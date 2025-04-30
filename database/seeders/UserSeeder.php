<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create 1 specific user
        User::create([
            'name' => 'dennis wijaya',
            'email' => 'dens6942@gmail.com',
            'google_id' => '1234567890',
            'avatar' => 'https://lh3.googleusercontent.com/a/ACg8ocIA0Ul5nLfbUDAoRZ5q21Tlu3FS43Dppo0ztgJOhVOcZL1ZqlQn=s96-c',
            'role' => 'admin',
            'password' => bcrypt('dennis123'),
        ]);
    }
}
