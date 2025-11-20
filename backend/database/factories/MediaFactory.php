<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filename = fake()->lexify('image_????').'.jpg';

        return [
            'user_id' => User::factory(),
            'original_name' => $filename,
            'file_name' => $filename,
            'path' => "uploads/{$filename}",
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => fake()->numberBetween(50000, 1200000),
            'url' => config('app.url')."/storage/uploads/{$filename}",
        ];
    }
}
