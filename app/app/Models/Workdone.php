<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workdone extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function user(){
        return $this->belongsTo(Admin::class, 'user_id');
    }
    public function job(){
        return $this->belongsTo(Repair::class, 'repair_id');
    }
   
}