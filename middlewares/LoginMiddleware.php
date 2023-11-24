<?php
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

include_once '../controllers/AuthenticatorController.php';

class LoginMiddleware{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();

        $authenticatorController = new authenticatorController();
        if ($parametros['usuario'] !== null && $parametros['password'] !== null) {
            $retorno = $authenticatorController->autenticarUsuario($parametros['usuario'], $parametros['password']);
            if ($retorno !== false) {
                
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('error' => "Usuario o contrasenia incorrecto."));
                $response->getBody()->write(json_encode($payload));
            }
        }else{
            $response = new Response();
            $payload = json_encode(array('error' => "Parametros incorrectos."));
            $response->getBody()->write(json_encode($payload));
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>