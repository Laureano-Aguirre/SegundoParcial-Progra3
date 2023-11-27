<?php

include_once '../class/logTransacciones.php';

class logTransaccionesController{
    public function agregarLogTransaccion($user, $fecha, $operacion){
        $log = new LogTransaccionesBanco();
        $log->usuario = $user;
        $log->fecha = $fecha;
        $log->operacion = $operacion;
        return $log->agregarLogTransaccion();
    }

    public function listarOperaciones(){
        return LogTransaccionesBanco::listarOperaciones();
    }
}



?>