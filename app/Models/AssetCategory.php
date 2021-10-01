<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    protected $table = 'asset_categories';
    protected $primaryKey = 'cate_id';
    // public $incrementing = false; // false = ไม่ใช้ options auto increment
    public $timestamps = false; // false = ไม่ใช้ field updated_at และ created_at

    public function group()
  	{
      	return $this->belongsTo('App\Models\AssetGroup', 'group_id', 'group_id');
  	}
  	
    public function type()
    {
        return $this->hasMany('App\Models\AssetType', 'cate_id', 'cate_id');
    }
}
