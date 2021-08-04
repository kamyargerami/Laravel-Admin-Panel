<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $routes = Route::getRoutes();
        $role = Role::find(1);
        $permissions = [];

        foreach ($routes as $route) {
            if (substr($route->getName(), 0, 5) != 'admin')
                continue;

            $permissions[] = Permission::firstOrCreate([
                'name' => $route->getName(),
            ])->id;
        }

        foreach (['read_others_data', 'store_data_for_others', 'change_others_data', 'delete_others_data', 'change_role'] as $custom_permission) {
            $permissions[] = Permission::firstOrCreate([
                'name' => $custom_permission,
            ])->id;
        }

        $role->syncPermissions($permissions);
    }
}
