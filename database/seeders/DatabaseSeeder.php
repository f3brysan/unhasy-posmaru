<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create(
            [
                'name' => 'Admin',
                'email' => 'x6oRt@example.com',
                'password' => bcrypt('password'),
                'no_induk' => 'superadmin'
            ]
        );       

        $user->assignRole('superadmin');
    }
}
