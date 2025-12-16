<?php

    require_once("../includes/database.php");
    require_once("../models/producto.php");


    function json_response(string $status, string $message): void {
        header('Content-Type: application/json');
        echo json_encode([
            'status'  => $status,
            'message' => $message
        ]);
        exit();
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recibir y sanitizar datos
        $id             = isset($_POST['producto_id']) ? trim($_POST['producto_id']) : '';
        $codigo         = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';
        $nombre         = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $descripcion    = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
        $precio_compra  = isset($_POST['precio_compra']) ? trim($_POST['precio_compra']) : '';
        $precio_venta   = isset($_POST['precio_venta']) ? trim($_POST['precio_venta']) : '';
        $stock          = isset($_POST['stock']) ? trim($_POST['stock']) : '';
        $estado         = isset($_POST['estado']) ? trim($_POST['estado']) : '';


        if ($codigo === '' || $nombre === '' || $precio_compra === '' || $precio_venta === '' || $stock === '' || $estado === '') {
            json_response('error', 'Debe completar todos los campos obligatorios');
        }


        if (!is_numeric($precio_compra) || !is_numeric($precio_venta)) {
            json_response('error', 'Los precios deben ser valores numéricos');
        }

        $precio_compra = (float) $precio_compra;
        $precio_venta  = (float) $precio_venta;


        if ($precio_compra <= 0 || $precio_venta <= 0) {
            json_response('error', 'Los precios deben ser mayores que cero');
        }


        if ($precio_venta < ($precio_compra * 1.10)) {
            json_response('error', 'El precio de venta debe ser al menos 10% mayor que el precio de compra');
        }


        if (!ctype_digit($stock) || (int) $stock < 0) {
            json_response('error', 'El stock debe ser un número entero igual o mayor que cero');
        }
        $stock = (int) $stock;


        if (!in_array($estado, ['0', '1'], true)) {
            json_response('error', 'Valor de estado inválido');
        }
        $estado_int = (int) $estado;

        try {
            $database = new Database();
            $db       = $database->getConnection();
            $producto = new Producto($db);


            $id_excluir = ($id !== '') ? (int) $id : null;
            if ($producto->existeCodigo($codigo, $id_excluir)) {
                json_response('error', 'El código del producto ya existe');
            }


            $producto->codigo        = htmlspecialchars(strip_tags($codigo));
            $producto->nombre        = htmlspecialchars(strip_tags($nombre));
            $producto->descripcion   = htmlspecialchars(strip_tags($descripcion));
            $producto->precio_compra = $precio_compra;
            $producto->precio_venta  = $precio_venta;
            $producto->stock         = $stock;
            $producto->estado        = $estado_int;

            if ($id === '') {
                // Insertar nuevo producto
                if ($producto->insertar()) {
                    json_response('success', 'Producto registrado correctamente');
                } else {
                    json_response('error', 'No se pudo registrar el producto');
                }
            } else {
                // Actualizar producto existente
                $producto->id_producto = (int) $id;
                if ($producto->actualizar()) {
                    json_response('success', 'Producto actualizado correctamente');
                } else {
                    json_response('error', 'No se pudo actualizar el producto');
                }
            }
        } catch (Exception $e) {
            error_log('Error al procesar producto: ' . $e->getMessage());
            json_response('error', 'Error en el servidor al procesar el producto');
        }
    }


    if (isset($_GET['eliminar'])) {
        $id = $_GET['eliminar'];
        if (!ctype_digit($id)) {
            json_response('error', 'ID de producto inválido');
        }
        try {
            $database = new Database();
            $db       = $database->getConnection();
            $producto = new Producto($db);
            if ($producto->eliminar((int) $id)) {
                json_response('success', 'Producto eliminado correctamente');
            } else {
                json_response('error', 'No se pudo eliminar el producto');
            }
        } catch (Exception $e) {
            error_log('Error al eliminar producto: ' . $e->getMessage());
            json_response('error', 'Error en el servidor al eliminar el producto');
        }
    }

    // Si no coincide ninguna ruta
    json_response('error', 'Solicitud no válida');
?>