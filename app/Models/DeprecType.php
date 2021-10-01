<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeprecType extends Model
{
    protected $table = 'deprec_types';
    protected $primaryKey = 'deprec_type_id';
    public $incrementing = false; // false = ไม่ใช้ options auto increment
    public $timestamps = false; // false = ไม่ใช้ field updated_at และ created_at

    public function asset()
    {
        return $this->hasMany('App\Models\Asset', 'deprec_type', 'deprec_type_id');
    }
}
