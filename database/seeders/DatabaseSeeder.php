<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {        
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'baak']);
        Role::create(['name' => 'mahasiswa']);
        Role::create(['name' => 'pimpinan']);
    }
}
