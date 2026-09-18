<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin KasirQ',
            'email' => 'admin@kasirq.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Satu',
            'email' => 'kasir1@kasirq.com',
            'password' => bcrypt('password'),
            'role' => 'cashier',
        ]);

        User::create([
            'name' => 'Kasir Dua',
            'email' => 'kasir2@kasirq.com',
            'password' => bcrypt('password'),
            'role' => 'cashier',
        ]);
    }
}
