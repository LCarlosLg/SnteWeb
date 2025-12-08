<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inventario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url('images/icono.jpg') ?>" type="image/x-icon" />
</head>

<body class="bg-light">

    <div class="container-fluid mt-3 px-3">

    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 p-3 rounded shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Panel Administrativo</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarContenido">
                 <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="inventario-tab" data-bs-toggle="tab" data-bs-target="#inventario" type="button" role="tab">📦 Inventario</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pedidos-tab" data-bs-toggle="tab" data-bs-target="#pedidos" type="button" role="tab">🛒 Pedidos Realizados</button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        
        <div class="tab-pane fade show active" id="inventario" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestión de Productos</h5>
                    <div>
                        <form class="d-inline-block me-2" method="get" action="<?= base_url('inventario') ?>">
                            <input class="form-control form-control-sm d-inline-block w-auto" name="buscar" placeholder="Buscar..." value="<?= esc($buscar) ?>">
                        </form>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalProducto" onclick="nuevoProducto()">+ Agregar</button>
                        <a href="<?= base_url('inventario/reporte') ?>" class="btn btn-primary btn-sm">PDF</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Stock</th>
                                <th>Precio</th>
                                <th>Categoría</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($productos)): ?>
                                <?php foreach ($productos as $producto): ?>
                                    <tr>
                                        <td>
                                            <?php if ($producto['imagen']): ?>
                                                <img src="<?= base_url('inventario/mostrarImagen/' . $producto['imagen']) ?>" width="50" class="img-thumbnail">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($producto['nombre']) ?></td>
                                        <td><?= esc($producto['stock']) ?></td>
                                        <td>$<?= esc($producto['precio']) ?></td>
                                        <td><?= esc($producto['categoria']) ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" onclick="editarProducto(<?= htmlspecialchars(json_encode($producto), ENT_QUOTES, 'UTF-8') ?>)">Editar</button>
                                            <a href="<?= base_url('inventario/eliminar/' . $producto['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Borrar?')">X</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center">Sin productos.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pedidos" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary">Historial de Ventas</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Pedido</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Dirección de Envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pedidos)): ?>
                                <?php foreach ($pedidos as $pedido): ?>
                                    <?php 
                                        // Decodificamos el JSON de la dirección para mostrarlo bonito
                                        $dir = json_decode($pedido['direccion'], true); 
                                        $textoDireccion = is_array($dir) 
                                            ? ($dir['calle'] ?? '') . ' ' . ($dir['numero_exterior'] ?? '') . ', ' . ($dir['colonia'] ?? '') . '. ' . ($dir['ciudad'] ?? '')
                                            : 'Dirección no válida';
                                    ?>
                                    <tr>
                                        <td class="fw-bold">#<?= $pedido['id'] ?></td>
                                        <td>
                                            <?= esc($pedido['nombres'] . ' ' . $pedido['apellidos']) ?><br>
                                            <small class="text-muted"><?= esc($pedido['email']) ?></small>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></td>
                                        <td>
                                            <span class="badge bg-success"><?= esc($pedido['estado']) ?></span>
                                        </td>
                                        <td style="max-width: 300px;">
                                            <small><?= esc($textoDireccion) ?></small>
                                            <?php if(isset($dir['referencias']) && $dir['referencias']): ?>
                                                <br><small class="text-muted fst-italic">Ref: <?= esc($dir['referencias']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-4">No hay pedidos registrados aún.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <!-- Modal Agregar/Editar Producto -->
    <div class="modal fade" id="modalProducto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= base_url('inventario/agregar') ?>" method="post" enctype="multipart/form-data"
                    id="formProducto">
                    <input type="hidden" name="id" id="productoId">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Agregar/Editar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" id="productoNombre" required>
                        </div>
                        <div class="mb-3">
                            <label>Stock</label>
                            <input type="number" name="stock" class="form-control" id="productoStock" required>
                        </div>
                        <div class="mb-3">
                            <label>Precio</label>
                            <input type="number" name="precio" step="0.01" class="form-control" id="productoPrecio"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>Categoría</label>
                            <select name="categoria" class="form-select" id="productoCategoria" required>
                                <option value="">Seleccionar...</option>
                                <option value="Mueble">Mueble</option>
                                <option value="Electrodoméstico">Electrodoméstico</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Imagen</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Si quieres cambiar la imagen, selecciona un archivo
                                nuevo.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function nuevoProducto() {
            document.getElementById('formProducto').action = "<?= base_url('inventario/agregar') ?>";
            document.getElementById('modalLabel').textContent = 'Agregar Producto';
            document.getElementById('productoId').value = '';
            document.getElementById('productoNombre').value = '';
            document.getElementById('productoStock').value = '';
            document.getElementById('productoPrecio').value = '';
            document.getElementById('productoCategoria').value = '';
        }

        function editarProducto(producto) {
            document.getElementById('formProducto').action = `<?= base_url('inventario/actualizar') ?>/${producto.id}`;
            document.getElementById('modalLabel').textContent = 'Editar Producto';
            document.getElementById('productoId').value = producto.id;
            document.getElementById('productoNombre').value = producto.nombre;
            document.getElementById('productoStock').value = producto.stock;
            document.getElementById('productoPrecio').value = producto.precio;
            document.getElementById('productoCategoria').value = producto.categoria;
            new bootstrap.Modal(document.getElementById('modalProducto')).show();
        }
    </script>

</body>

</html>