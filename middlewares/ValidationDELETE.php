<?php


use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

require_once '../validations/validaciones.php';

class ValidationMiddlewareDELETE{
    public function __invoke(Request $request, RequestHandler $handler): Response{
        $parametros = $request->getParsedBody();
        $action = $parametros['action'];

        switch ($action){
            case 'BorrarCuenta':
                if(Validaciones::validarInt($parametros['idCuenta'])){
                    $response = $handler->handle($request);
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