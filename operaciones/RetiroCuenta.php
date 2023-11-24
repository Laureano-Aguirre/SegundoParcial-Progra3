<?php

/*
(por POST) se recibe el Tipo de Cuenta, Nro de Cuenta y Moneda
y el importe a retirar, si la cuenta existe en banco.json, se decrementa el saldo
existente según el importe extraído y se registra en el archivo retiro.json la operación
con los datos de la cuenta y el depósito (fecha, monto) e id autoincremental.
Si la cuenta no existe o el saldo es inferior al monto a retirar, informar el tipo de error.
*/

require_once 'cuenta.php';
require_once 'retiro.php';

if(isset($_POST["tipoCuenta"]) && isset($_POST["nroCuenta"]) && isset($_POST["moneda"]) && isset($_POST["importe"])){
    $cuentas = cargarCuentasDesdeJSON('banco.json');

    if($cuentas){
        $cuenta = Cuenta::buscarCuentaPorNro($cuentas, $_POST["nroCuenta"]);

        if($cuenta !== null)
        {
            echo'<br>Encontramos su cuenta, aguarde un momento mientras gestionamos el retiro de su dinero...';
            if(($cuentaActualizada = Cuenta::retiro($cuenta, $_POST["importe"])) !== null){
                $cuentasActualizadas = Cuenta::actualizarCuentas($cuentas, $cuentaActualizada, $_POST["nroCuenta"]);
                if(actualizarCuentasJSON($cuentasActualizadas)){
                    $retiros = cargarRetirosDesdeJSON('retiro.json');
                    $idRetiro = generarIDAutoIncremental($retiros);
                    $fechaActual = date("Y-m-d");                  
                    $retiro = new Retiro($idRetiro, $_POST["tipoCuenta"], $_POST["nroCuenta"], $_POST["moneda"], $_POST["importe"], $fechaActual);

                    if(escribirEnJsonRetiros($retiro, $retiros)){
                        echo'<br> El retiro fue realizado con exito...';
                    }else{
                        echo'<br>Error al escribir los retiros en JSON...';
                    }
                }else{
                    echo'<br>Error al intentar actualizar las cuentas...';
                }
            }else{
                echo'<br>No puede retirar dicho importe ya que no lo posee en la cuenta...';
            }
        }else{
            echo'<br>No existe esa cuenta en el banco...';
        }
    }else{
        echo'<br>No hay cuentas en el banco...';
    }
}else{
    echo'<br>Parametros incorrectos...';
}

function cargarCuentasDesdeJSON($archivo){
    $contenidoJSON = file_get_contents($archivo);
    $datos = json_decode($contenidoJSON, true);

    $cuentas = [];
    foreach ($datos as $dato) {
        $cuentas[] = new Cuenta(
            $dato['id'],
            $dato['nombre'],
            $dato['apellido'],
            $dato['tipoDocumento'],
            $dato['nroDocumento'],
            $dato['email'],
            $dato['tipoCuenta'],
            $dato['moneda'],
            $dato['saldoInicial'],
            $dato['estado']
        );
    }

    return $cuentas;
}

function actualizarCuentasJSON($cuentasActualizadas){

    $json = json_encode($cuentasActualizadas);

    $nombreArchivo = "banco.json";

    if($archivo = fopen($nombreArchivo, 'w')){
        fwrite($archivo, $json);
        fclose($archivo);
        return true;
    }else{
        echo "<br>$nombreArchivo no se pudo abrir correctamente";
        return false;
    }

}

function cargarRetirosDesdeJSON($archivo){
    $contenidoJSON = file_get_contents($archivo);
    $datos = json_decode($contenidoJSON, true);

    $retiros = [];
    foreach ($datos as $dato) {
        $retiros[] = new Retiro(
            $dato['id'],
            $dato['tipoCuenta'],
            $dato['nroCuenta'],
            $dato['moneda'],
            $dato['importe'],
            $dato['fecha']
        );
    }

    return $retiros;
}

function escribirEnJsonRetiros($retiro, $retiros){

    $nombreArchivo = "retiro.json";

    $retiros [] = $retiro;
    $json_new = json_encode($retiros);

    if($archivo = fopen($nombreArchivo, 'w')){
        fwrite($archivo, $json_new);
        fclose($archivo);

        return true;
    }else{
        echo "<br>$nombreArchivo no se pudo abrir correctamente";
        return false;
    }
}

function generarIDAutoincremental($retiros) {

    $id = 10;
    foreach ($retiros as $retiro) {
        if ($retiro->getId() > $id) {
            $id = $retiro->getId();    //busca el ultimo id
        }
    }

    return $id + 1; //devuelve el que le sigue del ultimo
}

?>