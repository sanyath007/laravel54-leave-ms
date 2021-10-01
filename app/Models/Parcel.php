<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    protected $table = 'parcels';    
    protected $primaryKey = 'parcel_id';
    // public $incrementing = false; //ไม่ใช้ options auto increment
    // public $timestamps = false; //ไม่ใช้ field updated_at และ created_at
    protected $fillable = ['status'];

    public function assetType()
    {
        return $this->belongsTo('App\Models\AssetType', 'asset_type', 'type_id');
    }

    public function parcelType()
    {
        return $this->belongsTo('App\Models\AssetType', 'asset_type', 'type_id');
    }
  
    public function deprecType()
    {
        return $this->belongsTo('App\Models\DeprecType', 'deprec_type', 'deprec_type_id');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\AssetUnit', 'asset_unit', 'unit_id');
    }

  	public function asset()
  	{
      	return $this->hasMany('App\Models\Asset', 'parcel_id', 'parcel_id');
  	}
}
