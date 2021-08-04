<?php


namespace App\Services;


use App\Models\License;

class LicenseService
{
    public static function create($type, $max_use, $user_id, $status, $product_id, $key_length)
    {
        do {
            $key = HashService::rand($key_length);
        } while (License::where('key', $key)->count());

        return License::create([
            'type' => $type,
            'max_use' => $max_use,
            'user_id' => $user_id,
            'status' => $status,
            'product_id' => $product_id,
            'key' => $key,
        ]);
    }

    public static function getExpireDate($type)
    {
        if ($type == 'trial') {
            return now()->addMonth()->toDateString();
        }

        return now()->addMonths(explode('_', $type)[0])->toDateString();
    }
}
