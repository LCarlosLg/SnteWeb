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
        <h4>Pago con tarjeta</h4>
        <form id="pagoForm" method="post" action="<?= base_url('checkout/confirmar') ?>">
            <div class="mb-3">
                <label for="tarjeta" class="form-label">Número de tarjeta</label>
                <input type="text" class="form-control" id="tarjeta" name="tarjeta" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del titular</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="exp" class="form-label">Fecha de expiración</label>
                <input type="month" class="form-control" id="exp" name="exp" required>
            </div>
            <div class="mb-3">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" required>
            </div>

            <!-- Se agregan los campos de dirección ocultos -->
            <input type="hidden" name="direccion" id="hiddenDireccion">
            <input type="hidden" name="colonia" id="hiddenColonia">
            <input type="hidden" name="cp" id="hiddenCp">

            <button type="submit" class="btn btn-success">Pagar y confirmar</button>
        </form>
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
