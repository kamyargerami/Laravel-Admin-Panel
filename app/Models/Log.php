<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $dates = [
        'created_at'
    ];

    protected $guarded = [];

    const UPDATED_AT = null;

    protected $casts = [
        'data' => 'array'
    ];

    public function compile()
    {
        return __('log.' . $this->type, $this->data ?: []);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
