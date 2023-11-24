<?php

/*
Debe recibir todos los datos propios de una cuenta (a excepción del saldo); si dicha
cuenta existe (comparar por Tipo y Nro. de Cuenta) se modifica, de lo contrario
informar que no existe esa cuenta.
*/

require_once 'cuenta.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT'){
    parse_str(file_get_contents('php://input'), $_PUT);         //tomo los datos sin procesar de la solicitud http y lo convierto en la variable $_PUT      

    $cuentas = cargarCuentasDesdeJSON('banco.json');
    
    if($cuentas !== null){
        if (isset($_PUT["id"]) && isset($_PUT["nombre"]) && isset($_PUT["apellido"]) && isset($_PUT["tipoDocumento"]) && isset($_PUT["nroDocumento"]) && isset($_PUT["email"]) && isset($_PUT["tipoCuenta"]) && isset($_PUT["moneda"])){
            
            $cuenta = Cuenta::buscarCuentaPorNro($cuentas, $_PUT["id"], $_PUT["tipoCuenta"]);
            if($cuenta !== null){
                echo'<br>La cuenta que intenta modificar existe, estamos modificandola, aguarde...';
                $retorno = Cuenta::modificarCuenta($cuenta, $_PUT["nombre"], $_PUT["apellido"], $_PUT["tipoDocumento"], $_PUT["nroDocumento"], $_PUT["email"], $_PUT["moneda"]);
                if($retorno == true){
                    $cuentasActualizada = Cuenta::actualizarCuentas($cuentas, $cuenta, $_PUT["id"]);
                    if($cuentasActualizada){
                        if(actualizarJSON($cuentasActualizada)){
                            echo'<br>Cuenta modificada con exito...';
                        }else{
                            echo'<br>Error al actualizar el JSON...';
                        }
                    }else{
                        echo'<br>Error al actualizar la cuenta...';
                    }
                }
            }else{
                echo'<br>La cuenta que intenta modificar no existe...';
            }
        }else{
            echo'<br>Parametros incorrectos';
        }
    }else{
        echo'<br>No hay cuentas en el banco...';
    }
}else{
    echo'<br>Error en el REQUEST METHOD';
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