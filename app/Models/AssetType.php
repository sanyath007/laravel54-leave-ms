<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    protected $table = 'asset_types';
    protected $primaryKey = 'type_id';
    public $incrementing = false; // false = ไม่ใช้ options auto increment
    public $timestamps = false; // false = ไม่ใช้ field updated_at และ created_at

   //  public function assetClass()
  	// {
   //    	return $this->belongsTo('App\Models\AssetClass', 'class_id', 'class_id');
  	// }
    
    public function cate()
  	{
      	return $this->belongsTo('App\Models\AssetCategory', 'cate_id', 'cate_id');
  	}

    public function parcel()
    {
        return $this->hasMany('App\Models\Parcel', 'parcel_id', 'parcel_id');
    }
}
