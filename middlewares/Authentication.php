<?php
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

include_once '../controllers/AuthenticatorController.php';

class AuthenticationMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response{
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        
        try {
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        }catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }   

    public static function verificarToken(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');     //agarramos el token en el header
        $token = trim(explode("Bearer", $header)[1]);       //nos adaptamos a que venga en una determinada forma, sacamos el bearer

        try {
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);     //aca es un token valido
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}



?>