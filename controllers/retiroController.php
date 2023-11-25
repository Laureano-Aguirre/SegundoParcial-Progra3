<?php

include_once '../class/retiro.php';

class retiroController{
    public function agregarRetiro($idRetiro, $tipoCuenta, $nroCuenta, $moneda, $importe, $fecha){
        $retiro = new RetiroBanco();
        $retiro->id = $idRetiro;
        $retiro->tipoCuenta = $tipoCuenta;
        $retiro->nroCuenta = $nroCuenta;
        $retiro->moneda = $moneda;
        $retiro->importe = $importe;
        $retiro->fecha = $fecha;
        return $retiro->agregarRetiro();
    }

    public function listarRetiros(){
        return RetiroBanco::listarRetiros();
    }
}


?>