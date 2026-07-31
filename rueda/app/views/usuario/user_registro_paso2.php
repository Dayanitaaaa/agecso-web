<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../includes/CsrfService.php';
include __DIR__ . '/../layout/header.php';
?>

<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
        <div>
            <img src="img/LOGO AGECSO 2021.jpg" alt="AGECSO Logo" class="mx-auto h-16 w-auto rounded-xl shadow-md border border-gray-100 object-contain bg-white mb-4 p-1.5 transition duration-300 hover:scale-105">
            <div class="flex items-center justify-center mb-4">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Paso 2 de 2</span>
            </div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">Credenciales de Acceso</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Configura tu correo y contraseña para ingresar</p>
        </div>
        
        <?php if(isset($mensaje)) echo $mensaje; ?>

        <form class="mt-8 space-y-6" action="index.php?controlador=usuario&accion=registro" method="POST">
            <?php echo CsrfService::getInputField('registro_paso2'); ?>
            <input type="hidden" name="paso" value="2">
            
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico Corporativo</label>
                    <input id="correo" name="correo" type="email" required autocomplete="email" maxlength="254" pattern="^[^\s@]+@[^\s@]+\.[^\s@]{2,}$" 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="ejemplo@empresa.com">
                    <p class="mt-1 text-[10px] text-gray-500">Ej: .com, .org, .co, etc (debe incluir dominio válido)</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña de Acceso</label>
                    <input id="password" name="password" type="password" required minlength="6" 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="********">
                    <p class="mt-1 text-[10px] text-gray-500">Mínimo 6 caracteres.</p>
                </div>

                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                    <input id="password_confirm" name="password_confirm" type="password" required minlength="6" 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="********">
                </div>
            </div>

            <div class="flex items-center justify-between space-x-4">
                <a href="index.php?controlador=usuario&accion=registro" class="text-sm text-gray-500 hover:text-gray-700 font-medium">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
                <button type="submit" class="flex-1 flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Finalizar Registro <i class="fas fa-check-circle ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const pass = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirm').value;
    const email = document.getElementById('correo').value.toLowerCase();

    if (pass.length < 6) {
        e.preventDefault();
        alert('La contraseña debe tener mínimo 6 caracteres.');
        return;
    }

    if (pass !== confirm) {
        e.preventDefault();
        alert('Las contraseñas no coinciden.');
        return;
    }

    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email);
    if (!emailOk) {
        e.preventDefault();
        alert('Ingresa un correo válido (ej: usuario@empresa.com)');
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
