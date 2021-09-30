<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => ucfirst($this->faker->words(5, true)),
            'body' => $this->faker->sentences(4, true),
            'created_at' => $this->faker->dateTimeBetween('-30 days', now()),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pinned()
    {
        return $this->state(function (array $attributes) {
            return [
                'pinned' => true,
            ];
        });
    }
}
