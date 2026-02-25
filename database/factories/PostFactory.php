<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(8);

        // Gera um slug único (evita colisões)
        $baseSlug = Str::slug($title);
        $slug = $baseSlug . '-' . Str::lower(Str::random(6));

        // Usa uma categoria existente ou cria uma padrão
        $categoryId = Category::query()->inRandomOrder()->value('id');
        if (!$categoryId) {
            $categoryId = Category::create([
                'name' => 'Geral',
                'slug' => 'geral',
            ])->id;
        }

        return [
            'title' => $title,
            'slug' => $slug,
            'subtitle' => $this->faker->optional(0.6)->sentence(10),
            'excerpt' => $this->faker->optional(0.8)->paragraph(2),
            'author_name' => $this->faker->name(),
            'body' => collect($this->faker->paragraphs(6))->map(fn($p) => "<p>{$p}</p>")->implode("\n"),
            'image_url' => $this->faker->optional(0.7)->imageUrl(1200, 630, 'business', true),
            'image_2_url' => $this->faker->optional(0.2)->imageUrl(1200, 630, 'business', true),
            'image_3_url' => $this->faker->optional(0.2)->imageUrl(1200, 630, 'business', true),
            'image_4_url' => $this->faker->optional(0.2)->imageUrl(1200, 630, 'business', true),
            'video_url' => $this->faker->optional(0.1)->url(),
            'category_id' => $categoryId,
            'is_featured' => $this->faker->boolean(20),
            'is_popular' => $this->faker->boolean(25),
            'published_at' => $this->faker->optional(0.9)->dateTimeBetween('-30 days', 'now'),
            'meta_title' => $this->faker->optional(0.7)->sentence(10),
            'meta_description' => $this->faker->optional(0.7)->sentence(18),
        ];
    }
}
