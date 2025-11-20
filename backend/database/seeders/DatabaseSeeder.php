<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()
            ->admin()
            ->create([
                'name' => 'CMS Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);

        $categories = collect([
            'Announcements',
            'Tutorials',
            'Releases',
        ])->map(fn ($name) => Category::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(8),
        ]));

        collect([
            [
                'title' => 'About',
                'excerpt' => 'Who we are',
                'body' => '<p>This page can be edited from the admin.</p>',
            ],
            [
                'title' => 'Contact',
                'excerpt' => 'Say hello',
                'body' => '<p>Contact us through the CMS.</p>',
            ],
        ])->each(fn ($data) => Page::create([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'excerpt' => $data['excerpt'],
            'body' => $data['body'],
            'is_published' => true,
            'published_at' => now(),
            'meta_title' => $data['title'],
            'meta_description' => $data['excerpt'],
        ]));

        Post::factory()
            ->count(10)
            ->state(fn () => [
                'user_id' => $admin->id,
                'category_id' => $categories->random()->id,
            ])
            ->create();

        Media::factory()
            ->count(3)
            ->state(fn () => [
                'user_id' => $admin->id,
            ])
            ->create();
    }
}
