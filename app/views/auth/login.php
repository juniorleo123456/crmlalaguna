<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRMLalaguna - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/styles.css" rel="stylesheet"> <!-- Tu CSS custom -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body class="bg-light">
    <div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img src="<?= BASE_URL ?>assets/img/logo-lalaguna.svg" alt="Logo CRMLalaguna" class="logo mb-3">
                        <h4 class="mb-0">CRMLalaguna</h4>
                        <small class="text-muted">Portal de Clientes y Gestión</small>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form id="loginForm" action="<?= BASE_URL ?>login" method="post">
                        <!-- Campo oculto con CSRF token -->
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" required placeholder="usuario@ejemplo.com"
                                   name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" required name="password">
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordarme</label>
                            </div>
                            <a href="#" class="small">¿Olvidaste tu contraseña?</a>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                    </form>

                    <hr>
                    <div class="text-center small text-muted">
                        Accede como:
                        <a href="<?= BASE_URL ?>dashboard?role=admin">Admin</a> |
                        <a href="<?= BASE_URL ?>dashboard?role=socio">Socio</a> |
                        <a href="<?= BASE_URL ?>dashboard?role=cliente">Cliente</a>
                    </div>
                </div>
            </div>

            <p class="text-center text-muted small mt-3">
                Desarrollado con MVC PHP – <code><?= BASE_URL ?></code>
            </p>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/app.js"></script> <!-- Si tienes JS custom -->
</body>

</html>