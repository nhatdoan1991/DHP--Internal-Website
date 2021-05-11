<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';

    //added customerid to end
    public function order(){
        return $this->hasMany(Order::class,'customerid');
    }
    protected $fillable= [
        'firstname',
        'lastname',
        'email',
        'phonenumber',
        'address',
        'city',
        'zipcode',
        'state'



    ];
}
