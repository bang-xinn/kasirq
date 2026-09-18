<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan', 'description' => 'Berbagai jenis makanan'],
            ['name' => 'Minuman', 'description' => 'Berbagai jenis minuman'],
            ['name' => 'Snack & Camilan', 'description' => 'Makanan ringan dan camilan'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }
}
