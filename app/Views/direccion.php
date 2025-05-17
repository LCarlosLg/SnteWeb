<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dirección de Entrega</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>¿A qué domicilio te facturamos?</h2>
    <form action="<?= base_url('checkout/pago') ?>" method="post">
        <div class="row mb-3">
            <div class="col">
                <label>Calle</label>
                <input type="text" name="calle" class="form-control" required>
            </div>
            <div class="col">
                <label>Nº exterior</label>
                <input type="text" name="numero_exterior" class="form-control">
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" name="sin_numero" value="1">
                    <label class="form-check-label">Sin número</label>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label>Nº Interior / Depto (opcional)</label>
            <input type="text" name="numero_interior" class="form-control">
        </div>

        <div class="mb-3">
            <label>¿Entre qué calles está? (opcional)</label>
            <input type="text" name="entre_calles" class="form-control">
        </div>

        <div class="row mb-3">
            <div class="col">
                <label>Calle 1</label>
                <input type="text" name="calle1" class="form-control">
            </div>
            <div class="col">
                <label>Calle 2</label>
                <input type="text" name="calle2" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label>Indicaciones adicionales</label>
            <textarea name="indicaciones" class="form-control" placeholder="Descripción de la fachada, puntos de referencia..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Continuar</button>
    </form>
</div>
</body>
</html>
