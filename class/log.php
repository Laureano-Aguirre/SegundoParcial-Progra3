<?php
include_once '../db/AccesoDatos.php';

class LogBanco{
    public $id;
    public $user;
    public $fecha;


    public function agregarLog(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into log (usuario,fecha_log) values(:user, :fecha)");
        $consulta->bindValue(':user', $this->user, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();
    }
}

?>