<?php

include_once '../db/AccesoDatos.php';

class Encuesta{
    public $idEncuesta;
    public $puntuacionMesa;
    public $puntuacionRestaurante;
    public $puntuacionMozo;
    public $puntuacionCocinero;

    public function agregarEncuesta(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into encuestas (puntuacion_mesa,puntuacion_restaurante,puntuacion_mozo,puntuacion_cocinero) values(:puntuacionMesa, :puntuacionRestaurante, :puntuacionMozo, :puntuacionCocinero)");
        $consulta->bindValue(':puntuacionMesa', $this->puntuacionMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionRestaurante', $this->puntuacionRestaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionMozo', $this->puntuacionMozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionCocinero', $this->puntuacionCocinero, PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();
    }

    public static function listarEncuestas(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_encuesta as id, puntuacion_mesa as puntuacionMesa, puntuacion_restaurante as puntuacionRestaurante, puntuacion_mozo as puntuacionMozo, puntuacion_cocinero as puntuacionCocinero FROM  encuestas");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "encuesta");
    }

}




?>