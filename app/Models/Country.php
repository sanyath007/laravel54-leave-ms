<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = "countries";

    public function oversea()
    {
        return $this->hasMany(Oversea::class, 'country', 'id');
    }
}