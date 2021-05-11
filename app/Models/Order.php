<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = 'Order';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerid');
    }
    public function item()
    {
        return $this->hasMany(Item::class, 'orderid', 'id');
    }

    public function grouporder()
    {
        return $this->belongsTo(GroupOrder::class, 'grouporderid');
    }
    public function deliver()
    {
        return $this->belongsTo(Delivery::class, 'orderid', 'id');
    }
    public function message()
    {
        return $this->hasMany(Message::class, 'orderid', 'id');
    }



    protected $fillable = [
        'status',
        'deliverystatus',
        'grouporderid',
        'deliveryinstruction',
        'customerid',
        'deliverydate',
        'index',
        'reportnote'
    ];
}
