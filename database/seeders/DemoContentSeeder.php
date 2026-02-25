<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $cats = [
            'Saúde',
            'Segurança',
            'Ambiente',
        ];

        $categories = [];
        foreach ($cats as $name) {
            $categories[$name] = Category::firstOrCreate([
                'slug' => Str::slug($name),
            ], [
                'name' => $name,
            ]);
        }

        Post::factory()->count(10)->create(); // se quiser usar factory

        // Ou pode criar manualmente alguns posts aqui...
    }
}
