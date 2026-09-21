<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'whatsapp' => '081234567890',
                'password' => Hash::make('11223344'),
                'role' => 'admin',
                'balance' => 0,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'clipper@gmail.com'],
            [
                'name' => 'Clipper Pro',
                'whatsapp' => '081234567891',
                'password' => Hash::make('11223344'),
                'role' => 'clipper',
                'balance' => 0,
                'is_active' => true,
            ]
        );
    }
}
