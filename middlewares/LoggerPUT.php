<?php

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

require_once '../validations/validaciones.php';
include_once '../controllers/empleadoController.php';
include_once '../controllers/productoController.php';
include_once '../controllers/pedidoController.php';
include_once '../controllers/mesaController.php';

class LoggerMiddlewarePUT{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $action = $parametros['action'];

        //var_dump($parametros);
        switch ($action) {
            case 'ModificarUsuario':
                
                if ((Validaciones::validarInt($parametros['idEmpleado'])) && (Validaciones::validarStrings($parametros['nombre'])) && (Validaciones::validarStrings($parametros['apellido'])) && (Validaciones::validarStrings($parametros['rol']))) {
                    $empleadoController = new empleadoController();
                    $result = $empleadoController->modificarEmpleado($parametros['idEmpleado'], $parametros['nombre'], $parametros['apellido'], $parametros['rol']);
                    $response = $handler->handle($request);
                }else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
            break;
            case 'ModificarPedido':
                if(Validaciones::validarStrings(($parametros['productos'])) && Validaciones::validarStrings(($parametros['estado'])) && Validaciones::validarInt(($parametros['tiempoFinalizacion']))){
                    $pedidoController = new pedidoController();
                    $result = $pedidoController->modificarPedido($parametros['idPedido'], $parametros['productos'], $parametros['estado'], $parametros['tiempoFinalizacion']);
                    $response = $handler->handle($request);
                }else{
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'ModificarProducto':
                if(Validaciones::validarInt($parametros['cantidad'])){
                    $productoController = new productoController();
                    $result = $productoController->modificarProducto($parametros['idProducto'], $parametros['cantidad']);
                    $response = $handler->handle($request);
                }else{
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'ModificarMesa':
                if((Validaciones::validarInt($parametros['idMesa'])) && (Validaciones::validarStrings($parametros['estado']))){
                    $mesaController = new mesaController();
                    $result = $mesaController->modificarMesa($parametros['idMesa'], $parametros['estado']);
                    $response = $handler->handle($request);
                }else{
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