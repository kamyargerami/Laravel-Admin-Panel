<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'آرسام سافت',
            'email' => 'info@arsamsoft.com',
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make('secret'),
        ]);
    }
}
