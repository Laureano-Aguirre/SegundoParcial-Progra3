<?php

include_once '../class/ajuste.php';

class ajusteController{
    public function agregarAjuste($idMovimiento, $movimiento, $motivo){
        $ajuste = new AjusteBanco();
        $ajuste->idMovimiento = $idMovimiento;
        $ajuste->movimiento = $movimiento;
        $ajuste->motivo = $motivo;
        return $ajuste->agregarAjuste();
    }

    public function listarAjustes(){
        return AjusteBanco::listarAjustes();
    }
}

?>