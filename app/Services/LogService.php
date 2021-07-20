<?php


namespace App\Services;


use App\Models\Log;
use Illuminate\Database\Eloquent\Model;

class LogService
{
    public static function log(string $type, Model $model, int $user_id = null, array $data = null): bool
    {
        $stats = Log::create([
            'type' => $type,
            'loggable_id' => $model->id,
            'loggable_type' => get_class($model),
            'user_id' => $user_id,
            'data' => $data
        ]);

        return $stats ? true : false;
    }
}
