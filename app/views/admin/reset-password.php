<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Contraseña | <?= APP_NAME ?></title>
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

            <h1 class="login-title">Nueva Contraseña</h1>
            <p class="login-subtitle">Ingresa tu nueva contraseña de acceso</p>

            <?php if (isset($error) && $error): ?>
                <div class="alert alert-danger py-2 px-3 mb-3" style="font-size: 0.85rem; border-radius: 10px;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="login-input-group">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="Nueva contraseña" required minlength="6">
                </div>
                <div class="login-input-group">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirmar contraseña" required minlength="6">
                </div>
                <button type="submit" class="login-btn">Actualizar contraseña</button>
            </form>

            <div class="login-back">
                <a href="<?= APP_URL ?>/?page=login"><i class="bi bi-arrow-left me-1"></i>Cancelar</a>
            </div>
        </div>
    </div>

    <div class="login-right">
        <div class="login-welcome">
            <h1>Seguridad</h1>
            <p>Define una contraseña segura para proteger tu cuenta</p>
        </div>
    </div>

</body>
</html>
