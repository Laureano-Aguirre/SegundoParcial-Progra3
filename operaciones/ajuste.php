<?php

class Ajuste implements JsonSerializable{

    private $id;
    private $idMovimiento;
    private $motivo;
    private $importe;


    public function __construct($id, $idMovimiento, $motivo, $importe)
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
    }
}

?>