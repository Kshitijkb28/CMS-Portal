<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'excerpt' => fake()->sentences(2, true),
            'body' => collect(fake()->paragraphs(5))
                ->map(fn ($paragraph) => "<p>{$paragraph}</p>")
                ->implode(''),
            'is_published' => true,
            'published_at' => now(),
            'meta_title' => $title,
            'meta_description' => fake()->sentence(12),
        ];
    }
}
