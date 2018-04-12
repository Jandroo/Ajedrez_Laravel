<?php

namespace App\Http\Controllers;

class Fichas{
	static final function getFichas(){
		return [
            ["color" => "b", "ficha" => "rey", "fila" => 1, "columna" => 5]
        ];
	}
}