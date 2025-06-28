<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'aqeel',
                'password' => Hash::make('tes12345'),
                'email' => 'aqeel@gmail.com',
                'phone_number' => '081232323232',
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'russel',
                'password' => Hash::make('tes12345'),
                'email' => 'russel@gmail.com',
                'phone_number' => '081223232323',
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'email' => 'admin@gmail.com',
                'phone_number' => '081223232323',
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
