
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('modalProducto');
    const modal = new bootstrap.Modal(modalElement);
    const modalTitle = document.getElementById('modalProductoLabel');
    const form = document.getElementById('formularioProductos');


    cargarProductos();


    function cargarProductos() {
        $.get('./api/obtener_productos.php', function (data) {
            let html = '';
            if (data && data.length > 0) {
                data.forEach(producto => {

                    const precioCompra = parseFloat(producto.precio_compra).toFixed(2);
                    const precioVenta = parseFloat(producto.precio_venta).toFixed(2);
                    const estadoTexto = (producto.estado === 1 || producto.estado === '1') ? 'Activo' : 'Inactivo';
                    html += `
                        <tr>
                            <td>${producto.codigo}</td>
                            <td>${producto.nombre}</td>
                            <td>${producto.descripcion || ''}</td>
                            <td>${precioCompra}</td>
                            <td>${precioVenta}</td>
                            <td>${producto.stock}</td>
                            <td>${estadoTexto}</td>
                            <td>
                                <a href="#" class="btn btn-warning btn-sm btnEditar"
                                    data-id="${producto.id_producto}"
                                    data-codigo="${producto.codigo}"
                                    data-nombre="${producto.nombre}"
                                    data-descripcion="${producto.descripcion || ''}"
                                    data-precio_compra="${producto.precio_compra}"
                                    data-precio_venta="${producto.precio_venta}"
                                    data-stock="${producto.stock}"
                                    data-estado="${producto.estado}">
                                    Editar
                                </a>
                                <a href="#" class="btn btn-danger btn-sm btnEliminar" data-id="${producto.id_producto}">Eliminar</a>
                            </td>
                        </tr>`;
                });
            }
            $('#tablaProductos tbody').html(html);
        }, 'json');
    }


    $(document).on('click', '.btnEditar', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        modalTitle.textContent = 'Editar Producto';
        // Cargar valores
        $('#producto_id').val(id);
        $('#codigo').val($(this).data('codigo'));
        $('#nombre').val($(this).data('nombre'));
        $('#descripcion').val($(this).data('descripcion'));
        $('#precio_compra').val($(this).data('precio_compra'));
        $('#precio_venta').val($(this).data('precio_venta'));
        $('#stock').val($(this).data('stock'));
        $('#estado').val($(this).data('estado'));
        modal.show();
    });


    $('#modalProducto').on('hidden.bs.modal', () => {
        form.reset();
        $('#producto_id').val('');
        modalTitle.textContent = 'Agregar Producto';
    });


    $('#formularioProductos').on('submit', function (e) {
        e.preventDefault();
        // Validaciones front-end
        const codigo = $('#codigo').val().trim();
        const nombre = $('#nombre').val().trim();
        const precioCompraVal = $('#precio_compra').val();
        const precioVentaVal = $('#precio_venta').val();
        const stockVal = $('#stock').val();
        const estado = $('#estado').val();

        if (!codigo || !nombre || !precioCompraVal || !precioVentaVal || !stockVal || !estado) {
            alert('Todos los campos requeridos deben completarse');
            return;
        }

        const precioCompra = parseFloat(precioCompraVal);
        const precioVenta = parseFloat(precioVentaVal);
        const stock = parseInt(stockVal, 10);

        if (isNaN(precioCompra) || precioCompra <= 0) {
            alert('El precio de compra debe ser un número mayor a cero');
            return;
        }
        if (isNaN(precioVenta) || precioVenta <= 0) {
            alert('El precio de venta debe ser un número mayor a cero');
            return;
        }
        if (precioVenta < precioCompra * 1.10) {
            alert('El precio de venta debe ser al menos un 10% mayor al precio de compra');
            return;
        }
        if (isNaN(stock) || stock < 0) {
            alert('El stock debe ser un número entero igual o mayor a cero');
            return;
        }

        const datos = $(this).serialize();
        $.post('./api/procesar_producto.php', datos, function (respuesta) {

            let res;
            if (typeof respuesta === 'object') {
                res = respuesta;
            } else {
                try {
                    res = JSON.parse(respuesta);
                } catch (error) {
                    res = { status: 'error', message: respuesta };
                }
            }

            const alertType = res.status === 'success' ? 'success' : 'danger';
            const alerta = `
                <div class="alert alert-${alertType} alert-dismissible fade show" role="alert">
                    ${res.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>`;

            $('.modal-body').prepend(alerta);
            if (res.status === 'success') {
                setTimeout(() => {
                    modal.hide();
                    cargarProductos();
                }, 2000);
            }
        }, 'json');
    });

    // Eliminar producto
    $(document).on('click', '.btnEliminar', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        if (!confirm('¿Está seguro de eliminar este producto?')) return;
        $.get(`./api/procesar_producto.php?eliminar=${id}`, function (respuesta) {
            let res;
            if (typeof respuesta === 'object') {
                res = respuesta;
            } else {
                try {
                    res = JSON.parse(respuesta);
                } catch (error) {
                    res = { status: 'error', message: respuesta };
                }
            }
            alert(res.message);
            cargarProductos();
        }, 'json');
    });
});