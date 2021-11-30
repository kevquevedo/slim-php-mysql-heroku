<?php

require_once './models/Encuesta.php';

class EncuestaController extends Encuesta {

    //CREA UNA NUEVA ENCUESTA.
    public function LoadEncuesta($request, $response, $args){
        try{  
            $param = $request->getParsedBody();          
            $encuesta = new Encuesta(); 
            $encuesta->setCodigo($param['codigo']); $encuesta->setIdPedido($param['idPedido']); 
            $encuesta->setPuntajeMesa($param['puntoMesa']); $encuesta->setPuntajeRestaurant($param['puntoRest']); 
            $encuesta->setPuntajeMozo($param['puntoMozo']); $encuesta->setPuntajeCocinero($param['puntoCoci']);
            $encuesta->setComentario($param['comentario']);
            $payload = json_encode(array("Mensaje" => "Error. No se pudo agregar la encuesta."));
            if($encuesta->CargarEncuesta() != null){
                $payload = json_encode(array("Mensaje" => "Se agrego la encuesta con exito."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //Obtiene los mejores comentarios de las encuestas
    public function BestComentario($request, $response, $args){
        try{  
            $listaComentarios = Encuesta::MejoresComentarios();
            $payload = json_encode(array("Mensaje" => "Error. No se pudo obtener los mejores comentarios."));
            if($listaComentarios != null){
                $payload = json_encode(array("Lista de Mejores Comentarios" => $listaComentarios));
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