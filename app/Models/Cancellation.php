<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cancellation extends Model
{
    protected $table = 'cancellations';    
    // protected $primaryKey = 'cancel_id';
    // public $incrementing = false; //ไม่ใช้ options auto increment
    // public $timestamps = false; //ไม่ใช้ field updated_at และ created_at
    // protected $fillable = ['status'];

    public function leave()
    {
        return $this->belongsTo('App\Models\Leave', 'leave_id', 'id');
    }
}
