<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomElement([5000, 8000, 10000, 12000, 15000, 18000, 20000, 25000, 30000, 50000]);

        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'sku' => strtoupper(fake()->bothify('PRD-####-??')),
            'price' => $price,
            'cost_price' => $price * 0.7,
            'stock' => fake()->numberBetween(5, 100),
            'unit' => fake()->randomElement(['pcs', 'botol', 'pack', 'kg', 'liter']),
            'image' => null,
            'is_active' => true,
        ];
    }
}
