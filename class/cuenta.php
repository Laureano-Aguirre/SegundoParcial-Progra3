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

    /* public function __construct($id, $nombre, $apellido, $tipoDocumento, $nroDocumento, $email, $tipoCuenta, $moneda, $saldoInicial, $nombreImagen = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->tipoDocumento = $tipoDocumento;
        $this->nroDocumento = $nroDocumento;
        $this->email = $email;
        $this->tipoCuenta = $tipoCuenta;
        $this->moneda = $moneda;
        $this->saldoInicial = $saldoInicial;
        $this->estado = 'ACTIVO';
        $this->nombreImagen = $nombreImagen;
    } */

    /* public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    public function getNroDocumento()
    {
        return $this->nroDocumento;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    public function getMoneda()
    {
        return $this->moneda;
    }

    public function getSaldoInicial()
    {
        return $this->saldoInicial;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getNombreImagen()
    {
        return $this->id;
    } */

    public function agregarCuenta(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into cuenta (id_cuenta,nombre,apellido,tipo_documento,nro_documento,email,tipo_cuenta,moneda,saldo_inicial,estado,nombre_imagen) values(:id, :nombre, :apellido, :tipoDocumento, :nroDocumento, :email, :tipoCuenta, :moneda, :saldoInicial, :estado, :nombreImagen)");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
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
        $consulta = $objetoAccesoDato->retornarConsulta("UPDATE cuenta SET estado='inactivo' WHERE id_cuenta=:idCuenta");
        $consulta->bindValue(':idCuenta', $this->id, PDO::PARAM_INT);
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

    public static function existeCuenta($cuentas, $nombreCuenta, $tipoCuenta)
    {
        if ($cuentas) {
            foreach ($cuentas as $cuenta) {
                if ($cuenta->nombre === $nombreCuenta && $cuenta->tipoCuenta === $tipoCuenta) {
                    return 1;       //coincide ambas
                } elseif ($cuenta->nombre === $nombreCuenta && $cuenta->tipoCuenta !== $tipoCuenta) {
                    return 2;       //coincide solo el nombre
                } elseif ($cuenta->tipoCuenta === $tipoCuenta && $cuenta->nombre !== $nombreCuenta) {
                    return 3;       //coincide solo tipo de cuenta
                }
            }
        } else {
            echo "<br>Archivo vacio en existe cuenta...";
        }
        return 0;
    }

    public static function actualizarSaldo($cuentas, $nombreCuenta, $tipoCuenta, $saldo)
    {
        if ($cuentas) {
            foreach ($cuentas as $cuenta) {
                if ($cuenta->nombre === $nombreCuenta && $cuenta->tipoCuenta === $tipoCuenta) {
                    $cuenta->saldoInicial =  $cuenta->saldoInicial + $saldo;
                    return $cuentas;
                }
            }
        } else {
            echo '<br>Array vacio...';
            return false;
        }
    }

    public static function actualizarCuentas($cuentas, $cuentaModificada, $idCuenta)
    {
        if ($cuentas) {
            foreach ($cuentas as $key => $cuenta) {       //iteramos por indice numerico del array
                if ($cuenta->id = $idCuenta) {
                    $cuentas[$key] = $cuentaModificada; //reemplazamos en el indice que coinciden los id, un objeto por otro
                    return $cuentas;
                }
            }
        } else {
            echo '<br>Array vacio en actualizar cuentas';
        }
        return $cuentas;
    }

    public static function retornarCuenta($cuentas, $nombreCuenta, $tipoCuenta)
    {
        if ($cuentas) {
            foreach ($cuentas as $cuenta) {
                if ($cuenta->nombre === $nombreCuenta && $cuenta->tipoCuenta === $tipoCuenta) {
                    return $cuenta;
                }
            }
        } else {
            echo '<br>Array vacio...';
        }
        return null;
    }

    public static function buscarCuentaPorNro($cuentas, $numeroCuenta, $tipoCuenta = null)
    {
        if ($cuentas) {
            foreach ($cuentas as $cuenta) {
                if (($numeroCuenta == $cuenta->id && $tipoCuenta == $cuenta->tipoCuenta) || $numeroCuenta == $cuenta->id) {
                    return $cuenta;
                }
            }
        } else {
            echo '<br>Array vacio...';
        }
        return null;
    }

    public static function retiro($cuenta, $importe)
    {
        $resultado = $cuenta->saldoInicial = $cuenta->saldoInicial - $importe;
        if ($resultado >= 0) {
            return $cuenta;
        }
        return null;
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
