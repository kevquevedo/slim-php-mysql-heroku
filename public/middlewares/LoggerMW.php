<?php

require_once 'AutentificadorJWT.php';

use GuzzleHttp\Psr7\Request;
use Slim\Handlers\Strategies\RequestHandler;
use Slim\Psr7\Response;

class LoggerMW{

    //VERIFICA CREDENCIALES
    public static function VerificarCredenciales($request, $handler){
        $parametros = $request->getParsedBody();
        $email = $parametros['email'];
        $contrasena = $parametros['contrasena'];
        $usuario = Usuario::ValidarUsuario($email, $contrasena);

        if ($usuario != null) {
            $ingreso = array('nombre' => $usuario->getNombre(), 'apellido' => $usuario->getApellido(), 'mail' => $usuario->getEmail(), 
                             'contrasena' => $usuario->getContrasena(), 'tipo' => $usuario->getTipo(), 'sector' => $usuario->getSector());
            $token = AutentificadorJWT::CrearToken($ingreso);
            $payload = json_encode(array("jwt" => $token, "Ingreso" => " OK. Bienvenido/a " . $usuario->getNombre() . " " . $usuario->getApellido()));
        } else {
            $payload = json_encode(array('error' => 'Usuario y/o contrasena incorrectos.'));        
        }
        $response = new Response();
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    //VERIFICA SI EL USUARIO ES SOCIO
    public function EsSocio($request, $handler){
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode('Bearer', $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "socio") {                
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "Solo los socios tienen acceso")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    //VERIFICA SI EL USUARIO ES MOZO
    public function EsMozo($request, $handler){
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode('Bearer', $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "mozo") {                
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "El usuario no tiene permisos.")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    //VERIFICA SI EL USUARIO ES CERVECERO
    public function EsCervecero($request, $handler){
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode('Bearer', $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "cervecero") {                
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "El usuario no tiene permisos.")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    //VERIFICA SI EL USUARIO ES PASTELERO
    public function EsPastelero($request, $handler){
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode('Bearer', $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "pastelero") {                
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "El usuario no tiene permisos.")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    //VERIFICA SI EL USUARIO ES BARTENDER
    public function EsBartender($request, $handler){
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode('Bearer', $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "bartender") {                
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "El usuario no tiene permisos.")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    //VERIFICA SI EL USUARIO ES COCINERO
    public function EsCocinero($request, $handler){
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode('Bearer', $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "cocinero") {                
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "El usuario no tiene permisos.")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

}




?>