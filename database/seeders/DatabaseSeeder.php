<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class
        ]);
        $user = User::query()->create([
            'name' => 'super-admin',
            'email' => 'super.admin@gmail.com',
            'password' => '123456'
        ]);
    }
}
