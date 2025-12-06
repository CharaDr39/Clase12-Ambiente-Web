<?php
/**
 * Modelo para la tabla de usuarios
 * Contiene todas las operaciones CRUD
 */

class Usuario{
    private $conn;
    private $table_name = "usuarios";

    //Atributos usuarios
    public $id_usuario;
    public $nombre;
    public $usuario;
    public $correo;
    public $clave;
    public $rol;
    public $estado;

    public function __construct($db){
        $this->conn =$db;
    }

    /**
     * Obtener todos los usuarios
     */

    public function obtenerTodos(){
        $query = "SELECT id_usuario,nombre,usuario,correo,rol,estado FROM usuarios";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Obtener usuario por corrreo
     */

    public function obtenerPorCorreo($correo){
        $sql = "SELECT nombre, clave, rol FROM usuarios WHERE correo = :correo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":correo",$correo);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
?>