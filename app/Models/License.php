<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    const Types = ['yearly', 'trial'];

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
