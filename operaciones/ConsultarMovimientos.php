<?php

/*
Datos a consultar:
a- El total depositado (monto) por tipo de cuenta y moneda en un día en particular (se envía por parámetro), si no se pasa fecha, se muestran las del día anterior.
b- El listado de depósitos para un usuario en particular.
c- El listado de depósitos entre dos fechas ordenado por nombre.
d- El listado de depósitos por tipo de cuenta.
e- El listado de depósitos por moneda.
*/
/*
2DA PARTE:
ConsultaMovimientos.php: (por GET)
A la consulta de movimientos ya existente, incorporar:
f- El listado de todas las operaciones (depósitos y retiros) por usuario
*/

require_once 'cuenta.php';
require_once 'deposito.php';
require_once 'retiro.php';

$accion = isset($_GET['ACTION']) ? $_GET['ACTION'] : '';

switch($accion){
    case 'a':
        if(isset($_GET["tipoCuenta"]) && isset($_GET["moneda"]) && isset($_GET["fecha"])){
            $depositos = cargarDepositosDesdeJSON('depositos.json');
            $deposito = Deposito::existeDeposito($depositos, $_GET["tipoCuenta"], $_GET["moneda"], $_GET["fecha"]);

            if($deposito !== null){
                echo'<br>Mostrando el monto total de dicho dia...';
                $monto = Deposito::buscarMontosFecha($depositos, $_GET["fecha"]);
                echo"<br>El monto total de todos los depositos de la fehca {$_GET["fecha"]} es de $monto";
            }else{
                echo'<br>Error, no existe ningun deposito';
            }
        }elseif(isset($_GET["tipoCuenta"]) && isset($_GET["moneda"])){
            $fechaAnterior = date('Y-m-d', strtotime('-1 day'));
            $depositos = cargarDepositosDesdeJSON('depositos.json');
            $deposito = Deposito::existeDeposito($depositos, $_GET["tipoCuenta"], $_GET["moneda"]);
            if($deposito !== null){
                echo'<br>Mostrando depositos de dicho dia...';
                if(Deposito::mostrarDepositosConFecha($depositos, $fechaAnterior)){
                    echo'<br>Exito al mostrar los depositos...';
                }else{
                    echo'<br>Error al intentar mostrar los depositos...';
                }
            }else{
                echo'<br>Error, no existe ningun deposito';
            }
        }else{
            echo'<br>Parametros incorrectos en la opcion A...';
        }
        break;
    case 'b':
        if(isset($_GET["nroCuenta"])){
            echo'<br>En un momento buscaremos sus depositos, aguarde...';
            $depositos = cargarDepositosDesdeJSON('depositos.json');
            if(Deposito::buscarDepositosNroCuenta($depositos, $_GET["nroCuenta"])){
                if(Deposito::mostrarDepositos($depositos)){
                    echo'<br>Exito al mostrar los depositos por numero de cuenta...';
                }else{
                    echo'<br>Error al mostrar los depositos por numero de cuenta...';
                }
            }else{
                echo'<br>Error al buscar los depositos por numero de cuenta...';
            }
        }else{
            echo'<br>Parametros incorrectos...';
        }
        break;
    case 'c':
        if(isset($_GET["fechaUno"]) && isset($_GET["fechaDos"])){
            echo'<br>En un momento buscaremos los depositos entre ambas fechas, aguarde...';
            $depositos = cargarDepositosDesdeJSON('depositos.json');

            $depositosEncontrados = Deposito::buscarDepositosEntreFechas($depositos, $_GET["fechaUno"], $_GET["fechaDos"]);
            if($depositosEncontrados !== null){
                if(Deposito::mostrarDepositos($depositosEncontrados)){
                    echo'<br>Exito al mostrar los depositos entre dos fechas...';
                }else{
                    echo'<br>Error al mostrar los depositos entre dos fechas...';
                }
            }else{
                echo'<br>Error al buscar los depositos entre dos fechas...';
            }
        }else{
            echo'<br>Parametros incorrectos...';
        }
        break;
    case 'd':
        if(isset($_GET["tipoCuenta"])){
            echo'<br>En un momento buscaremos sus depositos, aguarde...';
            $depositos = cargarDepositosDesdeJSON('depositos.json');

            $depositosEncontrados = Deposito::buscarDepositosTipoCuenta($depositos, $_GET["tipoCuenta"]);
            if($depositosEncontrados !== null){
                if(Deposito::mostrarDepositos($depositosEncontrados)){
                    echo'<br>Exito al mostrar los depositos por tipo de cuenta...';
                }else{
                    echo'<br>Error al mostrar los depositos por tipo de cuenta...';
                }
            }else{
                echo'<br>Error al buscar los depositos por tipo de cuenta...';
            }
        }else{
            echo'<br>Parametros incorrectos...';
        }
        break;
    case 'e':
        if(isset($_GET["moneda"])){
            echo'<br>En un momento buscaremos sus depositos, aguarde...';
            $depositos = cargarDepositosDesdeJSON('depositos.json');

            $depositosEncontrados = Deposito::buscarDepositosMoneda($depositos, $_GET["moneda"]);
            if($depositosEncontrados !== null){
                if(Deposito::mostrarDepositos($depositosEncontrados)){
                    echo'<br>Exito al mostrar los depositos por moneda...';
                }else{
                    echo'<br>Error al mostrar los depositos por moneda...';
                }
            }else{
                echo'<br>Error al buscar los depositos por moneda...';
            }
        }else{
            echo'<br>Parametros incorrectos...';
        }
        break;
    case 'f':
        if(isset($_GET["nroCuenta"])){
            echo'<br>En un momento buscaremos sus operaciones, aguarde...';
            $depositos = cargarDepositosDesdeJSON('depositos.json');
            $retiros = cargarRetirosDesdeJSON('retiro.json');
            $depositosEncontrados= Deposito::buscarDepositosNroCuenta($depositos, $_GET["nroCuenta"]);
            if($depositosEncontrados){
                if(Deposito::mostrarDepositos($depositosEncontrados)){
                    echo'<br>Exito al mostrar los depositos por numero de cuenta...';
                    $retirosEncontrados = Retiro::buscarRetirosNroCuenta($retiros, $_GET["nroCuenta"]);
                    if($retirosEncontrados){
                        if(Retiro::mostrarRetiros($retirosEncontrados)){
                            echo'<br>Exito al mostrar los retiros por numero de cuenta...';
                        }else{
                            echo'<br>Error al mostrar los retiros por numero de cuenta...';
                        }
                    }else{
                        echo'<br>Error al buscar los retiros por numero de cuenta...';
                    }
                }else{
                    echo'<br>Error al mostrar los depositos por numero de cuenta...';
                }
            }else{
                echo'<br>Error al buscar los depositos por numero de cuenta...';
            }
        }else{
            echo'<br>Parametros incorrectos...';
        }
        break;
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

?>