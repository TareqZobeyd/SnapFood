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
                'name' => 'view-foods'
            ],
            [
                'name' => 'create-foods'
            ],
            [
                'name' => 'edit-foods'
            ],
            [
                'name' => 'delete-foods'
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

        $sellerRole = Role::query()->where('name', 'user')->first();
        $sellerRole->givePermissionTo(['create-foods', 'create-restaurants']);
    }
}
