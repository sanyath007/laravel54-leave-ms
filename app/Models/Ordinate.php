<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordinate extends Model
{
    protected $table = "ordinates";

    public function leave()
    {
        return $this->hasOne(Leave::class, 'leave_id', 'id');
    }
}