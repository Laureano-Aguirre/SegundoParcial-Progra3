<?php

include_once '../db/AccesoDatos.php';

class AjusteBanco{

    public $id;
    public $idMovimiento;
    public $motivo;
    public $importe;


    /* public function __construct($id, $idMovimiento, $motivo, $importe)
    {
        $this->id = $id;
        $this->idMovimiento = $idMovimiento;
        $this->motivo = $motivo;
        $this->importe = $importe;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getId() {
        return $this->id;
    }

    public function getIdMovimiento() {
        return $this->idMovimiento;
    }

    public function getMotivo() {
        return $this->motivo;
    }

    public function getImporte() {
        return $this->importe;
    } */

    public function agregarAjuste(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into ajuste (id_ajuste,id_movimiento,motivo,importe) values(:idAjuste, :idMovimiento, :motivo, :importe)");
        $consulta->bindValue(':idAjuste', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':idMovimiento', $this->idMovimiento, PDO::PARAM_INT);
        $consulta->bindValue(':motivo', $this->motivo, PDO::PARAM_STR);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
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