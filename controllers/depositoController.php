<?php

include_once '../class/deposito.php';

class depositoController{
    public function agregarDeposito($idDeposito, $tipoCuenta, $nroCuenta, $moneda, $importe, $fecha){
        $deposito = new DepositoBanco();
        $deposito->id = $idDeposito;
        $deposito->tipoCuenta = $tipoCuenta;
        $deposito->nroCuenta = $nroCuenta;
        $deposito->moneda = $moneda;
        $deposito->importe = $importe;
        $deposito->fecha = $fecha;
        return $deposito->agregarDeposito();
    }

    public function listarDepositos(){
        return DepositoBanco::listarDepositos();
    }
}


?>