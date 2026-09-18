<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'receipt_header', 'value' => 'KasirQ'],
            ['key' => 'receipt_footer', 'value' => 'Terima kasih! 🙏'],
            ['key' => 'qris_image', 'value' => 'images/qris.jpg'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
