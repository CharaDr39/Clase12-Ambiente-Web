<?php

    session_start();

    if (!isset($_SESSION['nombre_usuario'])) {
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./estilos/estilo.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <title>Productos</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <?php include 'includes/menu.php'; ?>
            <main class="col-md-9 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Catálogo de productos</h3>
                    <button class="btn btn-success mb-3" id="btnAgregar" data-bs-toggle="modal" data-bs-target="#modalProducto">Agregar Producto</button>
                </div>
                <table class="table table-bordered table-striped" id="tablaProductos">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </main>
            <footer class="text-center mt-3">
                &copy; 2025 - Desarrollado por Ambiente Web Cliente Servidor
            </footer>
        </div>
    </div>


    <div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalProductoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalProductoLabel">Registro de productos</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formularioProductos" method="post">
                        <input type="hidden" id="producto_id" name="producto_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="codigo" class="form-label">Código del Producto:</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" placeholder="P001" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del producto" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del producto"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="precio_compra" class="form-label">Precio de Compra:</label>
                                <input type="number" step="0.01" class="form-control" id="precio_compra" name="precio_compra" placeholder="0.00" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="precio_venta" class="form-label">Precio de Venta:</label>
                                <input type="number" step="0.01" class="form-control" id="precio_venta" name="precio_venta" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stock inicial:</label>
                                <input type="number" class="form-control" id="stock" name="stock" min="0" step="1" placeholder="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado:</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="" selected>Seleccione el estado</option>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" id="btnGuardarProducto">Guardar Producto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="./javascript/productos.js"></script>
</body>
</html>