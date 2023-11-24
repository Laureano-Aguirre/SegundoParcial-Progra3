<?php

use Firebase\JWT\JWT;
use Slim\Psr7\Message;

class AutentificadorJWT
{
    private static $claveSecreta = 'T3sT$JWT';
    private static $tipoEncriptacion = ['HS256'];

    public static function CrearToken($datos)
    {
        $ahora = time();
        $payload = array(
            'iat' => $ahora,    //tiempo en el que se genero
            'exp' => $ahora + (60000),      //tiempo en el que va a vencer
            'aud' => self::Aud(),           //quien puede usar el token
            'data' => $datos,       //lo que viene desde el postman
            'app' => "Test JWT"
        );
        return JWT::encode($payload, self::$claveSecreta);  //genera el token
    }

    public static function VerificarToken($token)
    {
        if (empty($token)) {
            throw new Exception("El token esta vacio.");
        }
        try {
            $decodificado = JWT::decode(
                $token,
                self::$claveSecreta,
                self::$tipoEncriptacion     //valida que el token este bien formateado, si fue generado con la clave y el tipo de encriptacion aceptado, si el tiempo de expiracion no se cumplio
            );
        } catch (Exception $e) {
            throw $e;
        }
        if ($decodificado->aud !== self::Aud()) {           //el usuario que creo el jwt, no es el mismo que lo esta usando
            throw new Exception("No es el usuario valido");     
        }
    }


    public static function ObtenerPayLoad($token)
    {
        if (empty($token)) {
            throw new Exception("El token esta vacio.");
        }
        return JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
        );
    }

    public static function ObtenerData($token)
    {
        return JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
        )->data;
    }

    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {       //agarre ip del cliente, de la manera que sea
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];   //le concatena que navegador esta usando
        $aud .= gethostname();                  //obtiene la ruta que esta usando

        return sha1($aud);                      //lo hashea. sirve para que solamente pueda ser usado por quien lo genero
    }
}


?>