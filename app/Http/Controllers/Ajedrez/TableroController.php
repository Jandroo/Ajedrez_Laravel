<?php

namespace App\Http\Controllers\Ajedrez;

use Illuminate\Http\Request;
use App\Http\Controllers\Master;
use App\User;
use App\Partida;
use App\Ficha;
use App\Http\Controllers\Ajedrez\ValidacionMovimiento;

class TableroController extends Master
{
    function ver(Request $request){
        $user = $this->getIdUserFromToken($request->input('token'));
        $user2 = $this->getIdUserFromName($request->input('name'));

        header("Access-Control-Allow-Origin: *");

        if($user != false && $user2 != false){
            $partida = Partida::select("id")->where([["id_negro", $user],["id_blanco", $user2]])->orWhere([["id_negro", $user2],["id_blanco", $user]]);

            if($partida->count() > 0){
                $idPartida = $partida->first()->toArray()["id"];
                $partida = $partida->first()->toArray();
                $idPartida = $partida["id"];
                $turno = $partida["turno"];
                $fichas = Ficha::select("color", "tipo", "fila", "columna")->where("id_partida", $idPartida)->get()->toArray();
                $tablero = [
                    "turno" => $turno,
                    "fichas" => $fichas
                ];

            }
            else $mensaje = "No se ha encontrado la partida.";
            
        }
        else $mensaje="El usuario no quiere jugar.";
        
        return response(json_encode(["tablero" => @$tablero]), 200)->header('Content-Type', 'application/json');
    }

    function moverFicha(Request $request){
        $user = User::getIdUserFromToken($request->input('token'));
        $user2 = User::getIdUserFromName($request->input('name'));
        $toFila = $request->input('toFila');
        $toColumna = $request->input('toColumna');
        $fromFila = $request->input('fromFila');
        $fromColumna = $request->input('fromColumna');

        header("Access-Control-Allow-Origin: *");

        if($user != false && $user2 != false){
            $partida = Partida::select("id", "turno", "id_negro", "id_blanco")
            ->where([["id_negro", $user], 
              ["id_blanco", $user2]])

            ->orWhere([["id_negro", $user2], 
              ["id_blanco", $user]]);
            
            if($partida->count() == 1){
                $partida = $partida->first();
                
                if(($partida->turno === "n" && $partida->id_negro == $user) || 
                   ($partida->turno === "b" && $partida->id_blanco == $user)){

                    $ficha = Ficha::where([["id_partida", $partida->id], ["fila", $toFila], ["columna", $toColumna], ["color", $partida->turno]]);

                if($ficha->count() == 1){
                    if(ValidacionMovimiento::checkMovimiento($ficha->first(),["columna" => $fromColumna, "fila" => $fromFila])){
                        $fichaTarget = Ficha::where([["id_partida", $partida->id], ["fila", $fromFila], ["columna", $fromColumna]]);

                        if($fichaTarget->count() > 0){
                            $fichaMia = $fichaTarget->first()->color === $partida->turno;
                            if(!$fichaMia) $fichaTarget->delete();

                        }
                        else $fichaMia = false;

                            //Fin partida
                        if(Ficha::where([["id_partida", $partida->id], ["color", ($partida->turno === "n" ? "b" : "n")], ["tipo", "torre"]])->count()==0){
                            $mensaje = "Fin de la partida, el ganador es el jugador ". ($partida->turno === "b" ? "blanco" : "negro");

                            Ficha::where("id_partida", $partida->id)->delete();
                            $partida->delete();

                        }
                            //Mover Ficha
                        else if(!$fichaMia){
                            $ficha = $ficha->first();
                            $ficha->columna = $fromColumna;
                            $ficha->fila = $fromFila;
                            $ficha->save();

                            $partida->turno = ($partida->turno === "n" ? "b" : "n");
                            $partida->save();

                            $mensaje="ficha movida.";

                        }
                        else $mensaje="No puedes mover tu ficha a un lugar donde hay otra ficha tuya.";
                    }else $mensaje="Movimiento no permitido.";

                } else if($ficha->count() > 1) $mensaje="Se ha encontrado mas de 1 ficha en la misma casilla.";
                else $mensaje="No hay ninguna ficha tuya en la casilla seleccionada.";
            }
            else $mensaje = "No es tu turno.";

        }
        else $mensaje = "No se ha encontrado la partida.";

    }
    else $mensaje="No se ha podido obtener el usuario.";

    return response(json_encode(["mensaje" => $mensaje]), 200)->header('Content-Type', 'application/json');
}
}
