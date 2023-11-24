<?php

class Cuenta implements JsonSerializable
{
    private $id;
    private $nombre;
    private $apellido;
    private $tipoDocumento;
    private $nroDocumento;
    private $email;
    private $tipoCuenta;
    private $moneda;
    private $saldoInicial;
    private $estado;
    private $nombreImagen;

    public function __construct($id, $nombre, $apellido, $tipoDocumento, $nroDocumento, $email, $tipoCuenta, $moneda, $saldoInicial, $nombreImagen = null)
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
    }

    public function jsonSerialize()
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

    public static function modificarCuenta($cuenta, $nombre, $apellido, $tipoDocumento, $nroDocumento, $email, $moneda)
    {
        $cuenta->nombre = $nombre;
        $cuenta->apellido = $apellido;
        $cuenta->tipoDocumento = $tipoDocumento;
        $cuenta->nroDocumento = $nroDocumento;
        $cuenta->email = $email;
        $cuenta->moneda = $moneda;

        return true;
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
