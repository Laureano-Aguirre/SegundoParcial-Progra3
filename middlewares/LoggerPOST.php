<?php

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

require_once '../validations/validaciones.php';
include_once '../controllers/empleadoController.php';
include_once '../controllers/productoController.php';
include_once '../controllers/pedidoController.php';
include_once '../controllers/mesaController.php';

class LoggerMiddlewarePOST{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $action = $parametros['action'];

        switch ($action) {
            case 'AltaEmpleado':
                if ((Validaciones::validarStrings($parametros['nombre'])) && (Validaciones::validarStrings($parametros['apellido'])) && (Validaciones::validarStrings($parametros['rol']))) {
                    $fechaAlta = date('Y-m-d');
                    $empleadoController = new empleadoController();
                    $result = $empleadoController->agregarEmpleado($parametros['usuario'], $parametros['password'], $parametros['nombre'], $parametros['apellido'], $parametros['rol'], $fechaAlta);
                    $response = $handler->handle($request);
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'AltaProductos':
                if ((Validaciones::validarStrings($parametros['tipo'])) && (Validaciones::validarStrings($parametros['descripcion'])) && (Validaciones::validarInt($parametros['cantidad']))) {
                    $productosController = new productoController();
                    $productosController->agregarProducto($parametros['tipo'], $parametros['descripcion'], $parametros['cantidad']);
                    $response = $handler->handle($request);
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'AltaMesas':
                if ((Validaciones::validarInt($parametros['idCliente'])) && (Validaciones::validarInt($parametros['idMozo'])) && (Validaciones::validarInt($parametros['idEncuesta'])) && (Validaciones::validarStrings($parametros['estado']))) {
                    $mesasController = new mesaController();
                    $mesasController->agregarMesa($parametros['idCliente'], $parametros['idPedido'], $parametros['idMozo'], $parametros['idEncuesta'], $parametros['estado']);
                    $response = $handler->handle($request);
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'AltaPedidos':
                if ((Validaciones::validarStrings($parametros['nombreCliente'])) && (Validaciones::validarInt($parametros['idEmpleado'])) && (Validaciones::validarStrings($parametros['productos'])) && (Validaciones::validarStrings($parametros['estado'])) && (Validaciones::validarInt($parametros['tiempoFinalizacion']))) {
                    $pedidosController = new pedidoController();
                    $pedidosController->agregarPedido($parametros['nombreCliente'], $parametros['idEmpleado'], $parametros['productos'], $parametros['estado'], $parametros['tiempoFinalizacion']);
                    $response = $handler->handle($request);
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'AltaEmpleadoCSV':
                $empleadoController = new empleadoController();
                $fechaAlta = date('Y-m-d');
                if (($handle = fopen("../files/CargarDesdeArchivo.csv", "r")) !== FALSE) {
                    if (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $result = $empleadoController->agregarEmpleado($data[0], $data[1], $data[2], $data[3], $data[4],$fechaAlta);
                        $response = $handler->handle($request);
                    }
                    fclose($handle);
                }else{
                    $response = new Response();
                    $result = ['message' => 'Error al leer el archivo.'];
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
