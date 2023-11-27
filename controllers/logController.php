<?php

include_once '../class/log.php';

class logController{
    public function agregarLog($user, $fecha){
        $log = new LogBanco();
        $log->user = $user;
        $log->fecha = $fecha;
        return $log->agregarLog();
    }
}



?>