<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function getIdUserFromToken($token){
        if($token == null) return false;
        $consulta = User::select("id")->where("token", "=", $token);
        if($consulta->count() > 0){
            return $consulta->get()->first()['id'];
        }else return false;
    }
    protected static function getIdUserFromName($name){
        if($name == null) return false;
        $consulta = User::select("id")->where("name", "=" ,$name);
        if($consulta->count() > 0){
            return $consulta->get()->first()['id'];
        }else return false;
    }
}
