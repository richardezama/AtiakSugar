<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = ['id'];
    public function type(){
        return $this->belongsTo(Subscriptiontype::class, 'subscription_type');
    }
}
