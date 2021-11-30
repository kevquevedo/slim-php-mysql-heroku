<?php

require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/Mesa.php';
require_once __DIR__ . '/Producto.php';


class Pedido
{
    //****************   ATRIBUTOS  *************************
    
    private int $idPedido;
    private string $codigo;
    private string $estado;
    private int $idUsuario;
    private int $nombreUsuario;
    private int $idMesa; 
    private float $total;

    //****************   CONSTRUCTOR  ***********************

    public function __construct(){
    }  

    //****************   GETTERS Y SETTERS  *******************

    //Codigo
    public function getCodigo() : string {
        return $this->codigo;
    }

    public function setCodigo(string $codigo) : self {
        $this->codigo = $codigo;
        return $this;
    }

    //Estado
    public function getEstado() : string {
        return $this->estado;
    }

    public function setEstado(string $estado) : self {
        $this->estado = $estado;
        return $this;
    }

    //idUsuario
    public function getIdUsuario() : int {
        return $this->idUsuario;
    }

    public function setIdUsuario(int $idUsuario) : self {
        $this->idUsuario = $idUsuario;
        return $this;
    }

    //nombreUsuario
    public function getNombreUsuario() : string {
        return $this->nombreUsuario;
    }

    public function setNombreUsuario(string $nombreUsuario) : self {
        $this->nombreUsuario = $nombreUsuario;
        return $this;
    }

    //idMesa
    public function getIdMesa() : int {
        return $this->idMesa;
    }

    public function setIdMesa(int $idMesa) : self {
        $this->idMesa = $idMesa;
        return $this;
    }

    //total
    public function getTotal() : float {
        return $this->total;
    }

    public function setTotal(float $total) : self {
        $this->total = $total;
        return $this;
    }

    //Productos
    public function getProductos() {
        return $this->productos;
    }

    public function setProductos($productos) {
        $this->productos = $productos;
        return $this;
    }

    //****************   METODOS  ***********************

    //Genera el codigo en string
    public static function GenerarCodigo() {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $caracteresLength = strlen($caracteres);
        $length = 5;
        $codigo = '';
        for ($i = 0; $i < $length; $i++) {
            $codigo .= $caracteres[rand(0, $caracteresLength - 1)];
        }
        return $codigo;
    }

