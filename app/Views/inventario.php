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

    <!-- Barra superior -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 p-3 rounded shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Inventario</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarContenido">
                <form class="d-flex my-2 my-lg-0 w-100 me-3" method="get" action="<?= base_url('inventario') ?>">
                    <input class="form-control me-2" name="buscar" type="search" placeholder="Buscar producto..." aria-label="Buscar">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
                <div class="d-flex flex-wrap gap-2 mt-2 mt-lg-0">
                    <a href="<?= base_url('inventario/reporte') ?>" class="btn btn-outline-primary">Reporte PDF</a>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mensajes -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= esc($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tabla de productos sin fecha -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-2 mb-md-0">Lista de productos</h5>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalProducto" onclick="nuevoProducto()">Agregar producto</button>
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
                                <td style="width: 100px;">
                                    <img src="<?= base_url('inventario/imagen/' . $producto['id']) ?>" alt="Imagen" class="img-thumbnail" width="60">
                                </td>
                                <td><?= esc($producto['nombre']) ?></td>
                                <td><?= esc($producto['stock']) ?></td>
                                <td>$<?= esc($producto['precio']) ?></td>
                                <td><?= esc($producto['categoria']) ?></td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <button class="btn btn-warning btn-sm" onclick="editarProducto(<?= htmlspecialchars(json_encode($producto), ENT_QUOTES, 'UTF-8') ?>)">Editar</button>
                                        <a href="<?= base_url('inventario/eliminar/' . $producto['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No hay productos registrados.</td></tr>
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
    <form action="<?= base_url('inventario/agregar') ?>" method="post" enctype="multipart/form-data" id="formProducto">
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
                <input type="number" name="precio" step="0.01" class="form-control" id="productoPrecio" required>
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
                <input type="file" name="imagen" class="form-control">
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

