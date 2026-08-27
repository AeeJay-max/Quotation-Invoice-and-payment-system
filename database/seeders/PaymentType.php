<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Cash', 'Bank Transfer', 'Ecocash'];
        foreach ($types as $name) {
            \App\Models\PaymentType::firstOrCreate(['name' => $name]);
        }
    }
}
