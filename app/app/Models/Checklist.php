<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function type(){
        return $this->belongsTo(Checklisttype::class, 'checklist_type');
    }
  
}