<?php

class Producto
{
    //****************   ATRIBUTOS  *************************

    private int $id;
    private string $nombre;
    private string $tipo;
    private string $sector;
    private float $valor;
    private int $demora;

    //****************   CONSTRUCTOR  ***********************

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

    //NOMBRE
    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self {
        $this->nombre = $nombre;
        return $this;
    }

    //TIPO
    public function getTipo(): string {
        return $this->tipo;
    }

    public function setTipo(string $tipo) : self {
        $this->tipo = $tipo;
        return $this;
    }

    //SECTOR
    public function getSector(): string {
        return $this->sector;
    }

    public function setSector(string $sector): self {
        $this->sector = $sector;
        return $this;
    }

    //VALOR
    public function getValor() : float {
        return $this->valor;
    }
 
    public function setValor(float $valor) : self {
        $this->valor = $valor;
        return $this;
    }

    //DEMORA
    public function getDemora() : int {
        return $this->demora;
    }
    
    public function setDemora(int $demora) : self {
        $this->demora = $demora;
        return $this;
    }

    //****************   METODOS DE BASE DE DATOS  ******************* 

    //INCORPORA UN PRODUCTO EN LA BD.
    public function Crear(){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "INSERT INTO productos (nombre, tipo, sector, valor, demora) 
                 VALUES (:nombre, :tipo, :sector, :valor, :demora)");
            $consulta->bindValue(':nombre', $this->getNombre(), PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $this->getTipo(), PDO::PARAM_STR);
            $consulta->bindValue(':sector', $this->getSector(), PDO::PARAM_STR);
            $consulta->bindValue(':valor', $this->getValor());
            $consulta->bindValue(':demora', $this->getDemora());
            $consulta->execute();
            return $objAccesoDatos->obtenerUltimoId();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //MODIFICA UN PRODUCTO DE LA BD.
    public function Modificar($id, $nombre, $tipo, $sector, $valor){
        try{
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE productos 
                                                            SET nombre = :nombre, tipo = :tipo, 
                                                                sector = :sector, valor = :valor
                                                            WHERE id = :id");
            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
            $consulta->bindValue(':valor', $valor);
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //ELIMINA UN PRODUCTO EN LA BD.
    public static function Borrar($id){
        try{
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET fechaBaja = :fechaBaja WHERE id = :id");
            $fecha = new DateTime(date("d-m-Y"));
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->execute();
            return $consulta->rowCount();
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //TRAE TODOS LOS PRODUCTOS DE LA BASE DE DATOS.
    public static function ObtenerTodos(){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT id, nombre, tipo, sector, valor 
                FROM productos");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS);
        }catch(PDOException $ex){
            throw $ex;
        }
    }

    //OBTIENE UN USUARIO DE LA BD.
    public static function ObtenerUno($id){
        try{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT id, nombre, tipo, sector, valor, demora 
                FROM productos
                WHERE id = :id");
            $consulta->bindValue(':id', $id);
            $consulta->execute();
            return $consulta->fetchObject('Producto');;
        }catch(PDOException $ex){
            throw $ex;
        }
    }



}

?>