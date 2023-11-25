<?php

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

require_once '../validations/validaciones.php';


class ValidationMiddlewarePOST{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $action = $parametros['action'];

        switch ($action) {
            case 'CuentaAlta':
                if ((Validaciones::validarNombre($parametros['nombre'])) && (Validaciones::validarNombre($parametros['apellido'])) && (Validaciones::validarNombre($parametros['tipoDocumento'])) && (Validaciones::validarDni($parametros['nroDocumento'])) && (Validaciones::validarCorreo($parametros['email'])) && (Validaciones::validarTipoCuenta($parametros['tipoCuenta'])) && (Validaciones::validarMoneda($parametros['moneda'])) && (isset($_FILES['archivo']))) {
                    $response = $handler->handle($request);
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'ConsultarCuenta':
                if ((Validaciones::validarNombre($parametros['nombre'])) && (Validaciones::validarTipoCuenta($parametros['tipoCuenta']))) {
                    $response = $handler->handle($request);
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'DepositoCuenta':
                if ((Validaciones::validarTipoCuenta($parametros['tipoCuenta'])) && (Validaciones::validarInt($parametros['nroCuenta'])) && (Validaciones::validarMoneda($parametros['moneda'])) && (Validaciones::validarInt($parametros['importe'])) && (isset($_FILES['archivo']))) {
                    $response = $handler->handle($request);
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'RetiroCuenta':
                if ((Validaciones::validarTipoCuenta($parametros['tipoCuenta'])) && (Validaciones::validarInt($parametros['nroCuenta'])) && (Validaciones::validarMoneda($parametros['moneda'])) && (Validaciones::validarInt($parametros['importe']))) {
                    $response = $handler->handle($request);
                } else {
                    $response = new Response();
                    $result = ['message' => 'Algun parametro no fue introducido en un formato correcto.'];
                    $response->getBody()->write(json_encode($result));
                }
                break;
            case 'AjusteCuenta':
                if ((Validaciones::validarInt($parametros['idRetiro'])) && (Validaciones::validarStrings($parametros['motivo'])) && (Validaciones::validarInt($parametros['monto']))) {
                    $response = $handler->handle($request);
                }else {
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
