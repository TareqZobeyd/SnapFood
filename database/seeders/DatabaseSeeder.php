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
        $superAdmin = User::query()->create([
            'name' => 'super-admin',
            'email' => 'super.admin@gmail.com',
            'phone'=> '09168900083',
            'password' => bcrypt('123456')
        ]);
        $superAdmin->assignRole('super-admin');
    }
}
