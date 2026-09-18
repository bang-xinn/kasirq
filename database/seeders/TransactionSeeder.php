<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();
        $paymentMethods = ['cash', 'transfer', 'qris'];

        for ($i = 0; $i < 30; $i++) {
            $user = $users->random();
            $numItems = rand(1, 4);
            $selectedProducts = $products->random($numItems);
            $subtotal = 0;

            $invoiceNumber = 'INV-'.now()->subDays(rand(0, 30))->format('Ymd').'-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $user->id,
                'subtotal' => 0,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total' => 0,
                'payment_amount' => 0,
                'change_amount' => 0,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 8)),
                'updated_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 8)),
            ]);

            foreach ($selectedProducts as $product) {
                $qty = rand(1, 3);
                $itemSubtotal = $product->price * $qty;
                $subtotal += $itemSubtotal;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $qty,
                    'subtotal' => $itemSubtotal,
                ]);
            }

            $paymentAmount = $subtotal + rand(0, 2) * 5000;
            $transaction->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'payment_amount' => $paymentAmount,
                'change_amount' => $paymentAmount - $subtotal,
            ]);
        }
    }
}
