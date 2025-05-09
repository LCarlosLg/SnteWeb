<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <!-- Barra superior -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 px-4 rounded shadow-sm">
        <a class="navbar-brand fw-bold" href="#">Inventario</a>
        <div class="collapse navbar-collapse justify-content-between">
            <form class="d-flex" method="get" action="<?= base_url('inventario') ?>">
                <input class="form-control me-2" name="buscar" type="search" placeholder="Buscar producto..." aria-label="Buscar">
                <button class="btn btn-outline-success" type="submit">Buscar</button>
            </form>
            <div class="d-flex gap-2">
                <a href="<?= base_url('inventario/reporte') ?>" class="btn btn-outline-primary">Descargar Reporte PDF</a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <!-- Mensajes de alerta -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= esc($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <!-- Tabla de productos -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de productos</h5>
            <a href="<?= base_url('inventario/agregar') ?>" class="btn btn-sm btn-success">Agregar producto</a>
        </div>
        <div class="card-body p-0">
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
                                    <a href="<?= base_url('inventario/editar/' . $producto['id']) ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <a href="<?= base_url('inventario/eliminar/' . $producto['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay productos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
