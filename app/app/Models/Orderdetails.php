<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderdetails extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function checking(){
        return $this->belongsTo(Estatetype::class, 'checking_id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
}