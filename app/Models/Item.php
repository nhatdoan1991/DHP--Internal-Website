<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    
    protected $table = 'item';

    public function order(){
        return $this->belongsTo(Order::class,'orderid');
    }


    


    protected $fillable= [
        'orderid',
        'itemname',
        'itemquantity',
    ];
}
