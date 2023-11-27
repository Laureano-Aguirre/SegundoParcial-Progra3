<?php

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
include_once '../controllers/depositoController.php';
include_once '../controllers/retiroController.php';

class ValidationMiddlewareGET{
    public function __invoke(Request $request, RequestHandler $handler): Response{
        $parametros = $request->getQueryParams();
        $action = $parametros['action'];
        $response = new Response();
        
        switch ($action){
            case 'a':
                if((Validaciones::validarTipoCuenta($parametros['tipoCuenta'])) && (Validaciones::validarMoneda($parametros['moneda'])) && (isset($parametros['fecha']))){
                    //el total depositado por tipo de cuenta y moneda con fecha especifica con fecha exacta
                    $depositoController = new depositoController();
                    $result = $depositoController->buscarDepTipoCuentaMonedaFecha($parametros['tipoCuenta'], $parametros['moneda'], $parametros['fecha']);
                    //var_dump($result);
                    if(!empty($result)){
                        $response = $handler->handle($request);
                        $response->getBody()->write(json_encode($result));
                    }else{
                        $response = new Response();
                        $result = ['message' => 'Error al consultar el movimiento, por favor, corroborar si el tipo de cuenta, moneda y fecha es correcto. De ser asi, no hay depositos con esas especificaciones.'];
                        $response->getBody()->write(json_encode($result));
                    }
                    
                }elseif((Validaciones::validarTipoCuenta($parametros['tipoCuenta'])) && (Validaciones::validarMoneda($parametros['moneda']))){
                    //el total depositado por tipo de cuenta y moneda sin fecha
                    $fechaAnterior = date('Y-m-d', strtotime('-1 day'));
                    $depositoController = new depositoController();
                    $result = $depositoController->buscarDepTipoCuentaMonedaFecha($parametros['tipoCuenta'], $parametros['moneda'], $fechaAnterior);
                    if(!empty($result)){
                        $response = $handler->handle($request);
                        $response->getBody()->write(json_encode($result));
                    }else{
                        $response = new Response();
                        $result = ['message' => 'Error al consultar el movimiento, por favor, corroborar si el tipo de cuenta, moneda. De ser asi, no hay depositos con esas especificaciones.'];
                        $response->getBody()->write(json_encode($result));
                    }
                }else{
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
            break;
            case 'b':
                if(Validaciones::validarInt($parametros['nroCuenta'])){
                    //listado de depósitos para un usuario en particular.
                    $depositoController = new depositoController();
                    $result = $depositoController->buscarDepNroCuenta($parametros['nroCuenta']);
                    if(!empty($result)){
                        $response = $handler->handle($request);
                        $response->getBody()->write(json_encode($result));
                    }else{
                        $response = new Response();
                        $result = ['message' => 'Error al consultar el movimiento, por favor, corroborar numero de cuenta es correcto. De ser asi, no hay depositos con esas especificaciones.'];
                        $response->getBody()->write(json_encode($result));
                    }
                }else{
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
            break;
            case 'c':
                if(isset($parametros['fechaUno']) && isset($parametros['fechaDos'])){
                    //listado de depósitos entre dos fechas ordenado por nombre.
                    $depositoController = new depositoController();
                    $result = $depositoController->buscarDepEntreFechas($parametros['fechaUno'], $parametros['fechaDos']);
                    if(!empty($result)){
                        $response = $handler->handle($request);
                        $response->getBody()->write(json_encode($result));
                    }else{
                        $response = new Response();
                        $result = ['message' => 'Error al consultar el movimiento, por favor, corroborar si las fechas son correctas. De ser asi, no hay depositos con esas especificaciones.'];
                        $response->getBody()->write(json_encode($result));
                    }
                }else{
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
            break;
            case 'd':
                if(Validaciones::validarTipoCuenta($parametros['tipoCuenta'])){
                    //listado de depósitos por tipo de cuenta.
                    $depositoController = new depositoController();
                    $result = $depositoController->buscarDepTipoCuenta($parametros['tipoCuenta']);
                    if(!empty($result)){
                        $response = $handler->handle($request);
                        $response->getBody()->write(json_encode($result));
                    }else{
                        $response = new Response();
                        $result = ['message' => 'Error al consultar el movimiento, por favor, corroborar si el tipo de cuenta es correcto. De ser asi, no hay depositos con esas especificaciones.'];
                        $response->getBody()->write(json_encode($result));
                    }
                }else{
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
            break;
            case 'e':
                if(Validaciones::validarMoneda($parametros['moneda'])){
                    //listado de depósitos por moneda
                    $depositoController = new depositoController();
                    $result = $depositoController->buscarDepMoneda($parametros['moneda']);
                    if(!empty($result)){
                        $response = $handler->handle($request);
                        $response->getBody()->write(json_encode($result));
                    }else{
                        $response = new Response();
                        $result = ['message' => 'Error al consultar el movimiento, por favor, corroborar si la moneda es correcta. De ser asi, no hay depositos con esas especificaciones.'];
                        $response->getBody()->write(json_encode($result));
                    }
                }else{
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'f':
                if(Validaciones::validarInt($parametros['nroCuenta'])){
                    //muestra todos los depositos y retiros
                    $depositoController = new depositoController();
                    $retiroController = new retiroController();
                    $result1 = $depositoController->buscarDepNroCuenta($parametros['nroCuenta']);
                    $result2 = $retiroController->buscarRetiroNroCuenta($parametros['nroCuenta']);

                    if(!empty($result1) && !empty($result2)){
                        $combinedResult = ['depositos' => $result1, 'retiros' => $result2];
                        $response = $handler->handle($request);
                        $response->getBody()->write(json_encode($combinedResult));
                    }else{
                        $response = new Response();
                        $result = ['message' => 'Error al consultar el movimiento, por favor, corroborar si el numero de cuenta es correcto. De ser asi, no hay depositos o retiros con esas especificaciones.'];
                        $response->getBody()->write(json_encode($result));
                    }
                }else{
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



?>