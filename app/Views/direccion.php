<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dirección de Envío</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url('images/icono.jpg') ?>" type="image/x-icon">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Datos de Envío</h4>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('checkout/guardarDireccion') ?>" method="post">
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label">Calle *</label>
                                <input type="text" name="calle" class="form-control" required placeholder="Ej. Av. Serdán">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Código Postal *</label>
                                <input type="text" name="cp" class="form-control" required placeholder="85400">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Número Exterior *</label>
                                <input type="text" name="numero_exterior" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Número Interior (Opcional)</label>
                                <input type="text" name="numero_interior" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Colonia *</label>
                            <input type="text" name="colonia" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ciudad y Estado *</label>
                            <input type="text" name="ciudad" class="form-control" value="Guaymas, Sonora" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Referencias / Entre calles</label>
                            <textarea name="referencias" class="form-control" rows="2" placeholder="Casa color azul, frente al parque..."></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">Continuar al Pago</button>
                            <a href="<?= base_url('productos') ?>" class="btn btn-link text-secondary">Cancelar y volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>