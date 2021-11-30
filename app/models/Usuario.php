<?php

require_once './database/AccesoDatos.php';

class Usuario
{
    //****************   ATRIBUTOS  *************************
    
    private int $id;
    private string $nombre;
    private string $apellido;
    private string $tipo;
    private string $sector;
    private string $contrasena;
    private string $email;

    //****************   CONSTRUCTOR  ***********************

    public function __construct(){
    } 

    //****************   GETTERS Y SETTERS  *******************

    //ID
    public function getId() : int {
        return $this->id;
    }

    public function setId(int $id) : self {
        $this->id = $id;
        return $this;
    }

    //NOMBRE  
    public function getNombre() : string {
        return $this->nombre;
    }

    public function setNombre(string $nombre) : self {
        $this->nombre = $nombre;
        return $this;
    }

    //APELLIDO  
    public function getApellido() : string {
        return $this->apellido;
    }

    public function setApellido( string $apellido) : self {
        $this->apellido = $apellido;
        return $this;
    }

    //TIPO DE USUARIO
    public function getTipo(): string {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self {

        $this->tipo = $tipo;
        return $this;
    }

    //SECTOR DE USUARIO
    public function getSector(): string {
        return $this->sector;
    }

    public function setSector(string $sector): self {

        switch($sector){

            case "bartender":
                $this->sector = "Barra de Tragos";
                break;
            case "cervecero":
                $this->sector = "Barra de Cerveza";
                break;
            case "cocinero":
                $this->sector = "Cocina";
                break;
            case "pastelero":
                $this->sector = "Candy Bar";
                break;
            default:
                $this->sector = "N/A";
                break;
        }
        return $this;
    }

    //EMAIL
    public function getEmail() : string {
        return $this->email;
    }

    public function setEmail(string $email) : self {
        $this->email = $email;
        return $this;
    }

    //CONTRASEÑA
    public function getContrasena() : string {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena){
        $this->contrasena = password_hash($contrasena, PASSWORD_BCRYPT);
        return $this;
    }

    //****************   METODOS DE BASE DE DATOS  *******************  

        //INCORPORA UN USUARIO EN LA BD.
        public function Crear(){
            try{
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta(
                    "INSERT INTO usuarios (nombre, apellido, tipo, sector, email, contrasena) 
                    VALUES (:nombre, :apellido, :tipo, :sector, :email, :contrasena )");
                $consulta->bindValue(':nombre', $this->getNombre(), PDO::PARAM_STR);
                $consulta->bindValue(':apellido', $this->getApellido(), PDO::PARAM_STR);
                $consulta->bindValue(':tipo', $this->getTipo());
                $consulta->bindValue(':sector', $this->getSector());
                $consulta->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
                $consulta->bindValue(':contrasena', $this->getContrasena());
                $consulta->execute();
                return $objAccesoDatos->obtenerUltimoId();
            }catch(PDOException $ex){
                throw $ex;
            }
        }

        //MODIFICA UN USUARIO DE LA BD.
        public function Modificar($id, $nombre, $apellido, $tipo, $email, $contrasena){
            try{
                $claveHash = password_hash($contrasena, PASSWORD_DEFAULT);
                $objAccesoDato = AccesoDatos::obtenerInstancia();
                $usuario = new Usuario(); $usuario->setSector($tipo);
                $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios 
                                                            SET nombre = :nombre, apellido = :apellido, tipo = :tipo, 
                                                                sector = :sector, email = :email, contrasena = :contrasena
                                                            WHERE id = :id");
                $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
                $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
                $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
                $consulta->bindValue(':sector', $usuario->getSector(), PDO::PARAM_STR); //VER SECTOR
                $consulta->bindValue(':email', $email, PDO::PARAM_STR);
                $consulta->bindValue(':contrasena', $claveHash);
                $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                $consulta->execute();
                return $consulta->rowCount();
            }catch(PDOException $ex){
                throw $ex;
            }
        }
        
        //ELIMINA UN USUARIO EN LA BD.
        public static function Borrar($id){
            try{
                $objAccesoDato = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
                $fecha = new DateTime(date("d-m-Y"));
                $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
                $consulta->execute();
                return $consulta->rowCount();
            }catch(PDOException $ex){
                throw $ex;
            }
        }

        //OBTIENE TODOS LOS USUARIOS DE LA BD.
        public static function ObtenerTodos(){
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT id, nombre, apellido, tipo, sector, email, contrasena 
                FROM usuarios");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS);
        }

        //OBTIENE UN USUARIO POR ID.
        public static function ObtenerPorId($id){
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT id, nombre, apellido, tipo, sector, email, contrasena 
                FROM usuarios
                WHERE id = :id");
            $consulta->bindValue(':id', $id);
            $consulta->execute();
            return $consulta->fetchObject('Usuario');
        }

        //OBTIENE UN USUARIO POR MAIL.
        public static function ObtenerPorEmail($email){
            try{
                $objAccesoDatos = AccesoDatos::obtenerInstancia();
                $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, apellido, tipo, sector, email, contrasena 
                                                            FROM usuarios 
                                                            WHERE email = :email");
                $consulta->bindValue(':email', $email, PDO::PARAM_STR);
                $consulta->execute();
                return $consulta->fetchObject('Usuario');
            }catch(PDOException $ex){
                throw $ex;
            }
        }

        //VALIDA SI EXISTE EL USUARIO EN LA BD.
        public static function ValidarUsuario($email, $contrasena){
            try{
                $retorno = null;
                $usuarioBuscado = Usuario::ObtenerPorEmail($email);
                if(password_verify($contrasena, $usuarioBuscado->getContrasena())){
                    $retorno = $usuarioBuscado;
                }
                return $retorno;
            }catch(PDOException $ex){
                throw $ex;
            }
        }
}