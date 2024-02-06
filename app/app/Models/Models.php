<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function make(){
        return $this->belongsTo(Make::class, 'make_id');
    }
  
}