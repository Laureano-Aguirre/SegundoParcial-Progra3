<?php

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

include_once '../controllers/empleadoController.php';
include_once '../controllers/productoController.php';
include_once '../controllers/pedidoController.php';
include_once '../controllers/mesaController.php';

class LoggerMiddlewareGET{
    public function __invoke(Request $request, RequestHandler $handler): Response{
        $parametros = $request->getQueryParams();
        $action = $parametros['action'];
        $response = new Response();
        
        switch ($action){
            case 'ListarEmpleados':
                $empleadoController = new empleadoController();
                $result = $empleadoController->listarEmpleados();
                $response->getBody()->write(json_encode($result));
                
            break;
            case 'ListarProductos':
                $productosController = new productoController();
                $result = $productosController->listarProductos();
                $response->getBody()->write(json_encode($result));
                //$response = $handler->handle($request);
            break;
            case 'ListarMesas':
                $mesasController = new mesaController();
                $result = $mesasController->listarMesas();
                $response->getBody()->write(json_encode($result));
                //$response = $handler->handle($request);
            break;
            case 'ListarPedidos':
                $pedidosController = new pedidoController();
                $result = $pedidosController->listarPedidos();
                $response->getBody()->write(json_encode($result));
                //$response = $handler->handle($request);
            break;
            case 'DescargarCSV':
                $empleadoController = new empleadoController();
                $result = $empleadoController->listarEmpleados();
                if (($handle = fopen("../files/LeerDesdeArchivo.csv", "w")) !== FALSE) {
                    foreach ($result as $empleado) {
                        $row = array($empleado->nombre, $empleado->apellido, $empleado->rol);
                        fputcsv($handle, $row);
                    }
                    $response = $handler->handle($request);
                    fclose($handle);
                }
                break;
            default:
                $response = new Response();
                $result = ['message' => 'Accion desconocida: ' . $action];
                $response->getBody()->write(json_encode($result));
                break;
                $response = $handler->handle($request);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }

}



?>