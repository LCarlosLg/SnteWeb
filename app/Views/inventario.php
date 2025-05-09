<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
    <h1>Gestión de Inventario</h1>

    <form action="<?= base_url('inventario/agregar') ?>" method="post" enctype="multipart/form-data" class="mb-5">
        <div class="row g-3">
            <div class="col-md-3"><input class="form-control" type="text" name="nombre" placeholder="Nombre" required></div>
            <div class="col-md-2"><input class="form-control" type="number" name="stock" placeholder="Stock" required></div>
            <div class="col-md-2"><input class="form-control" type="number" step="0.01" name="precio" placeholder="Precio" required></div>
            <div class="col-md-2">
                <select class="form-select" name="categoria" required>
                    <option value="">Categoría</option>
                    <option value="mueble">Mueble</option>
                    <option value="electrodomestico">Electrodoméstico</option>
                </select>
            </div>
            <div class="col-md-2"><input class="form-control" type="file" name="imagen" accept="image/*"></div>
            <div class="col-md-1"><button class="btn btn-success w-100" type="submit">Agregar</button></div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
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
            <?php foreach ($productos as $p): ?>
                <tr>
                    <td style="width: 100px;">
                        <?php if ($p['imagen']): ?>
                            <img src="<?= base_url('inventario/imagen/' . $p['id']) ?>" width="80" height="80" class="img-thumbnail">
                        <?php else: ?>
                            <span class="text-muted">Sin imagen</span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($p['nombre']) ?></td>
                    <td><?= esc($p['stock']) ?></td>
                    <td>$<?= number_format($p['precio'], 2) ?></td>
                    <td><?= esc($p['categoria']) ?></td>
                    <td>
                        <a href="<?= base_url('inventario/eliminar/' . $p['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
