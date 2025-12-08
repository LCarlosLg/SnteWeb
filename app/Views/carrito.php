<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .step-section { display: none; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Tu carrito de compras</h2>

    <!-- Paso 0: Mostrar productos -->
    <div id="step-0">
        <?php if (!empty($productos)): ?>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $item): ?>
                        <tr>
                            <td><?= esc($item['nombre']) ?></td>
                            <td>$<?= number_format($item['precio'], 2) ?></td>
                            <td><?= $item['cantidad'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button class="btn btn-success mt-3" onclick="showStep(1)">Finalizar compra</button>
        <?php else: ?>
            <p>No tienes productos en el carrito.</p>
        <?php endif; ?>
    </div>

    <!-- Paso 1: Dirección -->
    <div id="step-1" class="step-section">
        <h4>Dirección de envío</h4>
        <form id="direccionForm">
            <div class="mb-3">
                <label for="direccion" class="form-label">Calle y número</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="mb-3">
                <label for="colonia" class="form-label">Colonia</label>
                <input type="text" class="form-control" id="colonia" name="colonia" required>
            </div>
            <div class="mb-3">
                <label for="cp" class="form-label">Código Postal</label>
                <input type="text" class="form-control" id="cp" name="cp" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="showStep(2)">Continuar</button>
        </form>
    </div>

    <!-- Paso 2: Pago -->
    <div id="step-2" class="step-section">
        <h4 class="mb-4">Finalizar Compra</h4>
        <div class="card p-4 shadow-sm text-center">
            <p class="mb-4">Serás redirigido a la plataforma segura de Stripe para completar tu pago.</p>
            
            <a href="<?= base_url('checkout/stripe') ?>" class="btn btn-primary btn-lg w-100">
                Pagar con Tarjeta <i class="bi bi-credit-card-2-front-fill ms-2"></i>
            </a>
            
            <small class="text-muted mt-3 d-block">Pagos seguros y encriptados</small>
        </div>
        
        <button type="button" class="btn btn-secondary mt-3" onclick="showStep(1)">Atrás</button>
    </div>
</div>

<script>
    function showStep(step) {
        document.querySelectorAll('.step-section').forEach(div => div.style.display = 'none');
        document.getElementById('step-0')?.style.display = 'none';

        if (step === 2) {
            // Transferir datos de dirección a formulario de pago
            document.getElementById('hiddenDireccion').value = document.getElementById('direccion').value;
            document.getElementById('hiddenColonia').value = document.getElementById('colonia').value;
            document.getElementById('hiddenCp').value = document.getElementById('cp').value;
        }

        const section = document.getElementById(`step-${step}`);
        if (section) section.style.display = 'block';
    }
</script>
</body>
</html>
