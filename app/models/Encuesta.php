<?php

class Encuesta
{
    //****************   ATRIBUTOS  ***************************

    private string $codigoPedido;
    private int $idPedido;
    private int $puntoMesa;
    private int $puntoRestaurant;    
    private int $puntoMozo;
    private int $puntoCocinero;
    private string $comentario;

    //****************   CONSTRUCTOR  ************************

    public function __construct(){
    }

    //****************   GETTERS Y SETTERS  *******************

    //codigoPedido
    public function getCodigo(): string {
        return $this->codigoPedido;
    }

    public function setCodigo(string $codigoPedido): self {
        $this->codigoPedido = $codigoPedido;
        return $this;
    }

    //idPedido
    public function getIdPedido(): int {
        return $this->idPedido;
    }

    public function setIdPedido(int $idPedido): self {
        $this->idPedido = $idPedido;
        return $this;
    }

    //puntoMesa
    public function getPuntajeMesa(): int {
        return $this->puntoMesa;
    }

    public function setPuntajeMesa(int $puntoMesa): self {
        $this->puntoMesa = $puntoMesa;
        return $this;
    }

    //puntoRestaurant
    public function getPuntajeRestaurant(): int {
        return $this->puntoRestaurant;
    }

    public function setPuntajeRestaurant(int $puntoRestaurant): self {
        $this->puntoRestaurant = $puntoRestaurant;
        return $this;
    }

    //puntoMozo
    public function getPuntajeMozo(): int {
        return $this->puntoMozo;
    }

    public function setPuntajeMozo(int $puntoMozo): self {
        $this->puntoMozo = $puntoMozo;
        return $this;
    }

    //puntoCocinero
    public function getPuntajeCocinero(): int {
        return $this->puntoCocinero;
    }

    public function setPuntajeCocinero(int $puntoCocinero): self {
        $this->puntoCocinero = $puntoCocinero;
        return $this;
    }

    //comentario
    public function getComentario(): string {
        return $this->comentario;
    }

    public function setComentario(string $comentario): self {
        $this->comentario = $comentario;
        return $this;
    }

    //****************   METODOS DE BASE DE DATOS  ******************* 

    //CREA UNA NUEVA ENCUESTA EN LA BD.
    public function CargarEncuesta(){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "INSERT INTO encuestas (codigoPedido, idPedido, puntoMesa, puntoRestaurant, puntoMozo, puntoCocinero, comentario) 
                 VALUES (:codigoPedido, :idPedido, :puntoMesa, :puntoRestaurant, :puntoMozo, :puntoCocinero, :comentario)");
            $consulta->bindValue(':codigoPedido', $this->getCodigo(), PDO::PARAM_STR);
            $consulta->bindValue(':idPedido', $this->getIdPedido(), PDO::PARAM_INT);
            $consulta->bindValue(':puntoMesa', $this->getPuntajeMesa(), PDO::PARAM_INT);
            $consulta->bindValue(':puntoRestaurant', $this->getPuntajeRestaurant(), PDO::PARAM_INT);
            $consulta->bindValue(':puntoMozo', $this->getPuntajeMozo(), PDO::PARAM_INT);
            $consulta->bindValue(':puntoCocinero', $this->getPuntajeCocinero(), PDO::PARAM_INT);
            $consulta->bindValue(':comentario', $this->getComentario(), PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //OBTIENE LOS MEJORES COMENTARIOS DE LA ENCUESTA EN LA BD.
    public static function MejoresComentarios(){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT comentario
                FROM encuestas
                WHERE 
                puntoMesa >= :puntoMesa AND
                puntoRestaurant >= :puntoRestaurant AND
                puntoMozo >= :puntoMozo AND
                puntoCocinero >= :puntoCocinero");
            $consulta->bindValue(':puntoMesa', "7");
            $consulta->bindValue(':puntoRestaurant', "7");
            $consulta->bindValue(':puntoMozo', "7");
            $consulta->bindValue(':puntoCocinero', "7");
            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_OBJ);
        }catch(PDOException $ex){
            throw $ex;
        }
    }


}