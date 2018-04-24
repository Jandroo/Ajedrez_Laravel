<?php

namespace App\Http\Controllers;

class Fichas{
	static final function getFichas(){
		return [
            ["color" => "b", "ficha" => "torre", "fila" => 1, "columna" => 1],
            ["color" => "n", "ficha" => "torre", "fila" => 8, "columna" => 1]
        ];
	}
}