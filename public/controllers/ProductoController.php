<?php

require_once './models/Producto.php';
require_once './interfaces/IMethods.php';

class ProductoController extends Producto implements IMethods {

    //CARGA UN PRODUCTO NUEVO EN LA BASE DE DATOS.
    public function Insert($request, $response, $args){
        try{          
            
            $parametros = $request->getParsedBody();
            $newProducto = new Producto();
            $newProducto->setNombre($parametros['nombre']); $newProducto->setTipo($parametros['tipo']); $newProducto->setSector($parametros['sector']);
            $newProducto->setValor($parametros['valor']); $newProducto->setDemora($parametros['demora']);

            $payload = json_encode(array("Mensaje" => "Error. No se pudo agregar el producto."));
            if($newProducto->Crear() != null){
                $payload = json_encode(array("Mensaje" => "Se agrego el producto con exito."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //MODIFICA UN PRODUCTO DE LA BASE DE DATOS.
    public function Update($request, $response, $args){
        try{          
            $parametros = $request->getParsedBody();
            $id = $parametros['id']; $nombre = $parametros['nombre']; $tipo = $parametros['tipo'];
            $sector = $parametros['sector']; $valor = $parametros['valor'];
            $payload = json_encode(array("Mensaje" => "No se pudo modificar el producto."));
            if(Producto::Modificar($id, $nombre, $tipo, $sector, $valor) > 0){
                $payload = json_encode(array("Mensaje" => "Producto modificado con exito."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //ELIMINA UN PRODUCTO DE LA BASE DE DATOS.
    public function Delete($request, $response, $args){
        try{
            $parametros = $request->getParsedBody();
            $id = $parametros['id'];
            $payload = json_encode(array("Mensaje" => "No se pudo borrar el producto."));
            if(Producto::Borrar($id) > 0){
                $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //MODIFICA UN USUARIO DE LA BASE DE DATOS.
    public function ReadAll($request, $response, $args){
        try{
            $objetos = Producto::ObtenerTodos();
            $payload = json_encode(array("Mensaje" => "No se pudo recuperar la lista de productos."));
            if($objetos){
                $payload = json_encode(array("Lista de Productos" => $objetos));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    public function ReadOne($request, $response, $args){
        try{
            $id = $args['id'];
            $object = Producto::ObtenerUno($id);
            $payload = json_encode(array("Mensaje" => "No se encontró el producto."));
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



?>