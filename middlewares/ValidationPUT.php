<?php

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

require_once '../validations/validaciones.php';
include_once '../controllers/cuentaController.php';

class ValidationMiddlewarePUT{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $action = $parametros['action'];

        //var_dump($parametros);
        switch ($action) {
            case 'ModificarCuenta':
                if ((Validaciones::validarInt($parametros['idCuenta'])) && (Validaciones::validarNombre($parametros['nombre'])) && (Validaciones::validarNombre($parametros['apellido'])) && (Validaciones::validarNombre($parametros['tipoDocumento'])) && (Validaciones::validarDni($parametros['nroDocumento'])) && (Validaciones::validarCorreo($parametros['email'])) && (Validaciones::validarTipoCuenta($parametros['tipoCuenta'])) && (Validaciones::validarMoneda($parametros['moneda']))) {
                    $cuentaController = new cuentaController();
                    if(($cuentaController->buscarCuentaPorNroYTipo($parametros['idCuenta'], $parametros['tipoCuenta'])) == -1){
                        $response = new Response();
                        $result = ['message' => 'No existe ninguna cuenta con ese ID.'];
                        $response->getBody()->write(json_encode($result));
                    }else{
                        $cuentaController->modificarCuenta($parametros['idCuenta'], $parametros['nombre'], $parametros['apellido'], $parametros['tipoDocumento'], $parametros['nroDocumento'], $parametros['email'], $parametros['tipoCuenta'], $parametros['moneda']);
                        $response = $handler->handle($request);
                    }
                }else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            default:
                
                $response = new Response();
                $result = ['message' => 'Accion desconocidad: ' . $action];
                $response->getBody()->write(json_encode($result));
                break;
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>