<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetUnit extends Model
{
    protected $table = 'asset_units';
    protected $primaryKey = 'unit_id';
    public $incrementing = false; //ไม่ใช้ options auto increment
    public $timestamps = false; //ไม่ใช้ field updated_at และ created_at

    public function asset()
    {
        return $this->hasMany('App\Models\Asset', 'unit', 'unit_id');
    }
}
