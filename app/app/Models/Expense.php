<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function type(){
        return $this->belongsTo(Expensetype::class, 'expense_type');
    }
    public function staff(){
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function estate(){
        return $this->belongsTo(Estate::class, 'estate_id');
    }

    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}