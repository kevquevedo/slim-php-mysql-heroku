<?php

require_once './models/Usuario.php';
require_once './interfaces/IMethods.php';

class UsuarioController extends Usuario implements IMethods
{
    //CARGA UN USUARIO NUEVO EN LA BASE DE DATOS.
    public function Insert($request, $response, $args){
        try{
            $parametros = $request->getParsedBody();
            $nombre = $parametros['nombre']; $apellido = $parametros['apellido']; $tipoUsuario = $parametros['tipoUsuario']; 
            $email = $parametros['email']; $contrasena = $parametros['contraseña'];
            
            $nuevoUsuario = new Usuario(); $nuevoUsuario->setNombre($nombre); $nuevoUsuario->setApellido($apellido); $nuevoUsuario->setTipo($tipoUsuario); 
            $nuevoUsuario->setEmail($email); $nuevoUsuario->setContrasena($contrasena); $nuevoUsuario->setSector($tipoUsuario);

            $payload = json_encode(array("Mensaje" => "Error. No se pudo agregar el usuario."));
            if($nuevoUsuario->Crear() != null){
                $payload = json_encode(array("Mensaje" => "Se agrego el usuario con exito."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //MODIFICA UN USUARIO DE LA BASE DE DATOS.
    public function Update($request, $response, $args){
        try{          
            $parametros = $request->getParsedBody();
            $id = $parametros['id']; $nombre = $parametros['nombre']; $apellido = $parametros['apellido']; $tipo = $parametros['tipo'];
            $email = $parametros['email']; $contrasena = $parametros['contrasena'];
            $payload = json_encode(array("Mensaje" => "No se pudo modificar el usuario."));
            if(Usuario::Modificar($id, $nombre, $apellido, $tipo, $email, $contrasena) > 0){
                $payload = json_encode(array("Mensaje" => "Usuario modificado con exito."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //ELIMINA UN USUARIO DE LA BASE DE DATOS.
    public function Delete($request, $response, $args){
        try{
            $parametros = $request->getParsedBody();
            $id = $parametros['id'];
            $payload = json_encode(array("Mensaje" => "No se pudo borrar el usuario."));
            if(Usuario::Borrar($id) > 0){
                $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //TRAE TODOS LOS USUARIOS DE LA BASE DE DATOS.
    public function ReadAll($request, $response, $args){
        try{
            $array = Usuario::ObtenerTodos();
            $payload = json_encode(array("Mensaje" => "No se pudo recuperar la lista de usuarios."));
            if($array){
                $payload = json_encode(array("Lista De Usuarios" => $array));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //TRAE UN USUARIO POR EL ID DEL MISMO.
    public function ReadOne($request, $response, $args){
        try{
            $id = $args['id'];
            $object = Usuario::ObtenerPorId($id);
            $payload = json_encode(array("Mensaje" => "No se encontró el usuario."));
            if($object){
                $payload = json_encode($object);
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

}