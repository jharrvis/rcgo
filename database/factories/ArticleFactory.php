<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'author_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->randomNumber(5),
            'excerpt' => $this->faker->sentence(15),
            'content' => "# " . $title . "\n\n" . $this->faker->paragraph(5),
            'featured_image' => null,
            'featured_image_alt' => null,
            'category' => 'Tips Bisnis',
            'tags' => ['usaha', 'mobil rc'],
            'status' => 'draft',
            'is_featured' => false,
            'published_at' => null,
            'seo_title' => null,
            'seo_description' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
    }
}
