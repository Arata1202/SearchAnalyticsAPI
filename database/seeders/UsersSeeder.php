<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => env('VITE_YOUR_NAME'),
            'email' => env('VITE_YOUR_EMAIL_ADDRESS'),
            'password' => Hash::make(env('VITE_YOUR_EMAIL_PASSWORD')),
        ]);
    }
}
