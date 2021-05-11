<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderid', 'id');
    }

    protected $fillable = [
        'orderid',
        'userid',
        'isRead',
        'isOperator',
        'message'
    ];
}
