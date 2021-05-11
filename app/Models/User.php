<?php

namespace App\Models;

use App\Http\Controllers\DeliveringControler;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\GroupUse;

class User extends Authenticatable
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'User';
    protected $primaryKey = 'id';

    protected $fillable = [
        'firstname',
        'lastname',
        'role',
        'email',
        'password',
        'phonenumber'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function grouporder()
    {
        return $this->hasMany(GroupOrder::class, 'refuserid', 'id');
    }
    public function message()
    {
        return $this->hasMany(Message::class, 'userid', 'id');
    }
    public function generateToken()
    {
        $this->api_token = Str::random(60);
        $this->save();
        return $this->api_token;
    }
}
