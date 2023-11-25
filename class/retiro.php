<?php
include_once '../db/AccesoDatos.php';

class RetiroBanco {
    public $id;
    public $tipoCuenta;
    public $nroCuenta;
    public $moneda;
    public $importe;
    public $fecha;


    /* public function __construct($id, $tipocuenta, $nroCuenta, $moneda, $importe, $fecha)
    {
        $this->id = $id;
        $this->tipoCuenta = $tipocuenta;
        $this->nroCuenta = $nroCuenta;
        $this->moneda = $moneda;
        $this->importe = $importe;
        $this->fecha = $fecha;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
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
    } */

    public function agregarRetiro(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("INSERT into retiro (id_retiro,tipo_cuenta,id_cuenta,moneda,importe,fecha_retiro) values(:idDeposito, :tipoCuenta, :idCuenta, :moneda, :importe, :fechaRetiro)");
        $consulta->bindValue(':idDeposito', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':idCuenta', $this->nroCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':moneda', $this->moneda, PDO::PARAM_STR);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
        $consulta->bindValue(':fechaRetiro', $this->fecha, PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->retornarUltimoId();
    }

    public static function listarRetiros(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->retornarConsulta("SELECT id_retiro as idRetiro, tipo_cuenta as tipoCuenta, id_cuenta as nroCuenta, moneda, importe, fecha_retiro as fechaRetiro FROM cuenta");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "RetiroBanco");
    }

    public static function buscarRetiroPorId($retiros, $idRetiro){
        if($retiros){
            foreach($retiros as $retiro){
                if($retiro->id == $idRetiro){
                    return $retiro;
                }
            }
        }else{
            echo'<br>Array de retiros vacios en buscar retiros por id...';
        }
        return null;
    }

    public static function retornarMonto($retiro){
        return $retiro->importe;
    }

    public static function retonarNroCuenta($retiro){
        return $retiro->nroCuenta;
    }

    public static function modificarRetiro($retiro, $importeActualizado){
        $retiro->importe = $importeActualizado;
        return $retiro;
    }

    public static function buscarRetirosNroCuenta($retiros, $nroCuenta){
        $retirosEncontrados = array();
        if($retiros){
            foreach($retiros as $retiro){
                if($retiro->nroCuenta == $nroCuenta){
                    $retirosEncontrados[] = $retiro;
                }
            }
        }else{
            echo'<br>Array de retiros vacio en buscar retiros por numero de cuenta...';
        }
        return $retirosEncontrados;
    }

    public static function mostrarRetiros($retiros)
    {
        if ($retiros) {
            echo '<br>Depositos: ';
            foreach ($retiros as $retiro) {
                echo "<br>ID: $retiro->id";
                echo "<br>Tipo de cuenta: $retiro->tipoCuenta";
                echo "<br>Numero de cuenta: $retiro->nroCuenta";
                echo "<br>Moneda: $retiro->moneda";
                echo "<br>Importe: $retiro->importe";
                echo "<br>Fecha: $retiro->fecha<br>";
            }
            return true;
        } else {
            echo '<br>Array vacio en mostrar retiros';
        }
        return false;
    }
}


?>