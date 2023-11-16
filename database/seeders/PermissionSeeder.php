<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'super-admin'
            ],
            [
                'name' => 'seller'
            ],
            [
                'name' => 'user'
            ]

        ]);
        DB::table('permissions')->insert([
            [
                'name' => 'view-restaurants'
            ],
            [
                'name' => 'create-restaurants'
            ],
            [
                'name' => 'edit-restaurants'
            ],
            [
                'name' => 'delete-restaurants'
            ],
            [
                'name' => 'view-food'
            ],
            [
                'name' => 'create-food'
            ],
            [
                'name' => 'edit-food'
            ],
            [
                'name' => 'delete-food'
            ],
            [
                'name' => 'create-discount'
            ],
            [
                'name' => 'delete-discount'
            ]
        ]);

        $superAdminRole = Role::query()->where('name', 'super-admin')->first();
        $superAdminRole->syncPermissions(Permission::all());

        $sellerRole = Role::query()->where('name', 'seller')->first();
        $sellerRole->givePermissionTo(['create-food', 'edit-food',
            'delete-food', 'create-restaurants',
            'edit-restaurants', 'delete-restaurants']);
    }
}