    //INCORPORA UN PEDIDO EN LA BD.
    public function Crear(){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $usuario = Usuario::ObtenerPorId($this->idUsuario);
            $mesa = Mesa::ObtenerPorId($this->idMesa);

            //Calcula el total del pedido
            $total = 0; $tiempoFinal = -3600; $demoraProducto = 0;
            foreach($this->getProductos() as $producto){
                $prod = Producto::ObtenerUno($producto["idProducto"]);
                $total += $prod->getValor() * $producto["cantidad"];
                if($prod->getDemora() > $demoraProducto){
                    $demoraProducto = $prod->getDemora();
                }
            }
            $tiempoDemora = date("H:i:s", $tiempoFinal + $demoraProducto);

            //GRABA LA BD DE PEDIDOS
            $consulta = $objAccesoDatos->prepararConsulta(
                "INSERT INTO pedidos (codigo, estado, idUsuario, nombreUsuario, idMesa, total, tiempoDemora) 
                    VALUES (:codigo, :estado, :idUsuario, :nombreUsuario, :idMesa, :total, :tiempoDemora)");
            $consulta->bindValue(':codigo', $this->getCodigo(), PDO::PARAM_STR);
            $consulta->bindValue(':estado', "En Preparacion", PDO::PARAM_STR);
            $consulta->bindValue(':idUsuario', $usuario->getId(), PDO::PARAM_INT);
            $consulta->bindValue(':nombreUsuario', $usuario->getNombre(), PDO::PARAM_STR);
            $consulta->bindValue(':idMesa', $mesa->id, PDO::PARAM_INT);
            $consulta->bindValue(':total', $total);
            $consulta->bindValue(':tiempoDemora', $tiempoDemora, PDO::PARAM_STR);
            $consulta->execute();
            $idDetallePedido = $objAccesoDatos->obtenerUltimoId();
            
            //GRABA LA BD DE DETALLE DEL PEDIDO
            foreach($this->getProductos() as $producto){
                $prod = Producto::ObtenerUno($producto["idProducto"]);
                $consulta = $objAccesoDatos->prepararConsulta(
                    "INSERT INTO pedidos_detalle (idPedido, cantidad, idProducto, nombreProducto, estado, sector) 
                        VALUES (:idPedido, :cantidad, :idProducto, :nombreProducto, :estado, :sector)");
                $consulta->bindValue(':idProducto', $prod->getId(), PDO::PARAM_INT);
                $consulta->bindValue(':nombreProducto', $prod->getNombre(), PDO::PARAM_STR);
                $consulta->bindValue(':idPedido', $idDetallePedido, PDO::PARAM_INT);
                $consulta->bindValue(':cantidad', $producto["cantidad"], PDO::PARAM_INT);
                $consulta->bindValue(':estado', "Pendiente", PDO::PARAM_INT);
                $consulta->bindValue(':sector', $prod->getSector(), PDO::PARAM_STR);
                $consulta->execute();
            }
            return $idDetallePedido;
            
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //MODIFICA UN PEDIDO DE LA BD.
    public function Modificar($idPedido, $parametros){
        try{
            
            //Elimina los datos del pedido anterior
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM pedidos_detalle 
                                                        WHERE idPedido = :idPedido");
            $consulta->bindValue(':idPedido', $idPedido);
            $consulta->execute();
            $idDetallePedido = $objAccesoDatos->obtenerUltimoId();

            //Da de alta los datos del nuevo pedido
            foreach($parametros as $producto){
                $objAccesoDatos2 = AccesoDatos::obtenerInstancia();
                $prod = Producto::ObtenerUno($producto["idProducto"]);
                $consulta = $objAccesoDatos2->prepararConsulta(
                    "INSERT INTO pedidos_detalle (idPedido, cantidad, idProducto, nombreProducto) 
                        VALUES (:idPedido, :cantidad, :idProducto, :nombreProducto)");
                $consulta->bindValue(':idProducto', $prod->getId(), PDO::PARAM_INT);
                $consulta->bindValue(':nombreProducto', $prod->getNombre(), PDO::PARAM_STR);
                $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
                $consulta->bindValue(':cantidad', $producto["cantidad"], PDO::PARAM_INT);
                $consulta->execute();
            }

            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //CANCELA UN PEDIDO EN LA BD.
    public static function Borrar($idPedido){
        try{
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos 
                                                          SET estado = :estado 
                                                          WHERE idPedido = :idPedido");
            $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->bindValue(':estado', "Cancelado");
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //OBTIENE TODOS LOS PEDIDOS DE LA BD.
    public static function ObtenerTodos(){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT idPedido, codigo, estado, idUsuario, nombreUsuario, idMesa, total, tiempoDemora
                FROM pedidos");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS);
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //OBTIENE UN PEDIDO POR ID.
    public static function ObtenerPorId($idPedido){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT idPedido, codigo, estado, idUsuario, nombreUsuario, idMesa, total, tiempoDemora
                FROM pedidos
                WHERE idPedido = :idPedido");
            $consulta->bindValue(':idPedido', $idPedido);
            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_OBJ);
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //Obtiene el detalle de platos pendientes del Candy Bar
    public static function ObtenerCandy($estado){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT idPedido, cantidad, idProducto, nombreProducto, estado, sector 
                FROM pedidos_detalle
                WHERE sector = :sector AND estado = :estado");
            $consulta->bindValue(':sector', "Candy Bar", PDO::PARAM_STR);
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS);
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //Obtiene el detalle de platos pendientes de la Cocina
    public static function ObtenerCocina($estado){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT idPedido, cantidad, idProducto, nombreProducto, estado, sector 
                FROM pedidos_detalle
                WHERE sector = :sector AND estado = :estado");
            $consulta->bindValue(':sector', "Cocina", PDO::PARAM_STR);
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS);
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //Obtiene el detalle de platos pendientes de la Barra de Tragos
    public static function ObtenerTragos($estado){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT idPedido, cantidad, idProducto, nombreProducto, estado, sector 
                FROM pedidos_detalle
                WHERE sector = :sector AND estado = :estado");
            $consulta->bindValue(':sector', "Barra de Tragos", PDO::PARAM_STR);
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS);
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //Obtiene el detalle de platos pendientes de la Barra de Cerveza
    public static function ObtenerCerveza($estado){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT idPedido, cantidad, idProducto, nombreProducto, estado, sector 
                FROM pedidos_detalle
                WHERE sector = :sector AND estado = :estado");
            $consulta->bindValue(':sector', "Barra de Cerveza", PDO::PARAM_STR);
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS);
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //Modifica el estado de platos pendientes del Candy Bar en la BD.
    public static function ModificarCandy($idPedido, $estado){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "UPDATE pedidos_detalle
                 SET estado = :estado 
                 WHERE idPedido = :idPedido AND sector = :sector");
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->bindValue(':sector', "Candy Bar", PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //Modifica el estado de platos pendientes de la Cocina en la BD.
    public static function ModificarCocina($idPedido, $estado){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "UPDATE pedidos_detalle
                    SET estado = :estado 
                    WHERE idPedido = :idPedido AND sector = :sector");
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->bindValue(':sector', "Cocina", PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //Modifica el estado de tragos pendientes de la Barra de Tragos en la BD.
    public static function ModificarTragos($idPedido, $estado){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "UPDATE pedidos_detalle
                    SET estado = :estado 
                    WHERE idPedido = :idPedido AND sector = :sector");
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->bindValue(':sector', "Barra de Tragos", PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //Modifica el estado de tragos pendientes de la Barra de Cerveza en la BD.
    public static function ModificarCerveza($idPedido, $estado){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "UPDATE pedidos_detalle
                    SET estado = :estado 
                    WHERE idPedido = :idPedido AND sector = :sector");
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
            $consulta->bindValue(':sector', "Barra de Cerveza", PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //Verifica el tiempo de espera de un pedido.
    public static function VerificarDemora($idPedido, $codigo){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT idPedido, codigo, estado, idUsuario, nombreUsuario, idMesa, total, tiempoDemora
                FROM pedidos
                WHERE idPedido = :idPedido AND codigo = :codigo");
            $consulta->bindValue(':idPedido', $idPedido);
            $consulta->bindValue(':codigo', $codigo);
            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_OBJ);
        }catch(PDOException $ex){
            throw $ex;
        }
    }



}
?>