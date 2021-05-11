<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GroupOrder extends Model
{
    
    protected $table = 'grouporder';
    
    public function order(){
        return $this->hasMany(Order::class,'grouporderid');
    }
    public function user(){
        return $this->belongsTo(User::class,'refuserid');
    }
    public function deliver(){
        return $this->hasMany(Delivery::class,'grouporderid');
    }
    
    protected $fillable = [
        'groupname',
        'refuserid',
        'deliverydate',
    ];
}
