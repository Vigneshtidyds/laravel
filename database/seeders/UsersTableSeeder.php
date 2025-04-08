<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder {
    public function run() {
        $emails = [
            'user1@example.com', 'user2@example.com', 'user3@example.com',
            'user4@example.com', 'user5@example.com', 'user6@example.com',
            'user7@example.com', 'user8@example.com', 'user9@example.com',
            'user10@example.com', 'user11@example.com', 'user12@example.com',
            'user13@example.com', 'user14@example.com', 'user15@example.com'
        ];

        foreach ($emails as $email) {
            DB::table('users')->insert([
                'name' => Str::random(6),
                'email' => $email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

