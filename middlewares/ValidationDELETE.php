<?php


use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

require_once '../validations/validaciones.php';
include_once '../controllers/cuentaController.php';

class ValidationMiddlewareDELETE{
    public function __invoke(Request $request, RequestHandler $handler): Response{
        $parametros = $request->getParsedBody();
        $action = $parametros['action'];

        switch ($action){
            case 'BorrarCuenta':
                if((Validaciones::validarInt($parametros['idCuenta'])) && (Validaciones::validarTipoCuenta($parametros['tipoCuenta']))){
                    $cuentaController = new cuentaController();
                    if(($cuentaController->buscarCuentaPorNroYTipo($parametros['idCuenta'], $parametros['tipoCuenta'])) == -1){
                        $response = new Response();
                        $result = ['message' => 'No existe ninguna cuenta con ese ID.'];
                        $response->getBody()->write(json_encode($result));
                    }else{
                        $cuentaController->borrarCuenta($parametros['idCuenta'], $parametros['tipoCuenta']);
                        $nombreArchivo = $parametros['idCuenta'] . $parametros['tipoCuenta'] . ".png";  
                        $rutaOriginal = '../Imagenes/2023/' . $nombreArchivo;
                        $rutaBackup = '../ImagenesBackupCuentas/2023/' . $nombreArchivo;
                        if(rename($rutaOriginal, $rutaBackup)){
                            $response = $handler->handle($request);
                        }else{
                            $response = new Response();
                            $result = ['message' => 'Error, no se pudo mover la imagen.'];
                            $response->getBody()->write(json_encode($result));
                        }
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