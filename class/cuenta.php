<?php
include_once '../db/AccesoDatos.php';

class CuentaBanco
{
    public $id;
    public $nombre;
    public $apellido;
    public $tipoDocumento;
    public $nroDocumento;
    public $email;
    public $tipoCuenta;
    public $moneda;
    public $saldoInicial;
    public $estado;
    public $nombreImagen;

    
    public function agregarCuenta(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into cuenta (nombre,apellido,tipo_documento,nro_documento,email,tipo_cuenta,moneda,saldo_inicial,estado,nombre_imagen) values(:nombre, :apellido, :tipoDocumento, :nroDocumento, :email, :tipoCuenta, :moneda, :saldoInicial, :estado, :nombreImagen)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $this->tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':nroDocumento', $this->nroDocumento, PDO::PARAM_INT);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':moneda', $this->moneda, PDO::PARAM_STR);
        $consulta->bindValue(':saldoInicial', $this->saldoInicial, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':nombreImagen', $this->nombreImagen, PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();
    }

    public static function listarCuentas(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_cuenta as idCuenta, nombre, apellido, tipo_documento as tipoDocumento, nro_documento as nroDocumento, email, tipo_cuenta as tipoCuenta, moneda, saldo_inicial as saldo, estado, nombre_imagen as nombreImagen FROM cuenta");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "CuentaBanco");
    }
    
    public function borrarCuenta(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("UPDATE cuenta SET estado='inactivo' WHERE id_cuenta=:idCuenta AND tipo_cuenta=:tipoCuenta");
        $consulta->bindValue(':idCuenta', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        return $consulta->execute();
    }

    public function modificarCuenta(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("UPDATE cuenta SET nombre=:nombre, apellido=:apellido, tipo_documento=:tipoDocumento, nro_documento=:nroDocumento, email=:email, tipo_cuenta=:tipoCuenta, moneda=:moneda WHERE id_cuenta=:idCuenta");
        $consulta->bindValue(':idCuenta', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $this->tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':nroDocumento', $this->nroDocumento, PDO::PARAM_INT);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':moneda', $this->moneda, PDO::PARAM_STR);
        return $consulta->execute();
    }

    public function buscarCuentaPorNroYTipo(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_cuenta as idCuenta, nombre, apellido, tipo_documento as tipoDocumento, nro_documento as nroDocumento, email, tipo_cuenta as tipoCuenta, moneda, saldo_inicial as saldo, estado, nombre_imagen as nombreImagen FROM cuenta WHERE id_cuenta=:idCuenta AND tipo_cuenta=:tipoCuenta");
        $consulta->bindValue(':idCuenta', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->execute();
        $cuentaBuscada = $consulta->fetchObject('CuentaBanco');
        return $cuentaBuscada;
    }

    public function buscarCuentaPorDniYTipo(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_cuenta as idCuenta, nombre, apellido, tipo_documento as tipoDocumento, nro_documento as nroDocumento, email, tipo_cuenta as tipoCuenta, moneda, saldo_inicial as saldo, estado, nombre_imagen as nombreImagen FROM cuenta WHERE nro_documento=:nroDocumento AND tipo_cuenta=:tipoCuenta");
        $consulta->bindValue(':nroDocumento', $this->nroDocumento, PDO::PARAM_INT);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->execute();
        $cuentaBuscada = $consulta->fetchObject('CuentaBanco');
        return $cuentaBuscada;
    }

    public function buscarCuentaPorNombreYTipo(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT moneda, saldo_inicial as saldo FROM cuenta WHERE nombre=:nombre AND tipo_cuenta=:tipoCuenta");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->execute();
        $cuentaBuscada = $consulta->fetch(PDO::FETCH_ASSOC);
        return $cuentaBuscada;
    }

    public function actualizarSaldo(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("UPDATE cuenta SET saldo_inicial= saldo_inicial + :monto WHERE id_cuenta=:idCuenta AND tipo_cuenta=:tipoCuenta");
        $consulta->bindValue(':monto', $this->saldoInicial, PDO::PARAM_INT);
        $consulta->bindValue(':idCuenta', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        return $consulta->execute();
    }

    public function retirar(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT saldo_inicial FROM cuenta WHERE id_cuenta=:idCuenta AND tipo_cuenta=:tipoCuenta");
        $consulta->bindValue(':idCuenta', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->execute();
        $saldoActual = $consulta->fetchColumn();
        //var_dump($saldoActual);
        //var_dump($this->saldoInicial);
        if($saldoActual >= $this->saldoInicial){
            $consulta = $objetoAccesoDato->retornarConsulta("UPDATE cuenta SET saldo_inicial= saldo_inicial - :monto WHERE id_cuenta=:idCuenta AND tipo_cuenta=:tipoCuenta");
            $consulta->bindValue(':monto', $this->saldoInicial, PDO::PARAM_INT);
            $consulta->bindValue(':idCuenta', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
            $consulta->execute();
            return 1;
        }
            return -1;
    }

     

    public static function ajustarSaldoDeposito($cuenta, $deposito, $ajuste)
    {
        $resultado = $deposito - $ajuste;
        $cuenta->saldoInicial = $cuenta->saldoInicial - $resultado;
        return $cuenta;
    }

    public static function ajustarSaldoRetiro($cuenta, $importeOperacion, $ajuste)
    {
        $resultado = $importeOperacion - $ajuste;
        $cuenta->saldoInicial = $cuenta->saldoInicial + $resultado;
        return $cuenta;
    }

    public function cambiarEstado($estado)
    {
        if ($estado == -1 || $estado == 1) {
            $this->estado = $estado;
            return true;
        }
    }



    public static function validarTipoCuenta($cuentas, $tipoCuenta, $nroDocumento)
    {
        if ($cuentas) {
            foreach ($cuentas as $cuenta) {
                if ($cuenta->tipoCuenta == $tipoCuenta && $cuenta->nroDocumento == $nroDocumento) {
                    return true;
                }
            }
        } else {
            echo '<br>Array de cuentas vacio en validarTipoCuenta';
        }
        return false;
    }

    
}
