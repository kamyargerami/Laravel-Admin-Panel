<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    use HasFactory, SoftDeletes;

    const Types = ['1_month', '2_month', '3_month', '6_month', '12_month', '24_month', 'trial'];

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

    public function first_use()
    {
        return $this->hasOne(UsedLicence::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . $this->last_name;
    }
}
