<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

include_once '../middlewares/ValidationPOST.php';
include_once '../middlewares/ValidationDELETE.php';
include_once '../middlewares/ValidationPUT.php';
include_once '../middlewares/ValidationGET.php';

// Instantiate App
$app = AppFactory::create();

// Set base path
$app->setBasePath('/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

include_once '../controllers/ajusteController.php';
include_once '../controllers/cuentaController.php';
include_once '../controllers/depositoController.php';
include_once '../controllers/retiroController.php';


// Routes
$app->get('[/]', function (Request $request, Response $response) {
    $result = ['message' => 'Consultando movimientos...'];
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
})->add(new ValidationMiddlewareGET());

$app->post('[/]', function (Request $request, Response $response) {
    $action = $_POST['action'];
    switch ($action){
        case 'CuentaAlta':
            $result = ['message' => 'Exito al crear la cuenta!'];
            $response->getBody()->write(json_encode($result));
            break;
        case 'ConsultarCuenta':
            $result = ['message' => 'Cuenta consultada!'];
            $response->getBody()->write(json_encode($result));
            break;
        case 'DepositoCuenta':
            $result = ['message' => 'Exito al realizar el deposito en la cuenta!'];
            $response->getBody()->write(json_encode($result));
            break;
        case 'RetiroCuenta':
            $result = ['message' => 'Exito al retirar de la cuenta!'];
            $response->getBody()->write(json_encode($result));
            break;
        case 'AjusteCuenta':
            $result = ['message' => 'Exito al ajustar la cuenta!'];
            $response->getBody()->write(json_encode($result));
            break;
        default:
            $result = ['message' => 'Error, no se reconoce la accion ingresada...'];
            $response->getBody()->write(json_encode($result));
            break;
    }
    return $response->withHeader('Content-Type', 'application/json');
})->add(new ValidationMiddlewarePOST());


$app->put('[/]', function (Request $request, Response $response) {
    $result = ['message' => 'Exito al modificar la cuenta!'];
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
})->add(new ValidationMiddlewarePUT());

$app->delete('[/]', function (Request $request, Response $response) {    
    $result = ['message' => 'Exito al borrar la cuenta!'];
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
})->add(new ValidationMiddlewareDELETE());

/* $app->group('/auth', function (RouteCollectorProxy $group) {
    $group->post('[/login]', function (Request $request, Response $response) {
        $parametros = $request->getParsedBody();
        $datos = array('usuario' => $parametros['usuario'], 'rol' => 'socio');
        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token));
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    })->add(new LoginMiddleware());
}); */

$app->run();
