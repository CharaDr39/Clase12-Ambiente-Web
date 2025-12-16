<?php

    require_once("../includes/database.php");
    require_once("../models/producto.php");

    try {
        // Establecer conexión
        $database = new Database();
        $db = $database->getConnection();

        // Instanciar modelo de producto
        $producto = new Producto($db);

        // Obtener todos los productos
        $resultado = $producto->obtenerTodos();

        // Establecer cabecera JSON
        header('Content-Type: application/json');

        if ($resultado && count($resultado) > 0) {
            echo json_encode($resultado);
        } else {
            // Devuelve arreglo vacío si no hay datos
            echo json_encode([]);
        }
    } catch (Exception $e) {
        error_log("Error al obtener productos: " . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode([
            'status'  => 'error',
            'message' => 'Error al obtener productos'
        ]);
    }
?>