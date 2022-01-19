<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oversea extends Model
{
    protected $table = "overseas";

    // public function leave()
    // {
    //     return $this->hasOne(Leave::class, 'leave_id', 'id');
    // }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }
}