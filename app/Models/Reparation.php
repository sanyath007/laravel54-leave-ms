<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reparation extends Model
{
    protected $table = 'reparations';    
    protected $primaryKey = 'reparation_id';
    // public $incrementing = false; //ไม่ใช้ options auto increment
    // public $timestamps = false; //ไม่ใช้ field updated_at และ created_at
    protected $fillable = ['status'];

    public function asset()
    {
        return $this->belongsTo('App\Models\Asset', 'asset_id', 'asset_id');
    }
  
    // public function budgetType()
    // {
    //     return $this->belongsTo('App\Models\BudgetType', 'budget_type', 'budget_type_id');
    // }
}
