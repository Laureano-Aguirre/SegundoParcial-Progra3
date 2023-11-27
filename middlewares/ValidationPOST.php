<?php

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

require_once '../validations/validaciones.php';
include_once '../controllers/cuentaController.php';
include_once '../controllers/depositoController.php';
include_once '../controllers/ajusteController.php';


class ValidationMiddlewarePOST{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $action = $parametros['action'];
        $response = new Response();
        //var_dump($parametros);

        switch ($action) {
            case 'CuentaAlta':
                if ((Validaciones::validarNombre($parametros['nombre'])) && (Validaciones::validarNombre($parametros['apellido'])) && (Validaciones::validarNombre($parametros['tipoDocumento'])) && (Validaciones::validarDni($parametros['nroDocumento'])) && (Validaciones::validarCorreo($parametros['email'])) && (Validaciones::validarTipoCuenta($parametros['tipoCuenta'] . $parametros['moneda'])) && (Validaciones::validarMoneda($parametros['moneda'])) && (isset($_FILES['archivo']))) {
                    $saldoInicial = isset($parametros["saldoInicial"]) && !empty($parametros["saldoInicial"]) ? $parametros["saldoInicial"] : 0 ;
                    $cuentaController = new cuentaController();
                    if(($cuentaController->buscarCuentaPorDniYTipo($parametros['nroDocumento'], $parametros['tipoCuenta'])) == 1){
                        $response = new Response();
                        $result = ['message' => 'Ya existe una cuenta con ese DNI y tipo de cuenta, por favor, intente con otros datos.'];
                        $response->getBody()->write(json_encode($result));
                    }else{
                        $ultimoId = $cuentaController->agregarCuenta($parametros['nombre'], $parametros['apellido'], $parametros['tipoDocumento'], $parametros['nroDocumento'], $parametros['email'], $parametros['tipoCuenta'] . $parametros['moneda'], $parametros['moneda'], $saldoInicial);
                        $archivo = $_FILES['archivo'];
                        $nombreImagen = $archivo['name'];
                        $tipo = $archivo['type'];
                        $nombreImagen = $cuentaController->generarNombreImagen($parametros['tipoCuenta'], $ultimoId);
                        move_uploaded_file($archivo['tmp_name'], '../Imagenes/2023/' . $nombreImagen);
                        $response = $handler->handle($request);
                    }
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'ConsultarCuenta':
                if ((Validaciones::validarNombre($parametros['nombre'])) && (Validaciones::validarTipoCuenta($parametros['tipoCuenta']))) {
                    $cuentaController = new cuentaController();
                    $result = $cuentaController->buscarCuentaPorNombreYTipo($parametros['nombre'], $parametros['tipoCuenta']);
                    if(empty($result)){
                        $response = new Response();
                        $result = ['message' => 'No existe una cuenta con ese nombre y tipo de cuenta.'];
                        $response->getBody()->write(json_encode($result));
                    }else{
                        $response = $handler->handle($request);
                        $response->getBody()->write(json_encode($result));                       
                    } 
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'DepositoCuenta':
                if ((Validaciones::validarTipoCuenta($parametros['tipoCuenta'] . $parametros['moneda'])) && (Validaciones::validarInt($parametros['nroCuenta'])) && (Validaciones::validarMoneda($parametros['moneda'])) && (Validaciones::validarSaldoInicial($parametros['importe'])) && (isset($_FILES['archivo']))) {
                    $cuentaController = new cuentaController();
                    $result = $cuentaController->buscarCuentaPorNroYTipo($parametros['nroCuenta'], $parametros['tipoCuenta'] . $parametros['moneda']);
                    if($result == 1){
                        $cuentaController->actualizarSaldo($parametros['nroCuenta'], $parametros['tipoCuenta'] . $parametros['moneda'], $parametros['importe']);
                        $depositoController = new depositoController();
                        $fechaActual = date("Y-m-d");
                        $archivo = $_FILES['archivo'];
                        $nombreImagen = $archivo['name'];
                        $tipo = $archivo['type'];      
                        $ultimoId = $depositoController->agregarDeposito($parametros['tipoCuenta'] . $parametros['moneda'], $parametros['nroCuenta'], $parametros['moneda'], $parametros['importe'], $fechaActual);
                        $nombreImagen = $depositoController->generarNombreImagen($parametros['tipoCuenta'], $parametros['nroCuenta'], $ultimoId);
                        move_uploaded_file($archivo['tmp_name'], '../ImagenesDeDepositos2023/' . $nombreImagen);
                        $response = $handler->handle($request);
                    }else{
                        $response = new Response();
                        $result = ['message' => 'No existe la cuenta que desea realizar el deposito.'];
                        $response->getBody()->write(json_encode($result));
                    }
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'RetiroCuenta':
                if ((Validaciones::validarTipoCuenta($parametros['tipoCuenta'] . $parametros['moneda'])) && (Validaciones::validarInt($parametros['nroCuenta'])) && (Validaciones::validarMoneda($parametros['moneda'])) && (Validaciones::validarInt($parametros['importe']))) {
                    $cuentaController = new cuentaController();
                    if(($cuentaController->retirar($parametros['nroCuenta'], $parametros['tipoCuenta'] . $parametros['moneda'], $parametros['importe'])) == -1){
                        $response = new Response();
                        $result = ['message' => 'ERROR, no puede retirar esa cantidad ya que no posee un saldo suficienteee.'];
                        $response->getBody()->write(json_encode($result));
                    }else{
                        $retiroController = new retiroController();
                        $fechaActual = date("Y-m-d");
                        $result = $retiroController->agregarRetiro($parametros['tipoCuenta'] . $parametros['moneda'], $parametros['nroCuenta'], $parametros['moneda'], $parametros['importe'], $fechaActual);
                        $response = $handler->handle($request);
                    }
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'AjusteCuenta':
                if ((Validaciones::validarInt($parametros['idMovimiento'])) && (Validaciones::validarStrings($parametros['movimiento'])) && (Validaciones::validarStrings($parametros['motivo']))) {
                    if($parametros['movimiento'] == 'retiro'){
                        $retiroController = new retiroController();
                        $result = $retiroController->buscarRetiro($parametros['idMovimiento']);
                        if(empty($result)){
                            $response = new Response();
                            $result = ['message' => 'No existe un retiro con ese ID.'];
                            $response->getBody()->write(json_encode($result));
                        }else{
                            $idCuenta = $result['nroCuenta'];
                            $tipoCuenta = $result['tipoCuenta'];
                            $importe = $result['importe'];
                            $retiroController->modificarEstado($parametros['idMovimiento'], true);
                            $cuentaController = new cuentaController();
                            $cuentaController->actualizarSaldo($idCuenta, $tipoCuenta, $importe);
                            $ajusteController = new ajusteController();
                            $ajusteController->agregarAjuste($parametros['idMovimiento'], $parametros['movimiento'], $parametros['motivo']);
                            $response = $handler->handle($request);
                        }
                    }elseif($parametros['movimiento'] == 'deposito'){
                        $depositoController = new depositoController();
                        $result = $depositoController->buscarDeposito($parametros['idMovimiento']);
                        if(empty($result)){
                            $response = new Response();
                            $result = ['message' => 'No existe un retiro con ese ID.'];
                            $response->getBody()->write(json_encode($result));
                        }else{
                            $idCuenta = $result['nroCuenta'];
                            $tipoCuenta = $result['tipoCuenta'];
                            $importe = $result['importe'];
                            echo $tipoCuenta = $result['tipoCuenta'];
                            $depositoController->modificarEstado($parametros['idMovimiento'], true);
                            $cuentaController = new cuentaController();
                            $cuentaController->retirar($idCuenta, $tipoCuenta, $importe);
                            $ajusteController = new ajusteController();
                            $ajusteController->agregarAjuste($parametros['idMovimiento'], $parametros['movimiento'], $parametros['motivo']);
                            $response = $handler->handle($request);
                        }  
                    }else{
                        $response = new Response();
                        $result = ['message' => 'El tipo de movimiento no es valido.'];
                        $response->getBody()->write(json_encode($result));
                    }
                    
                }else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            default:
                $response = new Response();
                $result = ['message' => 'Accion desconocida: ' . $action];
                $response->getBody()->write(json_encode($result));
                break;

        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}
