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

        foreach ($routes as $route) {
            if (substr($route->getName(), 0, 5) != 'admin')
                continue;

            Permission::firstOrCreate([
                'name' => $route->getName(),
            ]);

            $role->syncPermissions($route->getName());
        }
    }
}
