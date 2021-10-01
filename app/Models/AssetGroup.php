<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetGroup extends Model
{
    protected $table = 'asset_groups';
    protected $primaryKey = 'group_id';
    // public $incrementing = false; // false = ไม่ใช้ options auto increment
    public $timestamps = false; // false = ไม่ใช้ field updated_at และ created_at

    public function cate()
    {
        return $this->hasMany('App\Models\AssetCate', 'group_id', 'group_id');
    }
}
