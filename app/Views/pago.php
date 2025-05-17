<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago con Tarjeta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Pago con Tarjeta</h2>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <form action="<?= base_url('checkout/completarPago') ?>" method="post">
        <div class="mb-3">
            <label>Número de Tarjeta</label>
            <input type="text" name="tarjeta" class="form-control" required maxlength="16">
        </div>
        <div class="mb-3">
            <label>Nombre en la Tarjeta</label>
            <input type="text" name="nombre_tarjeta" class="form-control" required>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Fecha de Expiración</label>
                <input type="text" name="expiracion" class="form-control" placeholder="MM/AA" required>
            </div>
            <div class="col">
                <label>CVV</label>
                <input type="text" name="cvv" class="form-control" required maxlength="4">
            </div>
        </div>

        <button type="submit" class="btn btn-success">Finalizar compra</button>
    </form>
</div>
</body>
</html>
