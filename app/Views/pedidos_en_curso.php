<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos en curso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Pedidos en curso</h2>
    <?php if (!empty($pedidos)): ?>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Dirección</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= $pedido['id'] ?></td>
                        <td><?= $pedido['fecha_pedido'] ?></td>
                        <td><?= $pedido['estado'] ?></td>
                        <td>
                            <?php
                                $direccion = json_decode($pedido['direccion'], true);
                                foreach ($direccion as $campo => $valor) {
                                    echo "<strong>" . ucfirst(str_replace('_', ' ', $campo)) . ":</strong> $valor<br>";
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tienes pedidos en curso.</p>
    <?php endif; ?>
    <a href="<?= base_url('productos') ?>" class="btn btn-primary mt-3">Volver a la tienda</a>
</div>
</body>
</html>
