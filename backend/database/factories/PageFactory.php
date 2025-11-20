<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'title' => $title,
            'excerpt' => fake()->sentence(10),
            'body' => collect(fake()->paragraphs(4))
                ->map(fn ($paragraph) => "<p>{$paragraph}</p>")
                ->implode(''),
            'is_published' => true,
            'published_at' => now(),
            'meta_title' => $title,
            'meta_description' => fake()->sentence(12),
        ];
    }
}
