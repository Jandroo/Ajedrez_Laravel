<?php

namespace App\Http\Controllers\Ajedrez;

use App\Http\Controllers\Controller;
use App\Ficha;

class ValidacionMovimiento
{
	public static function checkMovimiento(Ficha $ficha, $to){
    switch ($ficha->tipo) {
        
        case 'torre':
            return ValidacionMovimiento::torre($ficha, $to);
            break;
    }
    return false;
    }
    
	private static function torre(Ficha $ficha, $to){
        
        return ($ficha->fila != $to['fila'] && $ficha->columna == $to['columna']) || ($ficha->fila == $to['fila'] && $ficha->columna != $to['columna']);
	}
}
