<?php

include_once '../class/cuenta.php';

class cuentaController{
    public function agregarCuenta($nombre, $apellido, $tipoDocumento, $nroDocumento, $email, $tipoCuenta, $moneda, $saldoInicial, $nombreImagen = null){
        $cuenta = new CuentaBanco();
        $cuenta->nombre = $nombre;
        $cuenta->apellido = $apellido;
        $cuenta->tipoDocumento = $tipoDocumento;
        $cuenta->nroDocumento = $nroDocumento;
        $cuenta->email = $email;
        $cuenta->tipoCuenta = $tipoCuenta;
        $cuenta->moneda = $moneda;
        $cuenta->saldoInicial = $saldoInicial;
        $cuenta->estado = 'ACTIVO';
        $cuenta->nombreImagen = $nombreImagen;
        return $cuenta->agregarCuenta();
    }

    public function listarCuentas(){
        return CuentaBanco::listarCuentas();
    }

    public function modificarCuenta($idCuenta, $nombre, $apellido, $tipoDocumento, $nroDocumento, $email, $tipoCuenta, $moneda){
        $cuenta = new CuentaBanco();
        $cuenta->id = $idCuenta;
        $cuenta->nombre = $nombre;
        $cuenta->apellido = $apellido;
        $cuenta->tipoDocumento = $tipoDocumento;
        $cuenta->nroDocumento = $nroDocumento;
        $cuenta->email = $email;
        $cuenta->tipoCuenta = $tipoCuenta;
        $cuenta->moneda = $moneda;
        return $cuenta->modificarCuenta();
    }

    public function borrarCuenta($idCuenta, $tipoCuenta){
        $cuenta = new CuentaBanco();
        $cuenta->id = $idCuenta;
        $cuenta->tipoCuenta = $tipoCuenta;
        return $cuenta->borrarCuenta();
    }

    public function buscarCuentaPorNroYTipo($idCuenta, $tipoCuenta){
        $cuenta = new CuentaBanco();
        $cuenta->id = $idCuenta;
        $cuenta->tipoCuenta = $tipoCuenta;
        if($cuenta->buscarCuentaPorNroYTipo() == false){
            return -1;
        }
        return 1;
    }

    public function buscarCuentaPorDniYTipo($nroDocumento, $tipoCuenta){
        $cuenta = new CuentaBanco();
        $cuenta->nroDocumento = $nroDocumento;
        $cuenta->tipoCuenta = $tipoCuenta;
        if($cuenta->buscarCuentaPorDniYTipo() == false){
            return -1;
        }
        return 1;
    }

    public function buscarCuentaPorNombreYTipo($nombre, $tipoCuenta){
        $cuenta = new CuentaBanco();
        $cuenta->nombre = $nombre;
        $cuenta->tipoCuenta = $tipoCuenta;
        return $cuenta->buscarCuentaPorNombreYTipo();
    }

    public function actualizarSaldo($idCuenta, $tipoCuenta, $importe){
        $cuenta = new CuentaBanco();
        $cuenta->id = $idCuenta;
        $cuenta->tipoCuenta = $tipoCuenta;
        $cuenta->saldoInicial = $importe;
        return $cuenta->actualizarSaldo();
    }

    public function retirar($idCuenta, $tipoCuenta, $importe){
        $cuenta = new CuentaBanco();
        $cuenta->id = $idCuenta;
        $cuenta->tipoCuenta = $tipoCuenta;
        $cuenta->saldoInicial = $importe;
        return $cuenta->retirar();
    }

    function generarNombreImagen($tipoCuenta, $nroCuenta) {
        $nombreArchivo = $nroCuenta . $tipoCuenta . ".png"; 
        return $nombreArchivo;
    }

}
?>