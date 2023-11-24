<?php

/*
debe recibir un número el tipo y número de cuenta
y debe realizar la baja de la cuenta (soft-delete, no físicamente) y la foto relacionada a
esa venta debe moverse a la carpeta /ImagenesBackupCuentas/2023.
*/

require_once 'cuenta.php';

parse_str(file_get_contents("php://input"), $request);

if(isset($request['estado']) && isset($request['tipoCuenta']) && isset($request['nroCuenta'])){
    $tipoCuenta = $request['tipoCuenta'];
    $numeroCuenta = $request['nroCuenta'];
    $estado = $request['estado'];

    $cuentas = cargarCuentasDesdeJSON('banco.json');

    if($cuentas){
        $cuenta = Cuenta::buscarCuentaPorNro($cuentas, $numeroCuenta, $tipoCuenta);

        if($cuenta !== null){
            echo'<br>Encontramos la cuenta, procedemos a borrarla...';
            if($estado == -1){   //-1 inactiva, 1 activa
                $retorno = $cuenta->cambiarEstado($estado);
                if($retorno){
                    echo'<br>Exito al cambiar de estado la cuenta...';
                    $cuentasActualizadas = Cuenta::actualizarCuentas($cuentas, $cuenta, $cuenta->getId());
                    if(actualizarJSON($cuentasActualizadas))  {
                        echo'<br>Exito al actualizar el json...';
                    }   
                    $nombreArchivo = $cuenta->getId() . $tipoCuenta . ".png"; 
                    $rutaOriginal = 'Imagenes/2023/' . $nombreArchivo;
                    $rutaBackup = 'ImagenesBackupCuentas/2023/' . $nombreArchivo;
                    if(rename($rutaOriginal, $rutaBackup)){
                        echo'<br>Exito al mover la imagen...';
                               
                    }else{
                        echo'<br>Error al mover la imagen, intente mas tarde...';
                    }
                }else{
                    echo'<br>Error al cambiar su estado, intente mas tarde...';
                }
            }elseif($estado == 1 ) {
                $retorno = $cuenta->cambiarEstado($estado);
                if($retorno){
                    echo'<br>Exito al reestablecer la cuenta...';
                    $cuentasActualizadas = Cuenta::actualizarCuentas($cuentas, $cuenta, $cuenta->getId());
                    if(actualizarJSON($cuentasActualizadas))  {
                        echo'<br>Exito al actualizar el json...';
                    }   
                    $nombreArchivo = $cuenta->getId() . $tipoCuenta . ".png"; 
                    $rutaBackup = 'Imagenes/2023/' . $nombreArchivo;
                    $rutaOriginal = 'ImagenesBackupCuentas/2023/' . $nombreArchivo;
                    if(rename($rutaOriginal, $rutaBackup)){
                        echo'<br>Exito al mover la imagen...';
                               
                    }else{
                        echo'<br>Error al mover la imagen, intente mas tarde...';
                    }
                }else{
                    echo'<br>Error al cambiar su estado, intente mas tarde...';
                }               
            }          
        }else{
            echo'<br>La cuenta no existe...';
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

?>