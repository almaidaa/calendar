<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(1)->create();

        User::factory()->create([
            'name' => 'almaida',
            'username' => 'almaida',
            'email' => 'almaidaaa38@gmail.com',
            'password' => Hash::make('almaida'),
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'mnprasetya@gmail.com',
            'password' => Hash::make('admin'),
        ]);
    }
}
