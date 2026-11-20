<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect(['products.manage', 'orders.manage', 'users.manage'])
            ->map(fn ($name) => Permission::findOrCreate($name));

        Role::findOrCreate('admin')->syncPermissions($permissions);
        Role::findOrCreate('staff')->syncPermissions(['products.manage', 'orders.manage']);
    }
}
