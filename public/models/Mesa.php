<?php

class Mesa
{
    //****************   ATRIBUTOS  ***************************

    private int $id;
    private string $estado;

    //****************   CONSTRUCTOR  ************************

    public function __construct(){
    }

    //****************   GETTERS Y SETTERS  *******************

    //ID
    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    //ESTADO
    public function getEstado(): string {
        return $this->estado;
    }

    public function setEstado(string $estado): self {
        $this->estado = $estado;
        return $this;
    }

    //****************   METODOS DE BASE DE DATOS  ******************* 

    public function Crear(){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "INSERT INTO mesas (estado) 
                 VALUES (:estado)");
            $consulta->bindValue(':estado', $this->getEstado(), PDO::PARAM_STR);
            $consulta->execute();
            return $objAccesoDatos->obtenerUltimoId();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    public function Modificar($id, $estado){
        try{
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas 
                                                          SET estado = :estado
                                                          WHERE id = :id");
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    public function Borrar($id){
        try{
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET fechaBaja = :fechaBaja WHERE id = :id");
            $fecha = new DateTime(date("d-m-Y"));
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    public static function ObtenerTodos(){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT id, estado
                FROM mesas");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS);
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    public static function ObtenerPorId($id){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT id, estado
                FROM mesas
                WHERE id = :id");
            $consulta->bindValue(':id', $id);
            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_OBJ);
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    public static function ModificarMesaMozo($id, $estado){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "UPDATE mesas
                    SET estado = :estado 
                    WHERE id = :id");
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    public static function ModificarMesaSocio($id, $estado){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "UPDATE mesas
                    SET estado = :estado 
                    WHERE id = :id");
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    public static function MesaMasUsada(){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT count(*) as cantidad, idMesa
                FROM pedidos
                GROUP BY idMesa");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_OBJ);
        }catch(PDOException $ex){
            throw $ex;
        }
    }

}



?>