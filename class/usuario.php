<?php

include_once '../db/AccesoDatos.php';

class UsuarioBanco{
    public $id;
    public $nroDocumento;
    public $user;
    public $password;
    public $nombre;
    public $apellido;
    public $email;
    public $rol;
    public $estado;

    public function agregarUsuario(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into usuario (nro_documento,user,password,nombre,apellido,email,estado) values(:nroDocumento, :user, :password, :nombre, :apellido, :email, :estado)");
        $consulta->bindValue(':nroDocumento', $this->nroDocumento, PDO::PARAM_INT);
        $consulta->bindValue(':user', $this->user, PDO::PARAM_STR);
        $consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();
    }

    public function autenticarUsuario(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM usuario WHERE user=:usuario AND password=:pass");
        $consulta->bindValue(':usuario', $this->user, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $this->password, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetch() !== false;
    }
}


?>