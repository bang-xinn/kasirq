<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $makanan = Category::where('name', 'Makanan')->first();
        $minuman = Category::where('name', 'Minuman')->first();
        $snack = Category::where('name', 'Snack & Camilan')->first();

        $products = [
            // Makanan
            ['category_id' => $makanan->id, 'name' => 'Nasi Goreng Spesial', 'sku' => 'MKN-001', 'price' => 15000, 'cost_price' => 8000, 'stock' => 50, 'unit' => 'porsi'],
            ['category_id' => $makanan->id, 'name' => 'Mie Ayam Bakso', 'sku' => 'MKN-002', 'price' => 12000, 'cost_price' => 7000, 'stock' => 40, 'unit' => 'porsi'],
            ['category_id' => $makanan->id, 'name' => 'Ayam Bakar', 'sku' => 'MKN-003', 'price' => 20000, 'cost_price' => 12000, 'stock' => 30, 'unit' => 'porsi'],
            ['category_id' => $makanan->id, 'name' => 'Soto Ayam', 'sku' => 'MKN-004', 'price' => 13000, 'cost_price' => 7500, 'stock' => 35, 'unit' => 'porsi'],
            // Minuman
            ['category_id' => $minuman->id, 'name' => 'Es Teh Manis', 'sku' => 'MNM-001', 'price' => 5000, 'cost_price' => 1500, 'stock' => 100, 'unit' => 'gelas'],
            ['category_id' => $minuman->id, 'name' => 'Es Jeruk', 'sku' => 'MNM-002', 'price' => 7000, 'cost_price' => 3000, 'stock' => 80, 'unit' => 'gelas'],
            ['category_id' => $minuman->id, 'name' => 'Jus Alpukat', 'sku' => 'MNM-003', 'price' => 15000, 'cost_price' => 8000, 'stock' => 50, 'unit' => 'gelas'],
            ['category_id' => $minuman->id, 'name' => 'Kopi Hitam', 'sku' => 'MNM-004', 'price' => 8000, 'cost_price' => 3000, 'stock' => 60, 'unit' => 'gelas'],
            ['category_id' => $minuman->id, 'name' => 'Air Mineral Botol', 'sku' => 'MNM-005', 'price' => 4000, 'cost_price' => 2000, 'stock' => 200, 'unit' => 'botol'],
            // Snack
            ['category_id' => $snack->id, 'name' => 'Keripik Singkong', 'sku' => 'SNK-001', 'price' => 8000, 'cost_price' => 4000, 'stock' => 60, 'unit' => 'bungkus'],
            ['category_id' => $snack->id, 'name' => 'Pisang Goreng', 'sku' => 'SNK-002', 'price' => 10000, 'cost_price' => 5000, 'stock' => 40, 'unit' => 'porsi'],
            ['category_id' => $snack->id, 'name' => 'Onde-onde', 'sku' => 'SNK-003', 'price' => 2000, 'cost_price' => 800, 'stock' => 80, 'unit' => 'pcs'],
            ['category_id' => $snack->id, 'name' => 'Risoles Mayo', 'sku' => 'SNK-004', 'price' => 5000, 'cost_price' => 2500, 'stock' => 50, 'unit' => 'pcs'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
