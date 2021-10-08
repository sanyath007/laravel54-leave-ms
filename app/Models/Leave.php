<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'leaves';
    // protected $primaryKey = 'id';
    public $incrementing = false; // false = ไม่ใช้ options auto increment
    public $timestamps = false; // false = ไม่ใช้ field updated_at และ created_at

    public function person()
    {
        return $this->belongsTo('App\Models\Person', 'leave_person', 'person_id');
    }
    
    public function leaveType()
    {
        return $this->belongsTo('App\Models\LeaveType', 'leave_type', 'id');
    }

    public function delegate()
    {
        return $this->belongsTo('App\Models\Person', 'leave_delegate', 'person_id');
    }
}
