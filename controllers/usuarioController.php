<?php


include_once '../class/usuario.php';

class usuarioController{
    public function agregarUsuario($nroDocumento, $user, $password, $nombre, $apellido, $email){
        $usuario = new UsuarioBanco();
        $usuario->nroDocumento = $nroDocumento;
        $usuario->user = $user;
        $usuario->password = $password;
        $usuario->nombre = $nombre;
        $usuario->nombre = $nombre;
        $usuario->apellido = $apellido;
        $usuario->email = $email;
        return $usuario->agregarUsuario();
    }
}


?>