<?php

    class Producto {
        private $conn;
        private $table_name = "productos";

        public $id_producto;
        public $codigo;
        public $nombre;
        public $descripcion;
        public $precio_compra;
        public $precio_venta;
        public $stock;

        public $estado;


        public function __construct($db) {
            $this->conn = $db;
        }

        public function obtenerTodos() {
            $sql = "SELECT id_producto, codigo, nombre, descripcion, precio_compra, precio_venta, stock, estado FROM {$this->table_name}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function obtenerPorId($id) {
            $sql = "SELECT id_producto, codigo, nombre, descripcion, precio_compra, precio_venta, stock, estado FROM {$this->table_name} WHERE id_producto = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch();
            return $resultado ?: null;
        }


        public function insertar() {
            $sql = "INSERT INTO {$this->table_name} (codigo, nombre, descripcion, precio_compra, precio_venta, stock, estado) VALUES (:codigo, :nombre, :descripcion, :precio_compra, :precio_venta, :stock, :estado)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':codigo', $this->codigo);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':precio_compra', $this->precio_compra);
            $stmt->bindParam(':precio_venta', $this->precio_venta);
            $stmt->bindParam(':stock', $this->stock, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $this->estado, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $this->id_producto = $this->conn->lastInsertId();
                return true;
            }
            return false;
        }


        public function actualizar() {
            $sql = "UPDATE {$this->table_name} SET codigo = :codigo, nombre = :nombre, descripcion = :descripcion, precio_compra = :precio_compra, precio_venta = :precio_venta, stock = :stock, estado = :estado WHERE id_producto = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':codigo', $this->codigo);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':precio_compra', $this->precio_compra);
            $stmt->bindParam(':precio_venta', $this->precio_venta);
            $stmt->bindParam(':stock', $this->stock, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $this->estado, PDO::PARAM_INT);
            $stmt->bindParam(':id', $this->id_producto, PDO::PARAM_INT);
            return $stmt->execute();
        }


        public function eliminar($id) {
            $sql = "UPDATE {$this->table_name} SET estado = 0 WHERE id_producto = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }


        public function existeCodigo($codigo, $id_excluir = null) {
            $sql = "SELECT COUNT(*) FROM {$this->table_name} WHERE codigo = :codigo";
            if ($id_excluir !== null) {
                $sql .= " AND id_producto <> :id_excluir";
            }
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':codigo', $codigo);
            if ($id_excluir !== null) {
                $stmt->bindParam(':id_excluir', $id_excluir, PDO::PARAM_INT);
            }
            $stmt->execute();
            $count = (int) $stmt->fetchColumn();
            return $count > 0;
        }
    }
?>