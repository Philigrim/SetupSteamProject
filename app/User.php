<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usertype','firstname','lastname', 'email', 'password','city_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function lecturer(){
        return $this->belongsTo(Lecturer::class);
    }

    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function isRole(){
        return $this->usertype;
    }
}
