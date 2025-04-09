<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateUserProfilePicSeeder extends Seeder
{
    public function run(): void
    {
        User::whereNull('profile_pic')
            ->update(['profile_pic' => 'uploads/profile_pictures/user-image.png']);
    }
}
