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

    public function used()
    {
        return $this->hasMany(UsedLicence::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
