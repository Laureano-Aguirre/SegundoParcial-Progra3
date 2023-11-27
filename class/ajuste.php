<?php

include_once '../db/AccesoDatos.php';

class AjusteBanco{

    public $id;
    public $idMovimiento;
    public $movimiento;
    public $motivo;

    public function agregarAjuste(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into ajuste (id_movimiento,movimiento,motivo) values(:idMovimiento, :movimiento, :motivo)");
        $consulta->bindValue(':idMovimiento', $this->idMovimiento, PDO::PARAM_INT);
        $consulta->bindValue(':movimiento', $this->movimiento, PDO::PARAM_STR);
        $consulta->bindValue(':motivo', $this->motivo, PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();
    }

    public static function listarAjustes(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_ajuste as idAjuste, id_movimiento as idMovimiento, motivo, importe FROM ajuste");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "AjusteBanco");
    }
}

?>