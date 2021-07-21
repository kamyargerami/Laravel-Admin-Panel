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
        foreach (['kaspersky', 'eset', 'bitdefender', 'padra_mobile', 'idm'] as $name) {
            Product::firstOrCreate(['name' => $name]);
        }
    }
}
