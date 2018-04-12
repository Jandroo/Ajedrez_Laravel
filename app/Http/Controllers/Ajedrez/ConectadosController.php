<?php

namespace App\Http\Controllers\Ajedrez;

use Illuminate\Http\Request;
use App\Http\Controllers\Master;
use Auth;
use App\User;

class ConectadosController extends Master
{
     function login(Request $request){
        $password = $request->input('password');
        $email = $request->input('email');

        header("Access-Control-Allow-Origin: *");
        
        if (Auth::attempt(['email' => $email, 'password' => $password])){
            $token = $this->generateToken();

            if(User::where([['id', Auth::id()], ['token', null]])->update(array('token' => $token))){
                $estado = 1;

            }
            else{
                $estado = 0;
                if(User::where('email', $email)->update(array('token' => null)))
                    $mensaje = "Esta cuenta ya esta logeada, se ha forzado el cierre de sesion, inicie sesion de nuevo.";
                else
                    $mensaje = "Esta cuenta ya esta logeada, se ha intentado forzar el cierre de session, pero ha fallado, intentelo de nuevo.";
            }
            
        }
        else{
            $estado = 0;
            $mensaje = "Email o contraseña incorrecta.";
        }

        if($estado)
            return response(json_encode(["estado" => $estado, "token" => $token]), 200)->header('Content-Type', 'application/json');
        else
            return response(json_encode(["estado" => $estado, "mensaje" => $mensaje]), 200)->header('Content-Type', 'application/json');
    }

    function logout(Request $request){
        $token = $request->input('token');
        $status = User::where('token', $token)->update(array('token' => null)) ? 1 : 0;
        return response(json_encode(["status" => $status]), 200)->header('Content-Type', 'application/json');
    }


    function verConectados(Request $request){
        $id_usuario = $this->getIdUserFromToken($request->input('token'));
        $estado = 0;

        if($id_usuario != false){
            $estado = 1;
            $consulta = User::select("name")
                  ->where([["token", "<>", "null"],["id", "<>", $id_usuario]])
                  ->get();

            $usernames = [];
            foreach ($consulta as $value) {
                $usernames[] = $value["name"];
            }
        }
        else $mensaje="No se ha econtrado el usuario.";

        if($estado)
            return response(json_encode(["estado" => $estado, "usernames" => $usernames]), 200)->header('Content-Type', 'application/json');
        else
            return response(json_encode(["estado" => $estado, "mensaje" => $mensaje]), 200)->header('Content-Type', 'application/json');
    }

    private function generateToken(){
        do{
            $token = md5(uniqid(rand(), true));
        }
        while(User::where("token", $token)->count() >= 1);

        return $token;
    }
}
