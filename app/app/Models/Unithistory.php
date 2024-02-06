<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unithistory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function estate(){
        return $this->belongsTo(Estate::class, 'estate_id');
    }
    public function tenant(){
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}