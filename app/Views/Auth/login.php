<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Agregar favicon -->
    <link rel="icon" href="<?= base_url('images/icono.jpg') ?>" type="image/x-icon">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="text-center mb-4">

                <!-- Logo más grande (Ajustado a 350px de ancho) -->
                <img src="<?= base_url('images/logo.png') ?>" alt="Logo" class="img-fluid" style="max-width: 350px;">
            </div>
            <div class="card">
                <div class="card-header text-center">Iniciar Sesión</div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('usuarios/attemptLogin') ?>" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                    </form>
                    <div class="mt-3 text-center">
                        <span>¿No tienes cuenta?</span> 
                        <a href="<?= base_url('register') ?>">Regístrate aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", function() {
        const type = password.type === "password" ? "text" : "password";
        password.type = type;
        this.classList.toggle("bi-eye");
        this.classList.toggle("bi-eye-slash");
    });
</script>

</body>
</html>