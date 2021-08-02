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
        foreach (['admin', 'deputy', 'representation', 'supervisor', 'supervisor', 'support', 'developer'] as $name) {
            Role::firstOrCreate(['name' => $name]);
        }
    }
}
