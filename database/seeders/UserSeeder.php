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
            'nama' => 'Administrator',
            'email' => 'admin@coba.com',
           'role' => 'admin',
            'password' => 'admincoba',
            'status' => 'aktif',
            'last_login' => now()
        ]);

        User::factory()->count(50)->create();
    }
}
