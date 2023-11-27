<?php

include_once '../db/AccesoDatos.php';


class DepositoBanco{
    public $id;
    public $tipoCuenta;
    public $nroCuenta;
    public $moneda;
    public $importe;
    public $fecha;
    public $ajuste;

    public function agregarDeposito(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into deposito (tipo_cuenta,id_cuenta,moneda,importe,fecha_deposito, ajuste) values(:tipoCuenta, :nroCuenta, :moneda, :importe, :fecha, :ajuste)");
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':nroCuenta', $this->nroCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':moneda', $this->moneda, PDO::PARAM_STR);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':ajuste', $this->ajuste, PDO::PARAM_BOOL);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();
    }

    public static function listarDepositos(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_deposito as idDeposito, tipo_cuenta as tipoCuenta, id_cuenta as idCuenta, moneda, importe, fecha_deposito as fechaDeposito FROM deposito");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "DepositoBanco");
    }

    public function buscarDepTipoCuentaMonedaFecha(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_deposito as id, tipo_cuenta as tipoCuenta, id_cuenta as nroCuenta, moneda, importe, fecha_deposito as fecha, ajuste FROM deposito WHERE tipo_cuenta=:tipoCuenta AND moneda=:moneda AND fecha_deposito=:fecha AND ajuste=false");
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':moneda', $this->moneda, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "DepositoBanco");
    }

    public function buscarDepNroCuenta(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_deposito as id, tipo_cuenta as tipoCuenta, id_cuenta as nroCuenta, moneda, importe, fecha_deposito as fecha, ajuste FROM deposito WHERE id_cuenta=:nroCuenta AND ajuste=0");
        $consulta->bindValue(':nroCuenta', $this->nroCuenta, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "DepositoBanco");
    }

    public static function buscarDepEntreFechas($fechaUno, $fechaDos){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_deposito as id, tipo_cuenta as tipoCuenta, id_cuenta as nroCuenta, moneda, importe, fecha_deposito as fecha, ajuste FROM deposito WHERE fecha_deposito BETWEEN :fechaUno AND :fechaDos AND ajuste=0");
        $consulta->bindValue(':fechaUno', $fechaUno, PDO::PARAM_STR);
        $consulta->bindValue(':fechaDos', $fechaDos, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "DepositoBanco");
    }

    public function buscarDepTipoCuenta(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_deposito as id, tipo_cuenta as tipoCuenta, id_cuenta as nroCuenta, moneda, importe, fecha_deposito as fecha, ajuste FROM deposito WHERE tipo_cuenta=:tipoCuenta AND ajuste=0");
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "DepositoBanco");
    }
    
    public function buscarDepMoneda(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_deposito as id, tipo_cuenta as tipoCuenta, id_cuenta as nroCuenta, moneda, importe, fecha_deposito as fecha, ajuste FROM deposito WHERE moneda=:moneda AND ajuste=0");
        $consulta->bindValue(':moneda', $this->moneda, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "DepositoBanco");
    }

    public function buscarDeposito(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_deposito as idDeposito, tipo_cuenta as tipoCuenta, id_cuenta as nroCuenta, moneda, importe, fecha_deposito as fecha, ajuste FROM deposito WHERE id_deposito=:idDeposito AND ajuste=0");
        $consulta->bindValue(':idDeposito', $this->id, PDO::PARAM_INT);
        $consulta->execute();
        $depositoBuscado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $depositoBuscado;
    }

    public function modificarEstado(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("UPDATE deposito SET ajuste=:ajuste WHERE id_deposito=:idDeposito");
        $consulta->bindValue(':idDeposito', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':ajuste', $this->ajuste, PDO::PARAM_INT);
        return $consulta->execute();
    }
    
}

?>