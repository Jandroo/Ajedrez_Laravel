<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;

class Master extends Controller
{
    protected function getIdUserFromToken($token){
    	if($token == null) return false;
    	$consulta = User::select("id")->where("token", "=", $token);

    	if($consulta->count() > 0){
    		return $consulta->get()->first()['id'];
    	}
        else return false;
    }
    protected function getIdUserFromName($name){
    	if($name == null) return false;
    	$consulta = User::select("id")->where("name", "=" ,$name);

    	if($consulta->count() > 0){
    		return $consulta->get()->first()['id'];
    	}
        else return false;
    }
}
