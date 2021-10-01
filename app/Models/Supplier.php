<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    public $incrementing = false; //ไม่ใช้ options auto increment
    // public $timestamps = false; //ไม่ใช้ field updated_at และ created_at

    public function prefixes()
    {
        return $this->belongsTo('App\Models\SupplierPrefix', 'prefix_id', 'prefix_id');
    }

    public function asset()
    {
        return $this->hasMany('App\Models\Asset', 'supplier', 'supplier_id');
    }
}
