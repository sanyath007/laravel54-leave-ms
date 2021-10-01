<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetType extends Model
{
    protected $table = 'budget_types';
    protected $primaryKey = 'budget_type_id';
    public $incrementing = false; //ไม่ใช้ options auto increment
    // public $timestamps = false; //ไม่ใช้ field updated_at และ created_at

    public function asset()
    {
        return $this->hasMany('App\Models\Asset', 'budget_type', 'budget_type_id');
    }
}
