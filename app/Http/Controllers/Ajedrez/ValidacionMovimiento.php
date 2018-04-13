<?php

namespace App\Http\Controllers\Ajedrez;

use App\Http\Controllers\Controller;
use App\Ficha;

class ValidacionMovimiento
{
	public static function checkMovimiento(Ficha $ficha, $to){
    switch ($ficha->tipo) {
        
        case 'torre':
            return Movimientos::torre($ficha, $to);
            break;
    }
    return false;
    }
    
	private static function torre(Ficha $ficha, $to){
		$num1 = $ficha->fila - $to['fila'];
		$num2 = $ficha->columna - $to['columna'];
		return ($num1 > -2 && $num1 < 2 && $num2 > -2 && $num2 < 2);
	}
}
