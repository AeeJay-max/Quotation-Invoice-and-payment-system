<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'status' => true,
                'role_id' => 1,
                'password' => bcrypt('password')
            ]
        );
    }
}
