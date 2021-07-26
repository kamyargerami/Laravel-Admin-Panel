<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedLicence extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
