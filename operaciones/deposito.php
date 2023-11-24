<?php

class Deposito implements JsonSerializable{
    private $id;
    private $tipoCuenta;
    private $nroCuenta;
    private $moneda;
    private $importe;
    private $fecha;


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function __construct($id, $tipoCuenta, $nroCuenta, $moneda, $importe, $fecha)
    {
        $this->id = $id;
        $this->tipoCuenta = $tipoCuenta;
        $this->nroCuenta = $nroCuenta;
        $this->moneda = $moneda;
        $this->importe = $importe;
        $this->fecha = $fecha;
    }

    public function getId() {
        return $this->id;
    }

    public function getTipoCuenta() {
        return $this->tipoCuenta;
    }

    public function getNroCuenta() {
        return $this->nroCuenta;
    }

    public function getMoneda() {
        return $this->moneda;
    }

    public function getImporte() {
        return $this->importe;
    }

    public function getFecha() {
        return $this->fecha;
    }
    
    public static function existeDeposito($depositos, $tipoCuenta, $moneda, $fecha = null){
        if($depositos){
            if($fecha !== null){
                foreach($depositos as $deposito){
                    if($deposito->tipoCuenta == $tipoCuenta && $deposito->moneda == $moneda && $deposito->fecha == $fecha){
                        return $deposito;
                    }
                }
            }else{
                foreach($depositos as $deposito){
                    if($deposito->tipoCuenta == $tipoCuenta && $deposito->moneda == $moneda){
                        return $deposito;
                    }
                }
            }
            
        }else{
            echo'<br>Array de depositos vacio...';
        }
        return null;
    }

    public static function buscarMontosFecha($depositos, $fecha){
        $monto = 0;
        if($depositos){
            foreach($depositos as $deposito){
                if($deposito->fecha == $fecha){
                    $monto += $deposito->monto;
                }
            }
            return $monto;
        }else{
            echo'<br>Array vacio en buscar montos fecha';
        }
        return -1;
    }

    public static function buscarDepositosNroCuenta($depositos, $nroCuenta){
        $depositosEncontrados = array();
        if($depositos){
            foreach($depositos as $deposito){
                if($deposito->nroCuenta == $nroCuenta){
                    $depositosEncontrados[] = $deposito;
                }
            }
        }else{
            echo'<br>Array de depositos vacio en buscar depositos por numero de documento...';
        }
        return $depositosEncontrados;
    }

    public static function buscarDepositosEntreFechas($depositos, $fechaUno, $fechaDos){
        $depositosEncontrados = array();
        if($depositos){
            foreach($depositos as $deposito){
                if($deposito->fecha >= $fechaUno && $deposito->fecha <=$fechaDos){
                    $depositosEncontrados[] = $deposito;
                }
            }
        }else{
            echo'<br>Array vacio en mostrar depositos';
        }
        return $depositosEncontrados;
    }

    public static function buscarDepositosTipoCuenta($depositos, $tipoCuenta){
        $depositosEncontrados = array();
        if($depositos){
            foreach($depositos as $deposito){
                if($deposito->tipoCuenta == $tipoCuenta){
                    $depositosEncontrados[] = $deposito;
                }
            }
        }else{
            echo'<br>Array de depositos vacio en buscar depositos por tipo de cuenta...';
        }
        return $depositosEncontrados;
    }

    public static function buscarDepositosMoneda($depositos, $moneda){
        $depositosEncontrados = array();
        if($depositos){
            foreach($depositos as $deposito){
                if($deposito->moneda == $moneda){
                    $depositosEncontrados[] = $deposito;
                }
            }
        }else{
            echo'<br>Array de depositos vacio en buscar depositos por moneda...';
        }
        return $depositosEncontrados;
    }

    public static function mostrarDepositosConFecha($depositos, $fecha){
        if($depositos){
            echo'<br>Depositos: ';
            foreach($depositos as $deposito){
                if($deposito->fecha == $fecha){
                    echo"<br>ID: $deposito->id";
                    echo"<br>Tipo de cuenta: $deposito->tipoCuenta";
                    echo"<br>Numero de cuenta: $deposito->nroCuenta";
                    echo"<br>Moneda: $deposito->moneda";
                    echo"<br>Importe: $deposito->importe";
                    echo"<br>Fecha: $deposito->fecha<br>";
                }                
            }
            return true;
        }else{
            echo'<br>Array vacio en mostrar depositos';
        }
        return false;
    }

    public static function mostrarDepositos($depositos)
    {
        if ($depositos) {
            echo '<br>Depositos: ';
            foreach ($depositos as $deposito) {
                echo "<br>ID: $deposito->id";
                echo "<br>Tipo de cuenta: $deposito->tipoCuenta";
                echo "<br>Numero de cuenta: $deposito->nroCuenta";
                echo "<br>Moneda: $deposito->moneda";
                echo "<br>Importe: $deposito->importe";
                echo "<br>Fecha: $deposito->fecha<br>";
            }
            return true;
        } else {
            echo '<br>Array vacio en mostrar depositos';
        }
        return false;
    }

    public static function buscarDepositoId($id, $depositos){
        if ($depositos){
            foreach($depositos as $deposito){
                if($deposito->id == $id){
                    return $deposito;
                }
            }
        }
        return null;
    }

    public static function modificarDeposito($deposito, $montoActualizado){
        $deposito->importe = $montoActualizado;
        return $deposito;
    }

    public static function retornarNroCuentaIdDeposito($deposito){
        return $deposito->nroCuenta;
    }

    public static function retornarMonto($deposito){
        return $deposito->importe;
    }
    /*public static function mostrarDepositos($depositos, $fecha = null){
        if($depositos){
            echo '<br>Depósitos: ';
            foreach($depositos as $deposito){
                if ($fecha === null || $deposito->fecha == $fecha) {
                    echo "<br>ID: $deposito->id";
                    echo "<br>Tipo de cuenta: $deposito->tipoCuenta";
                    echo "<br>Numero de cuenta: $deposito->nroCuenta";
                    echo "<br>Moneda: $deposito->moneda";
                    echo "<br>Importe: $deposito->importe";
                    echo "<br>Fecha: $deposito->fecha";
                }
            }
            return true;
        } else {
            echo '<br>Array vacío en mostrar depósitos';
        }
        return false;
    }*/
    
}

?>