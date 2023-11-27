<?php

include_once '../class/usuario.php';

class authenticatorController{
    public function autenticarUsuario($user, $pass, $rol){
        $usuario = new UsuarioBanco();
        $usuario->user = $user;
        $usuario->password = $pass;
        $usuario->rol = $rol;
        return $usuario->autenticarUsuario();
    }
}



?>