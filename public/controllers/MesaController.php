<?php

require_once './models/Mesa.php';
require_once './interfaces/IMethods.php';

class MesaController  extends Mesa implements IMethods {

    //CREA UNA NUEVA MESA EN LA BD.
    public function Insert($request, $response, $args){
        try{            
            $newMesa = new Mesa(); $newMesa->setEstado("Cerrada");
            $payload = json_encode(array("Mensaje" => "Error. No se pudo agregar la mesa."));
            if($newMesa->Crear() != null){
                $payload = json_encode(array("Mensaje" => "Se agrego la mesa con exito."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //MODIFICA UNA MESA EN LA BD.
    public function Update($request, $response, $args){
        try{          
            $parametros = $request->getParsedBody();
            $id = $parametros['id']; $estado = $parametros['estado'];
            $payload = json_encode(array("Mensaje" => "No se pudo modificar la mesa."));
            if(Mesa::Modificar($id, $estado) > 0){
                $payload = json_encode(array("Mensaje" => "Mesa modificado con exito."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //ELIMINA UNA MESA EN LA BD.
    public function Delete($request, $response, $args){
        try{
            $parametros = $request->getParsedBody();
            $id = $parametros['id'];
            $payload = json_encode(array("Mensaje" => "No se pudo borrar la mesa."));
            if(Mesa::Borrar($id) > 0){
                $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //OBTIENE TODAS LAS MESAS DE LA BD.
    public function ReadAll($request, $response, $args){
        try{
            $objetos = Mesa::ObtenerTodos();
            $payload = json_encode(array("Mensaje" => "No se pudo recuperar la lista de mesas."));
            if($objetos){
                $payload = json_encode(array("Lista de Mesas" => $objetos));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //OBTIENE UNA MESA DE LA BD POR ID.
    public function ReadOne($request, $response, $args){
        try{
            $id = $args['id'];
            $object = Mesa::ObtenerPorId($id);
            $payload = json_encode(array("Mensaje" => "No se encontró la mesa."));
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

    //MODIFICA EL ESTADO DE LAS MESAS
    public static function ModifyMesaMozo($request, $response, $args){
        try{
            $estado = $args['estado']; $idMesa = $args['id'];
            $mesa = Mesa::ModificarMesaMozo($idMesa, $estado);
            $payload = json_encode(array("Mensaje" => "No se modifico el estado de la Mesa."));
            if($mesa > 0){
                $payload = json_encode(array("Mensaje" => "Se modifico correctamente el estado de la Mesa."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //MODIFICA EL ESTADO DE LAS MESAS
    public static function ModifyMesaSocio($request, $response, $args){
        try{
            $estado = "Cerrada"; $idMesa = $args['id'];
            $mesa = Mesa::ModificarMesaMozo($idMesa, $estado);
            $payload = json_encode(array("Mensaje" => "No se modifico el estado de la Mesa."));
            if($mesa > 0){
                $payload = json_encode(array("Mensaje" => "Se modifico correctamente el estado de la Mesa."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //OBTIENE LA MESA MAS USADA
    public static function TableMasUsada($request, $response, $args){
        try{
            $usoDeMesas = Mesa::MesaMasUsada(); $cantidad = 0; $idMesa;
            foreach($usoDeMesas as $usoMesa){
                if($usoMesa->cantidad > $cantidad){
                    $cantidad = $usoMesa->cantidad;
                    $idMesa  = $usoMesa->idMesa;
                }
            }
            $payload = json_encode(array("Mensaje" => "No se pudo obtener la Mesa mas usada."));
            if($usoDeMesas != null){
                $payload = json_encode(array("Mensaje" => "La mesa mas usada es la " . $idMesa . " con " . $cantidad . " usos."));
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