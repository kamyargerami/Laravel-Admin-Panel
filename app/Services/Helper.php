<?php


namespace App\Services;


class Helper
{
    public static function orderBy(string $input = null): array
    {
        return $input ? explode('-', $input) : ['id', 'desc'];
    }
}
