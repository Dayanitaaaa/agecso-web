<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - Rueda de Negocios AGECSO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <img src="https://agecso.org/assets/img/AGECSO.jpg" alt="AGECSO" class="h-16 mx-auto mb-4 rounded">
            <h1 class="text-2xl font-bold text-gray-800">Nueva Contraseña</h1>
            <p class="text-gray-600 mt-2">Establece tu nueva contraseña de acceso</p>
        </div>

        <?= $mensaje ?>

        <form action="index.php?controlador=usuario&accion=resetPassword&token=<?= htmlspecialchars($_GET['token'] ?? '') ?>" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" required minlength="6"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Mínimo 6 caracteres">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-check-double"></i>
                    </span>
                    <input type="password" name="confirm_password" required minlength="6"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Repite la contraseña">
                </div>
            </div>

            <button type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Actualizar Contraseña
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="index.php?controlador=usuario&accion=login" class="text-sm text-blue-600 hover:text-blue-500">
                Cancelar y volver
            </a>
        </div>
    </div>
</body>
</html>
