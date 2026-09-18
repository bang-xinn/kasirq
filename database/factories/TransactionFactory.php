<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(15000, 200000);
        $discount = 0;
        $tax = 0;
        $total = $subtotal - $discount + $tax;
        $payment = $total + fake()->randomElement([0, 5000, 10000, 20000]);

        return [
            'invoice_number' => 'INV-'.now()->format('Ymd').'-'.strtoupper(fake()->bothify('####')),
            'user_id' => User::factory(),
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'tax_amount' => $tax,
            'total' => $total,
            'payment_amount' => $payment,
            'change_amount' => $payment - $total,
            'payment_method' => fake()->randomElement(['cash', 'transfer', 'qris']),
            'notes' => fake()->optional()->sentence(),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
