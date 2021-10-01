<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPrefix extends Model
{
    protected $table = 'supplier_prefixes';
    protected $primaryKey = 'prefix_id';
    public $incrementing = false; //ไม่ใช้ options auto increment
    public $timestamps = false; //ไม่ใช้ field updated_at และ created_at

    public function supplier()
    {
        return $this->hasMany('App\Models\Supplier', 'prefix_id', 'prefix_id');
    }
}
