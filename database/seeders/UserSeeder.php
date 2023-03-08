<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@coba.com',
           'role' => 'admin',
            'password' => 'admincoba',
            'status' => 'active',
            'last_login' => now()
        ]);

        User::factory()->count(50)->create();
    }
}
