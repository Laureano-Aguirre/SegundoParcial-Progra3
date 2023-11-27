<?php

include_once '../class/retiro.php';

class retiroController{
    public function agregarRetiro($tipoCuenta, $nroCuenta, $moneda, $importe, $fecha){
        $retiro = new RetiroBanco();
        $retiro->tipoCuenta = $tipoCuenta;
        $retiro->nroCuenta = $nroCuenta;
        $retiro->moneda = $moneda;
        $retiro->importe = $importe;
        $retiro->fecha = $fecha;
        $retiro->ajuste=false;
        return $retiro->agregarRetiro();
    }

    public function listarRetiros(){
        return RetiroBanco::listarRetiros();
    }

    public function buscarRetiro($idRetiro){
        $retiro = new RetiroBanco();
        $retiro->id = $idRetiro;
        return $retiro->buscarRetiro();
    }

    public function buscarRetiroNroCuenta($nroCuenta){
        $retiro = new RetiroBanco();
        $retiro->nroCuenta = $nroCuenta;
        return $retiro->buscarRetiroNroCuenta();
    }

    public function modificarEstado($idRetiro, $estado){
        $retiro = new RetiroBanco();
        $retiro->id = $idRetiro;
        $retiro->ajuste = $estado;
        return $retiro->modificarEstado();
    }
}


?>