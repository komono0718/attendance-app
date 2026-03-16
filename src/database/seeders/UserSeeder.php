<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 管理者
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        // 一般ユーザー
        User::create([
            'name' => 'User1',
            'email' => 'user1@test.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'User2',
            'email' => 'user2@test.com',
            'password' => Hash::make('password'),
        ]);
    }
}
