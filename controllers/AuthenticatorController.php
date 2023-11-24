<?php

include_once '../class/empleado.php';

class authenticatorController{
    public function autenticarUsuario($user, $pass){
        $empleado = new Empleado();
        $empleado->usuario = $user;
        $empleado->password = $pass;
        return $empleado->autenticarEmpleado();
    }
}



?>