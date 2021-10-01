<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasedMethod extends Model
{
    protected $table = 'purchased_methods';
    protected $primaryKey = 'method_id';
    public $incrementing = false; //ไม่ใช้ options auto increment
    // public $timestamps = false; //ไม่ใช้ field updated_at และ created_at

    public function asset()
    {
        return $this->hasMany('App\Models\Asset', 'purchased_method', 'method_id');
    }
}
