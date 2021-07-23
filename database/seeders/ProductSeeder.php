<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['pav', 'pms', 'pmsp'] as $name) {
            Product::firstOrCreate(['name' => $name]);
        }
    }
}
