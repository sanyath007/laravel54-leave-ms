<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depreciation extends Model
{
    protected $table = 'depreciations';
    protected $primaryKey = 'deprec_id';
    // public $incrementing = false; //ไม่ใช้ options auto increment
    // public $timestamps = false; //ไม่ใช้ field updated_at และ created_at
    // protected $fillable = ['status'];
  	
  	public function asset()
    {
        return $this->hasMany('App\Models\Asset', 'asset_id', 'asset_id');
    }
}
