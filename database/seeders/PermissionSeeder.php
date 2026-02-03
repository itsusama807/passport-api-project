<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'product.view',
            'product.create',
            'product.delete',
            'product.restore',
            'product.force-delete',
            'product.update-category',
            'product.trashed.view',

            'category.view',
            'category.create',
            'category.update',
            'category.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }
    }

}
