<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?> | <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/login.css">
</head>
<body class="login-page">

    <div class="login-left">
        <div class="login-form-wrap">
            <div class="login-avatar">
                <img src="<?= APP_URL ?>/assets/img/AGECSO.jpg" alt="AGECSO">
            </div>

            <h1 class="login-title">Panel de Administración</h1>
            <p class="login-subtitle">Inicia sesión para continuar</p>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 px-3 mb-3" style="font-size: 0.85rem; border-radius: 10px;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="login-input-group">
                    <i class="bi bi-person input-icon"></i>
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                </div>
                <div class="login-input-group">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="login-btn">Iniciar sesión</button>
            </form>

            <div class="login-extras">
                <label class="d-flex align-items-center gap-1 text-muted" style="cursor: pointer;">
                    <input type="checkbox" class="form-check-input m-0" style="cursor: pointer;"> Recordarme
                </label>
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>

            <div class="login-back">
                <a href="<?= APP_URL ?>/?page=inicio"><i class="bi bi-arrow-left me-1"></i>Volver al sitio</a>
            </div>
        </div>
    </div>

    <div class="login-right">
        <div class="login-welcome">
            <h1>Bienvenido</h1>
            <p>Panel de Administración AGECSO<br>Gestión empresarial inteligente</p>
        </div>
    </div>

</body>
</html>
