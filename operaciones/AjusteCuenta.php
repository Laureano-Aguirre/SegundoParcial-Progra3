<?php

/*
Se ingresa el número de extracción o depósito afectado al ajuste y el motivo del
mismo. El número de extracción o depósito debe existir.
Guardar en el archivo ajustes.json
Actualiza en el saldo en el archivo banco.jsons
*/

include_once "cuenta.php";
include_once "retiro.php";
include_once "deposito.php";
include_once "ajuste.php";

if (isset($_POST["idRetiro"]) && isset($_POST["motivo"]) && isset($_POST["monto"])) {
    $idRetiro = $_POST["idRetiro"];
    $motivo = $_POST["motivo"];
    $monto = $_POST["monto"];
    
    $retiros = cargarRetirosDesdeJSON('retiro.json');
    
    if($retiros){
        $retiro = Retiro::buscarRetiroPorId($retiros, $idRetiro);
        if($retiro !== null){
            echo '<br>Encontramos el retiro, reemplazando el monto, aguarde un momento...';
            $montoRetiro = Retiro::retornarMonto($retiro);
            $nroCuenta = Retiro::retonarNroCuenta($retiro);

            $retiroActualizado = Retiro::modificarRetiro($retiro, $monto);

            $cuentas = cargarCuentasDesdeJSON('banco.json');
            $cuenta = Cuenta::buscarCuentaPorNro($cuentas, $nroCuenta);

            if ($cuenta !== null){
                $cuentaActualizada = Cuenta::ajustarSaldoRetiro($cuenta, $montoRetiro, $monto);
                $cuentasActualizadas = Cuenta::actualizarCuentas($cuentas, $cuentaActualizada, $nroCuenta);
                if (actualizarCuentasJSON($cuentasActualizadas)){
                    $ajustes = cargarAjustesDesdeJSON('ajustes.json');
                    $idAjuste = generarIDAutoIncremental($ajustes);
                    $ajuste = new Ajuste($idAjuste, $idRetiro, $motivo, $monto);                  
                    if (escribirAjustesEnJson($ajuste, $ajustes)) {
                        echo '<br>Ajuste hecho exitosamente...';
                    } else {
                        echo '<br>Error al escribir en ajustes.json...';
                    }
                }else {
                echo '<br>Error al querer actualizar las cuentas en el JSON...';
                }
            }else{
                echo '<br>entreee...';
                echo '<br>No existe dicha cuenta...';
            }
        }else{
            echo'<br>No existe dicho retiro...';
        }
    }else{
        echo'<br>No hay retiros para ajustar...';
    }

} elseif (isset($_POST["idDeposito"]) && isset($_POST["motivo"])) {
    $idDeposito = $_POST["idDeposito"];
    $motivo = $_POST["motivo"];
    $monto = $_POST["monto"];

    $depositos = cargarDepositosDesdeJSON('depositos.json');
    $deposito = Deposito::buscarDepositoId($idDeposito, $depositos);

    if ($deposito !== null) {
        echo '<br>Encontramos el deposito, reemplazando el monto, aguarde un momento...';
        $montoDeposito = Deposito::retornarMonto($deposito);
        $nroCuenta = Deposito::retornarNroCuentaIdDeposito($deposito);//arreglar

        $depositoActualizado = Deposito::modificarDeposito($deposito, $monto); //revisar porque no lo vuelvo a utilizar

        $cuentas = cargarCuentasDesdeJSON('banco.json');
        $cuenta = Cuenta::buscarCuentaPorNro($cuentas, $nroCuenta);

        if ($cuenta !== null) {
            $cuentaActualizada = Cuenta::ajustarSaldoDeposito($cuenta, $montoDeposito, $monto);
            $cuentasActualizadas = Cuenta::actualizarCuentas($cuentas, $cuentaActualizada, $nroCuenta);
            if (actualizarCuentasJSON($cuentasActualizadas)) {
                $ajustes = cargarAjustesDesdeJSON('ajustes.json');
                $idAjuste = generarIDAutoIncremental($ajustes);
                $ajuste = new Ajuste($idAjuste, $idDeposito, $motivo, $monto);               
                if (escribirAjustesEnJson($ajuste, $ajustes)) {
                    echo '<br>Ajuste hecho exitosamente...';
                } else {
                    echo '<br>Error al escribir en ajustes.json...';
                }
            } else {
                echo '<br>Error al querer actualizar las cuentas en el JSON...';
            }
        } else {
            echo '<br>No existe dicha cuenta...';
        }
        
    }else{
        echo'<br>No existe dicho deposito...';
    }
} else {
    echo '<br>Parametros incorrectos...';
}

function cargarDepositosDesdeJSON($archivo){
    $contenidoJSON = file_get_contents($archivo);
    $datos = json_decode($contenidoJSON, true);

    $depositos = [];
    foreach ($datos as $dato) {
        $depositos[] = new Deposito(
            $dato['id'],
            $dato['tipoCuenta'],
            $dato['nroCuenta'],
            $dato['moneda'],
            $dato['importe'],
            $dato['fecha']
        );
    }

    return $depositos;
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

function cargarAjustesDesdeJSON($archivo){
    $contenidoJSON = file_get_contents($archivo);
    $datos = json_decode($contenidoJSON, true);

    $ajustes = [];
    foreach ($datos as $dato) {
        $ajustes[] = new Ajuste(
            $dato['id'],
            $dato['idMovimiento'],
            $dato['motivo'],
            $dato['importe']
        );
    }

    return $ajustes;
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

function escribirAjustesEnJson($ajuste, $ajustes){

    $nombreArchivo = "ajustes.json";
    $json_old = file_get_contents($nombreArchivo);
    $ajustes = json_decode($json_old);

    $ajustes [] = $ajuste;
    $json_new = json_encode($ajustes);
     
    if($archivo = fopen($nombreArchivo, 'w')){
        fwrite($archivo, $json_new);
        fclose($archivo);
        return true;
    }else{
        echo "<br>$nombreArchivo no se pudo abrir correctamente";
        return false;
    }
}

function generarIDAutoincremental($ajustes) {

    $id = 1000;
    foreach ($ajustes as $ajuste) {
        if ($ajuste->getId() > $id) {
            $id = $ajuste->getId();    //busca el ultimo id
        }
    }

    return $id + 1; //devuelve el que le sigue del ultimo
}
?>