<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPriceByStoppage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'source_destination' => 'array'
    ];

    public function company(){
        return $this->belongsTo(BusCompanies::class , 'company_id');
    }
}
