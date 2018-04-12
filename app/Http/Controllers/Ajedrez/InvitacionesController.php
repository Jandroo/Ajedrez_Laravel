<?php

namespace App\Http\Controllers\Ajedrez;

use Illuminate\Http\Request;
use App\Http\Controllers\Master;
use App\Http\Controllers\Fichas;
use App\InvitacionesPartida;
use App\Partida;
use App\Ficha;

class InvitacionesController extends Master
{
    function invitar(Request $request){
    	$user = $this->getIdUserFromToken($request->input('token'));
    	$user2 = $this->getIdUserFromName($request->input('name'));
    	$estado = 0;

    	if($user != false && $user2 != false){

    		if(InvitacionesPartida::where([["id_usuario1", $user], ["id_usuario2", $user2]])->orWhere([["id_usuario1", $user2], ["id_usuario2", $user]])->count() == 0){

    			$InvitacionesPartida = new InvitacionesPartida;
		    	$InvitacionesPartida->id_usuario1 = $user;
		    	$InvitacionesPartida->id_usuario2 = $user2;
		    	$InvitacionesPartida->save();
		    	$estado = 1;
		    	$mensaje="Se ha enviado la invitacion.";

    		}
    		else $mensaje="Esperando al usuario que acepte.";
    	}
    	else $mensaje="El usuario no quiere jugar.";

    	return response(json_encode(["estado" => $estado, "mensaje" => $mensaje]), 200)->header('Content-Type', 'application/json');
    }

    function ver(Request $request){
    	$id_usuario = $this->getIdUserFromToken($request->input('token'));
    	$estado = 0;

    	if($id_usuario != false){
    		$estado = 1;
    		$mensaje = InvitacionesPartida::from('users as u1')
    			->join('InvitacionesPartidas as ip', function($join){
                    $join->on('u1.id', '=', 'ip.id_usuario2');

                })->join('users as u2', function($join){
                    $join->on('u2.id', '=', 'ip.id_usuario1');

                })->where('u1.id', $id_usuario)
    			->select("u2.name")
    			->get()
    			->toArray();

    	}else $mensaje="No se ha encontrado el usuario.";

    	return response(json_encode(["estado" => $estado, "mensaje" => $mensaje]), 200)->header('Content-Type', 'application/json');
    }

    function responder(Request $request){
        $user = $this->getIdUserFromToken($request->input('token'));
        $user2 = $this->getIdUserFromName($request->input('name'));
        $respuesta = $request->input('respuesta');
        $estado = 0;

        if($user != false && $user2 != false){
            if($respuesta == 1){
                $estado = 1;
                InvitacionesPartida::where([["id_usuario1", $user2],["id_usuario2", $user]])->delete();

                $partida = new Partida();
                $partida->id_negro=$user2;
                $partida->id_blanco=$user;
                $partida->save();

                $this->generarTablero($partida->id);

                $mensaje = "Solicitud aceptada!";

            }
            else if($respuesta == 0){
                $estado = 2;
                InvitacionesPartida::where([["id_usuario1", $user],["id_usuario2", $user]])->delete();
                $mensaje = "Solicitud rechazada!";

            }
            else $mensaje = "Respuesta no valida.";
        }
        else $mensaje="No se ha encontrado el usuario.";
        
        return response(json_encode(["estado" => $estado, "mensaje" => $mensaje]), 200)->header('Content-Type', 'application/json');
    }

    private function insertarFicha($idPartida, $color, $tipoFicha, $fila, $columna){
        $ficha = new Ficha;
        $ficha->id_partida = $idPartida;
        $ficha->color = $color;
        $ficha->tipo = $tipoFicha;
        $ficha->fila = $fila;
        $ficha->columna = $columna;
        return $ficha->save();
    }

    private function generarTablero($idPartida){
        foreach (Fichas::getFichas() as $ficha) {
            $this->insertarFicha($idPartida, $ficha['color'], $ficha['ficha'], $ficha['fila'], $ficha['columna']);
        }
    }
}
