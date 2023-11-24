<?php

/*
CuentaAlta.php: (por POST) se ingresa Nombre y Apellido, Tipo Documento, Nro.
Documento, Email, Tipo de Cuenta (CA – caja de ahorro o CC – cuenta corriente),
Moneda ($ o U$S), Saldo Inicial (0 por defecto).
Se guardan los datos en el archivo banco.json, tomando un id autoincremental de 6
dígitos como Nro. de Cuenta (emulado). Sí el nombre y tipo ya existen , se actualiza el
precio y se suma al stock existente.
completar el alta con imagen/foto del usuario/cliente, guardando la imagen con Nro y
Tipo de Cuenta (ej.: NNNNNNTT) como identificación en la carpeta:
/ImagenesDeCuentas/2023.
*/



include "cuenta.php";
include_once '../validations/validaciones.php';

if (isset($_POST["nombre"]) && isset($_POST["apellido"]) && isset($_POST["tipoDocumento"]) && isset($_POST["nroDocumento"]) && isset($_POST["email"]) && isset($_POST["tipoCuenta"]) && isset($_POST["moneda"]) && isset($_FILES["archivo"])){

    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : null;
    $apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : null;
    $tipoDocumento = isset($_POST["tipoDocumento"]) ? $_POST["tipoDocumento"] : null;
    $nroDocumento = isset($_POST["nroDocumento"]) ? $_POST["nroDocumento"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $tipoCuenta = isset($_POST["tipoCuenta"]) ? $_POST["tipoCuenta"] : null;
    $moneda = isset($_POST["moneda"]) ? $_POST["moneda"] : null;
    $archivo = isset($_FILES["archivo"]) ? $_FILES["archivo"] : null;
    
    $cuentas = cargarCuentasDesdeJSON("banco.json");
    $saldoInicial = isset($_POST["saldoInicial"]) && !empty($_POST["saldoInicial"]) ? $_POST["saldoInicial"] : 0 ;

    if($nombre && $apellido && $tipoDocumento && $nroDocumento && $email && $tipoCuenta && $moneda && $archivo){
        if(Cuenta::existeCuenta($cuentas, $nombre, $tipoCuenta) !== 1){     //modificar existe cuenta para que busque por dni y tipo cuenta
            if(!Cuenta::validarTipoCuenta($cuentas, $tipoCuenta, $_POST["nroDocumento"])){
                
                if($saldoInicial >= 0){
                $id = generarIDAutoincremental($cuentas);
                $cuenta = new Cuenta($id, $nombre, $_POST["apellido"], $_POST["tipoDocumento"], $_POST["nroDocumento"], $_POST["email"], $tipoCuenta, $_POST["moneda"], $saldoInicial);
                $archivo = $_FILES['archivo'];
                $nombreImagen = $archivo['name'];
                $tipo = $archivo['type'];
                if(escribirEnJSON($cuenta, $cuentas)){
                    echo'<br> La cuenta ha sido creada correctamente.';
                    $nombreImagen = generarNombreImagen($tipoCuenta, $id);
                    move_uploaded_file($archivo['tmp_name'], 'Imagenes/2023/' . $nombreImagen);
                }else{
                echo'<br>Error al crear la cuenta...';
                }
            }else{
                echo'<br>Error el saldo debe ser mayor a 0...';
            }
            }else{
                echo'<br>Ya posee una cuenta de ese tipo, por favor, ingrese otro tipo de cuenta...';
            }    
        }else{
            echo'<br>La cuenta ya existe, actualizaremos el saldo...';
            $cuentasActualizada = Cuenta::actualizarSaldo($cuentas, $nombre, $tipoCuenta, $saldoInicial);
            if(actualizarJSON($cuentasActualizada)){
                echo'<br>Exito al actualizar la cuenta...';
            }else{
                echo'<br>No se pudo actualizar la cuenta...';
            }
        }
    }else{
        echo'<br>Error, debe completar todos los campos...';
    } 
}
else{
    echo'<br>Parametros incorrectos';
}

function cargarCuentasDesdeJSON($archivo){
    $contenidoJSON = file_get_contents($archivo);
    $datos = json_decode($contenidoJSON, true);

    $cuentas = [];
    if($datos){
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
    }
    

    return $cuentas;
}

function escribirEnJson($cuenta, $cuentas){

    $nombreArchivo = "banco.json";
    $json_old = file_get_contents($nombreArchivo);
    $cuentas = json_decode($json_old);

    $cuentas [] = $cuenta;
    $json_new = json_encode($cuentas);
     
    if($archivo = fopen($nombreArchivo, 'w')){
        fwrite($archivo, $json_new);
        fclose($archivo);
        return true;
    }else{
        echo "<br>$nombreArchivo no se pudo abrir correctamente";
        return false;
    }
}

function actualizarJSON($cuentasActualizadas){

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

function generarIDAutoincremental($cuentas){

    $id = 100000;
    foreach ($cuentas as $cuenta) {
        if ($cuenta->getId() > $id) {
            $id = $cuenta->getId();    //busca el ultimo id
        }
    }

    return $id + 1; //devuelve el que le sigue del ultimo
}

function generarNombreImagen($tipoCuenta, $numeroCuentaID) {
    $nombreArchivo = $numeroCuentaID . $tipoCuenta . ".png"; 
    return $nombreArchivo;
}



?>