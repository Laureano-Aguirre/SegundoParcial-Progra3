<?php

include_once '../class/ajuste.php';

class ajusteController{
    public function agregarAjuste($idAjuste, $idMovimiento, $motivo, $importe){
        $ajuste = new AjusteBanco();
        $ajuste->id = $idAjuste;
        $ajuste->idMovimiento = $idMovimiento;
        $ajuste->motivo = $motivo;
        $ajuste->importe = $importe;
        return $ajuste->agregarAjuste();
    }

    public function listarAjustes(){
        return AjusteBanco::listarAjustes();
    }
}

?>