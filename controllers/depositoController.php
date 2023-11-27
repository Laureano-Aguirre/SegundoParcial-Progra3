<?php

include_once '../class/deposito.php';

class depositoController{
    public function agregarDeposito($tipoCuenta, $nroCuenta, $moneda, $importe, $fecha){
        $deposito = new DepositoBanco();
        $deposito->tipoCuenta = $tipoCuenta;
        $deposito->nroCuenta = $nroCuenta;
        $deposito->moneda = $moneda;
        $deposito->importe = $importe;
        $deposito->fecha = $fecha;
        $deposito->ajuste = false;
        return $deposito->agregarDeposito();
    }

    public function listarDepositos(){
        return DepositoBanco::listarDepositos();
    }

    public function buscarDepTipoCuentaMonedaFecha($tipoCuenta, $moneda, $fecha){
        $deposito = new DepositoBanco();
        $deposito->tipoCuenta = $tipoCuenta;
        $deposito->moneda = $moneda;
        $deposito->fecha = $fecha;
        return $deposito->buscarDepTipoCuentaMonedaFecha();
    }

    public function buscarDepNroCuenta($nroCuenta){
        $deposito = new DepositoBanco();
        $deposito->nroCuenta = $nroCuenta;
        return $deposito->buscarDepNroCuenta();
    }

    public function buscarDepEntreFechas($fechaUno, $fechaDos){
        return DepositoBanco::buscarDepEntreFechas($fechaUno, $fechaDos);
    }

    public function buscarDepTipoCuenta($tipoCuenta){
        $deposito = new DepositoBanco();
        $deposito->tipoCuenta = $tipoCuenta;
        return $deposito->buscarDepTipoCuenta();
    }

    public function buscarDepMoneda($moneda){
        $deposito = new DepositoBanco();
        $deposito->moneda = $moneda;
        return $deposito->buscarDepMoneda();
    }

    public function buscarDeposito($idDeposito){
        $deposito = new DepositoBanco();
        $deposito->id = $idDeposito;
        return $deposito->buscarDeposito();
    }

    public function modificarEstado($idDeposito, $estado){
        $deposito = new DepositoBanco();
        $deposito->id = $idDeposito;
        $deposito->ajuste = $estado;
        return $deposito->modificarEstado();
    }

    function generarNombreImagen($tipoCuenta, $idCuenta, $idDeposito) {
        $nombreArchivo = $idCuenta . $tipoCuenta . $idDeposito .".png"; 
        return $nombreArchivo;
    }
}


?>