<?php

require_once './models/Pedido.php';
require_once './interfaces/IMethods.php';

class PedidoController extends Pedido implements IMethods {

    //CARGA UN PEDIDO NUEVO EN LA BASE DE DATOS.
    public function Insert($request, $response, $args){
        try{
            $parametros = $request->getParsedBody();
            $codigo = Pedido::GenerarCodigo();
            $pedido = new Pedido(); 
            $pedido->setCodigo($codigo); $pedido->setIdUsuario($parametros[0]["idUsuario"]); 
            $pedido->setIdMesa($parametros[0]["idMesa"]); $pedido->setProductos($parametros);

            $payload = json_encode(array("Mensaje" => "Error. No se pudo agregar el pedido."));
            if($pedido->Crear() != null){
                $payload = json_encode(array("Mensaje" => "Se agrego el pedido con exito."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //MODIFICA UN PEDIDO DE LA BASE DE DATOS.
    public function Update($request, $response, $args){
        try{          
            $parametros = $request->getParsedBody();
            $idPedido = $parametros[0]["idPedido"];

            $payload = json_encode(array("Mensaje" => "No se pudo modificar el pedido."));
            if(Pedido::Modificar($idPedido, $parametros) > 0){
                $payload = json_encode(array("Mensaje" => "Pedido modificado con exito."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //ELIMINA/CANCELA UN PEDIDO DE LA BASE DE DATOS.
    public function Delete($request, $response, $args){
        try{
            $parametros = $request->getParsedBody();
            $id = $parametros['idPedido'];
            $payload = json_encode(array("Mensaje" => "No se pudo cancelar el pedido."));
            if(Pedido::Borrar($id) > 0){
                $payload = json_encode(array("mensaje" => "Pedido cancelado con exito"));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //TRAE TODOS LOS PEDIDOS DE LA BASE DE DATOS.
    public function ReadAll($request, $response, $args){
        try{
            $objetos = Pedido::ObtenerTodos();
            $payload = json_encode(array("Mensaje" => "No se pudo recuperar la lista de pedidos."));
            if($objetos){
                $payload = json_encode(array("Lista de Pedidos" => $objetos));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //TRAE UN PEDIDO POR EL ID DEL MISMO.
    public function ReadOne($request, $response, $args){
        try{
            $id = $args['id'];
            $object = Pedido::ObtenerPorId($id);
            $payload = json_encode(array("Mensaje" => "No se encontró el pedido."));
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

    //CARGA UNA FOTO AL PEDIDO
    public function LoadPhoto($request, $response, $args){
        try{
            $parametros = $request->getParsedBody();
            $id = $parametros['idPedido'];
            $pedido = Pedido::ObtenerPorId($id);
            //Foto
            $dir_subida = 'FotosPedidos/';
            $extension = explode(".",$_FILES["archivo"]["name"])[1];
            $payload = json_encode(array("mensaje" => "No se pudo cargar la foto."));
            if($extension == "jpg" || $extension == "jpeg" || $extension == "png"){
                $nombreArchivo = $pedido->idPedido ."-".$pedido->nombreUsuario."-".$pedido->codigo;
                $destino = $dir_subida .$nombreArchivo ."." .$extension;
                if(move_uploaded_file($_FILES["archivo"]["tmp_name"],$destino)){
                    $payload = json_encode(array("mensaje" => "Foto cargada correctamente."));
                }
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //CARGA EL DETALLE DE PEDIDOS DEL CANDY BAR
    public function LoadCandy($request, $response, $args){
        try{
            $estado = $args['estado'];
            $listaCandy = Pedido::ObtenerCandy($estado);
            $payload = json_encode(array("Mensaje" => "No existen pendientes para el Candy Bar."));
            if($listaCandy != null){
                $payload = json_encode(array("Lista Candy Bar: " => $listaCandy));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //CARGA EL DETALLE DE PEDIDOS DE LA COCINA
    public function LoadCocina($request, $response, $args){
        try{
            $estado = $args['estado'];
            $listaCocina = Pedido::ObtenerCocina($estado);
            $payload = json_encode(array("Mensaje" => "No existen pendientes para la Cocina."));
            if($listaCocina != null){
                $payload = json_encode(array("Lista Cocina: " => $listaCocina));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //CARGA EL DETALLE DE PEDIDOS DE LA BARRA DE TRAGOS
    public function LoadTragos($request, $response, $args){
        try{
            $estado = $args['estado'];
            $listaTragos = Pedido::ObtenerTragos($estado);
            $payload = json_encode(array("Mensaje" => "No existen pendientes para la Barra de Tragos."));
            if($listaTragos != null){
                $payload = json_encode(array("Lista Tragos: " => $listaTragos));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //CARGA EL DETALLE DE PEDIDOS DE LA BARRA DE CERVEZA
    public function LoadCerveza($request, $response, $args){
        try{
            $estado = $args['estado'];
            $listaCerveza = Pedido::ObtenerCerveza($estado);
            $payload = json_encode(array("Mensaje" => "No existen pendientes para la Barra de Cerveza."));
            if($listaCerveza != null){
                $payload = json_encode(array("Lista Cerveza: " => $listaCerveza));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //MODIFICA EL ESTADO DE LOS PEDIDOS DEL CANDY BAR
    public function ModifyCandy($request, $response, $args){
        try{
            $parametros = $request->getParsedBody(); 
            $estado = $args['estado']; $idPedido = $parametros['idPedido'];
            $listaCandy = Pedido::ModificarCandy($idPedido, $estado);
            $payload = json_encode(array("Mensaje" => "No se modifico el estado de un pedido para el Candy Bar."));
            if($listaCandy > 0){
                $payload = json_encode(array("Mensaje" => "Se modifico correctamente el estado del pedido."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //MODIFICA EL ESTADO DE LOS PEDIDOS DE LA COCINA
    public function ModifyCocina($request, $response, $args){
        try{
            $parametros = $request->getParsedBody(); 
            $estado = $args['estado']; $idPedido = $parametros['idPedido'];
            $listaCocina = Pedido::ModificarCocina($idPedido, $estado);
            $payload = json_encode(array("Mensaje" => "No se modifico el estado de un pedido para la Cocina."));
            if($listaCocina > 0){
                $payload = json_encode(array("Mensaje" => "Se modifico correctamente el estado del pedido."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //MODIFICA EL ESTADO DE LOS PEDIDOS DE LA BARRA DE TRAGOS
    public function ModifyTragos($request, $response, $args){
        try{
            $parametros = $request->getParsedBody(); 
            $estado = $args['estado']; $idPedido = $parametros['idPedido'];
            $listaTragos = Pedido::ModificarTragos($idPedido, $estado);
            $payload = json_encode(array("Mensaje" => "No se modifico el estado de un pedido para la Barra de Tragos."));
            if($listaTragos > 0){
                $payload = json_encode(array("Mensaje" => "Se modifico correctamente el estado del pedido."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //MODIFICA EL ESTADO DE LOS PEDIDOS DE LA BARRA DE CERVEZAS
    public function ModifyCerveza($request, $response, $args){
        try{
            $parametros = $request->getParsedBody(); 
            $estado = $args['estado']; $idPedido = $parametros['idPedido'];
            $listaCerveza = Pedido::ModificarCerveza($idPedido, $estado);
            $payload = json_encode(array("Mensaje" => "No se modifico el estado de un pedido para la Barra de Cerveza."));
            if($listaCerveza > 0){
                $payload = json_encode(array("Mensaje" => "Se modifico correctamente el estado del pedido."));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

    //INFORMA EL TIEMPO DE ESPERA DE UN PEDIDO, SEGUN SU CODIGO.
    public function LoadEspera($request, $response, $args){
        try{
            $parametros = $request->getParsedBody(); 
            $codigo = $parametros['codigo']; $idPedido = $parametros['idPedido'];
            $pedido = Pedido::VerificarDemora($idPedido, $codigo);
            $payload = json_encode(array("Mensaje" => "No se pudo verificar el tiempo de espera."));
            if($pedido != null){
                $payload = json_encode(array("Tiempo de Espera" => $pedido->tiempoDemora));
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

?>