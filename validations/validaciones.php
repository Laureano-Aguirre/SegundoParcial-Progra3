<?php

Class Validaciones{

    public static function validarNombre($str){
        return preg_match('/^[A-Za-z\s]+$/', $str);       //devuelve false en caso de que el nombre no sea valido (contenga solo letras)
    }

    public static function validarDni($str){
        return preg_match('/^\d{8}$/', $str);     // ^ indica que comienza y $ termina con el mismo caracter \d que representa a numeros. devuelve true si es valido
    }

    public static function validarCorreo($str){
        return preg_match('/^.*@.*\.com$/', $str) && preg_match('/@/', $str, $matches) && count($matches) === 1;        //comprueba que haya unicamente un @ y termine en .com
    }

    public static function validarTipoCuenta($str){
        $patrones = [
            '/^CA\$$/',           // caja de ahorro en pesos
            '/^CAU\$S$/',          // caja de ahorro en dólares
            '/^CC\$$/',           // cuenta Corriente en pesos
            '/^CCU\$S$/'          // cuenta Corriente en dólares
        ];
    
        foreach ($patrones as $patron) {
            if (preg_match($patron, $str)) {
                return true;
            }
        }
    
        return false;
    }

    public static function validarMoneda($str) {
        $patronMoneda = '/(\$|U\$S)$/';
        return preg_match($patronMoneda, $str);
    }

    public static function validarSaldoInicial($monto){
        if($monto >= 0){
            return true;
        }
        return false;
    }

}


?>