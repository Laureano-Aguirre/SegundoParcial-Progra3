<?php

include_once '../class/cuenta.php';

class cuentaController{
    public function agregarCuenta($id, $nombre, $apellido, $tipoDocumento, $nroDocumento, $email, $tipoCuenta, $moneda, $saldoInicial, $nombreImagen = null){
        $cuenta = new CuentaBanco();
        $cuenta->id = $id;
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

    public function borrarCuenta($idCuenta){
        $cuenta = new CuentaBanco();
        $cuenta->id = $idCuenta;
        return $cuenta->borrarCuenta();
    }

    public function buscarCuentaPorNroYTipo($idCuenta, $tipoCuenta){
        $cuenta = new CuentaBanco();
        $cuenta->id = $idCuenta;
        $cuenta->tipoCuenta = $tipoCuenta;
        return $cuenta->buscarCuentaPorNroYTipo();
    }
}

?>