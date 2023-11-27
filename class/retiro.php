<?php
include_once '../db/AccesoDatos.php';

class RetiroBanco {
    public $id;
    public $tipoCuenta;
    public $nroCuenta;
    public $moneda;
    public $importe;
    public $fecha;
    public $ajuste;

    public function agregarRetiro(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into retiro (tipo_cuenta,id_cuenta,moneda,importe,fecha_retiro,ajuste) values(:tipoCuenta, :idCuenta, :moneda, :importe, :fechaRetiro, :ajuste)");
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':idCuenta', $this->nroCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':moneda', $this->moneda, PDO::PARAM_STR);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
        $consulta->bindValue(':fechaRetiro', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':ajuste', $this->ajuste, PDO::PARAM_BOOL);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();
    }

    public static function listarRetiros(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_retiro as idRetiro, tipo_cuenta as tipoCuenta, id_cuenta as nroCuenta, moneda, importe, fecha_retiro as fechaRetiro FROM retiro");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "RetiroBanco");
    }

    public function buscarRetiro(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT tipo_cuenta as tipoCuenta, id_cuenta as nroCuenta, importe FROM retiro WHERE id_retiro=:idRetiro");
        $consulta->bindValue(':idRetiro', $this->id, PDO::PARAM_INT);
        $consulta->execute();
        $retiroBuscado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $retiroBuscado;
    }

    public function modificarEstado(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("UPDATE retiro SET ajuste=:ajuste WHERE id_retiro=:idRetiro");
        $consulta->bindValue(':idRetiro', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':ajuste', $this->ajuste, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public function buscarRetiroNroCuenta(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_retiro as id, tipo_cuenta as tipoCuenta, id_cuenta as nroCuenta, moneda, importe, fecha_retiro as fecha, ajuste FROM retiro WHERE id_cuenta=:nroCuenta AND ajuste=0");
        $consulta->bindValue(':nroCuenta', $this->nroCuenta, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "RetiroBanco");
    }

}


?>