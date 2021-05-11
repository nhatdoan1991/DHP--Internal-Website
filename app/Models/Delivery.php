<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $table = 'deliver';

    public function order(){
        return $this->hasMany(Order::class,'id');
    }
    public function driver(){
        return $this->belongsTo(User::class,'refuserid');
    }
    public function grouporder(){
        return $this->belongsTo(GroupOrder::class,'grouporderid');
    }

    protected $fillable = [
        'grouporderid',
        'driverid',
        'orderid',
        'status'
    ];
}
 