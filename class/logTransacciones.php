<?php

include_once '../db/AccesoDatos.php';

class LogTransaccionesBanco{
    public $id;
    public $fecha;
    public $usuario;
    public $operacion;

    public function agregarLogTransaccion(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into operaciones (usuario,fecha_operacion,operacion) values(:usuario, :fecha, :operacion)");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':operacion', $this->operacion, PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();
    }

    public static function listarOperaciones(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_log as id, usuario, fecha_operacion as fecha, operacion FROM operaciones");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "agregarLogTransaccion");
    }

}