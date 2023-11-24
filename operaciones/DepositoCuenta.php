<?php

/*
a- DepositoCuenta.php: (por POST) se recibe el Tipo de Cuenta, Nro de Cuenta y
Moneda y el importe a depositar, si la cuenta existe en banco.json, se incrementa el
saldo existente según el importe depositado y se registra en el archivo depósitos.json
la operación con los datos de la cuenta y el depósito (fecha, monto) e id
autoincremental). Si la cuenta no existe, informar el error.
b- Completar el depósito con imagen del talón de depósito con el nombre: Tipo de
Cuenta, Nro. de Cuenta e Id de Depósito, guardando la imagen en la carpeta
/ImagenesDeDepositos2023.
*/
require_once 'cuenta.php';
require_once 'deposito.php';

if(isset($_POST["tipoCuenta"]) && isset($_POST["nroCuenta"]) && isset($_POST["moneda"]) && isset($_POST["importe"]) && isset($_FILES["archivo"])){
    $tipoCuenta = $_POST["tipoCuenta"];
    $nroCuenta = $_POST["nroCuenta"];
    
    
    $cuentas = cargarCuentasDesdeJSON("banco.json");
    
    $cuenta = Cuenta::buscarCuentaPorNro($cuentas, $nroCuenta);
    
    if($cuenta !== null){
        $archivo = $_FILES['archivo'];
        $nombreImagen = $archivo['name'];
        $tipo = $archivo['type'];

        echo'<br>Ya encontramos la cuenta, aguarde que estamos realizando la operacion...';

        $cuentasActualizado = Cuenta::actualizarSaldo($cuentas, $cuenta->getNombre(), $tipoCuenta, $_POST["importe"]);
        if(actualizarJSONBanco($cuentasActualizado)){
            echo'<br>Saldo actualizado correctamente...';
            $fechaActual = date("Y-m-d");
            $depositos = cargarDepositosDesdeJSON('depositos.json');
            $id = generarIdAutoIncremental($depositos);
            $deposito = new Deposito($id, $tipoCuenta, $nroCuenta, $_POST["moneda"], $_POST["importe"], $fechaActual);
            if(escribirEnJsonDepositos($deposito, $depositos)){
                $nombreImagen = generarNombreImagen($tipoCuenta, $cuenta->getId(), $id);
                move_uploaded_file($archivo['tmp_name'], 'ImagenesDeDepositos2023/' . $nombreImagen);
                echo'<br> El deposito se realizo con exito.';
            }else{
                echo'<br>Error al guardar el deposito...';
            }
        }else{
            echo'<br>Error al acutalizar el banco.json...';
        }
    }else{
        echo'<br>La cuenta no existe...';
    }
}else{
    echo'<br>Parametros incorrectos';
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

function actualizarJSONBanco($cuentasActualizadas){

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

function escribirEnJsonDepositos($deposito, $depositos){

    $nombreArchivo = "depositos.json";
    $json_old = file_get_contents($nombreArchivo);
    $depositos = json_decode($json_old);

    $depositos [] = $deposito;
    $json_new = json_encode($depositos);
     
    if($archivo = fopen($nombreArchivo, 'w')){
        fwrite($archivo, $json_new);
        fclose($archivo);
        return true;
    }else{
        echo "<br>$nombreArchivo no se pudo abrir correctamente";
        return false;
    }
}

function generarIDAutoincremental($depositos) {

    $id = 100;
    foreach ($depositos as $deposito) {
        if ($deposito->getId() > $id) {
            $id = $deposito->getId();    //busca el ultimo id
        }
    }

    return $id + 1; //devuelve el que le sigue del ultimo
}

function generarNombreImagen($tipoCuenta, $numeroCuentaID, $idDeposito) {
    $nombreArchivo = $numeroCuentaID . $tipoCuenta . $idDeposito .".png"; 
    return $nombreArchivo;
}
?>