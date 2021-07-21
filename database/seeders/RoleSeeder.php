<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['representation', 'developer', 'supervisor', 'supervisor', 'support'] as $name) {
            Role::firstOrCreate(['name' => $name]);
        }
    }
}
