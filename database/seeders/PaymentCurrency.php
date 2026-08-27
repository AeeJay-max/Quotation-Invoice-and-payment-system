<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentCurrency extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = ['USD', 'ZWL', 'RTGS'];
        foreach ($currencies as $name) {
            \App\Models\PaymentCurrency::firstOrCreate(['name' => $name]);
        }
    }
}
