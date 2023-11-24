<?php

include_once '../db/AccesoDatos.php';

class Empleado{
    public $id;
    public $usuario;
    public $password;
    public $nombre;
    public $apellido;
    public $rol;
    public $fechaAlta;

    public function agregarEmpleado(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into empleados (usuario,password,nombre,apellido,rol,fecha_Alta) values(:usuario, :password,:nombre, :apellido, :rol, '$this->fechaAlta')");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();
    }

    public static function listarEmpleados(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_empleado as id, nombre, apellido, rol, fecha_Alta as fechaAlta FROM  empleados");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");
    }

    public function modificarEmpleado(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("UPDATE empleados SET nombre=:nombre, apellido=:apellido, rol=:rol WHERE id_empleado=:id");

        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        return $consulta->execute();
    }

    public function borrarEmpleado(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("DELETE from empleados WHERE id_empleado=:id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public function autenticarEmpleado(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT * FROM empleados WHERE usuario=:usuario AND password=:pass");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $this->password, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetch() !== false;
    }
}


?>