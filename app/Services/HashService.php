<?php


namespace App\Services;


class HashService
{
    public static function rand($length = 25)
    {
        $characters = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

}
