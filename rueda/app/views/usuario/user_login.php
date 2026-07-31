<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../includes/CsrfService.php';
$hide_footer = true; // Ocultamos el footer genérico en esta página para evitar que se divida el degradado
$login_layout = true; // Indica que es la página de login para quitar padding del main
include __DIR__ . '/../layout/header.php';
?>

<div class="min-h-screen flex flex-col justify-between">
    <!-- Fila Superior: Formulario e Ilustración -->
    <div class="flex-grow flex flex-col md:flex-row min-h-[calc(100vh-4rem-8rem)]">
        <!-- Mitad Izquierda: Tarjeta de Login -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-6 bg-white">
            <div class="bg-white rounded-[2.5rem] shadow-[0_12px_40px_rgba(0,0,0,0.06)] border border-gray-100 p-10 max-w-sm w-full text-center space-y-6">
                
                <div>
                    <h2 class="text-2xl font-bold tracking-widest text-gray-800 uppercase inline-block border-b-2 border-gray-800 pb-1 mb-2">
                        INICIAR SESION
                    </h2>
                </div>

                <?php if(!empty($mensaje)): ?>
                    <div class="text-xs text-red-600 bg-red-50 p-2.5 rounded-lg border border-red-200 text-left">
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>

                <form class="space-y-5" action="index.php?controlador=usuario&accion=login" method="POST">
                    <?php echo CsrfService::getInputField('login'); ?>
                    
                    <!-- Campo Usuario (Correo) -->
                    <div class="text-left relative">
                        <label for="correo" class="block text-sm font-bold text-gray-700 ml-4 mb-1">Usuario</label>
                        <input id="correo" name="correo" type="email" required 
                               class="w-full px-5 py-2.5 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent transition duration-200 placeholder-gray-300 text-sm" 
                               placeholder="nombre@empresa.com">
                        <!-- Custom Error Container -->
                        <div id="correo-error" class="hidden mt-1.5 ml-4 flex items-center gap-1.5 text-xs font-semibold text-red-500 animate-slide-down">
                            <i class="fas fa-exclamation-circle text-xs"></i>
                            <span id="correo-error-text">Por favor, ingresa tu correo electrónico.</span>
                        </div>
                    </div>

                    <!-- Campo Contraseña -->
                    <div class="text-left">
                        <label for="password" class="block text-sm font-bold text-gray-700 ml-4 mb-1">Contraseña</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required 
                                   class="w-full px-5 py-2.5 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent transition duration-200 placeholder-gray-300 text-sm pr-20" 
                                   placeholder="********">
                            <!-- Iconos internos en el Input -->
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-2.5 text-gray-500">
                                <button type="button" onclick="togglePasswordVisibility()" class="hover:text-sky-500 focus:outline-none transition">
                                    <i id="eye-icon" class="fas fa-eye text-sm"></i>
                                </button>
                                <button type="submit" class="hover:text-sky-500 focus:outline-none transition">
                                    <i class="fas fa-arrow-right text-base"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Custom Error Container -->
                        <div id="password-error" class="hidden mt-1.5 ml-4 flex items-center gap-1.5 text-xs font-semibold text-red-500 animate-slide-down">
                            <i class="fas fa-exclamation-circle text-xs"></i>
                            <span id="password-error-text">Por favor, ingresa tu contraseña.</span>
                        </div>
                        <div class="text-center mt-2">
                            <a href="#" class="text-[11px] text-gray-500 hover:text-sky-500 hover:underline transition">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>

                    <!-- Botón Enviar -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-[#00a2ff] hover:bg-[#008fe5] text-white font-extrabold py-3 px-6 rounded-full shadow-[0_4px_15px_rgba(0,162,255,0.3)] hover:shadow-[0_6px_20px_rgba(0,162,255,0.4)] transition duration-200 transform hover:-translate-y-0.5 text-md">
                            Iniciar sesión
                        </button>
                    </div>
                </form>

                <!-- Registro -->
                <div class="pt-2 text-center space-y-1.5">
                    <p class="text-[11px] text-gray-400 font-medium">¿No tienes una cuenta?</p>
                    <a href="index.php?controlador=usuario&accion=registro" 
                       class="block text-sm font-black text-gray-800 hover:text-sky-500 hover:underline tracking-widest uppercase transition">
                        REGISTRATE AQUÍ
                    </a>
                </div>

            </div>
        </div>

        <!-- Mitad Derecha: Imagen Ilustrativa -->
        <div class="hidden md:flex md:w-1/2 bg-[#002e53] items-center justify-center p-8 relative overflow-hidden select-none">
            <img src="img/imagenagecso.png" alt="AGECSO" class="max-w-[90%] max-h-[80%] object-contain transition duration-500 hover:scale-102">
            <!-- Capa de tinte celeste ultra sutil para cohesionar el diseño -->
            <div class="absolute inset-0 bg-[#00a2ff]/5 mix-blend-multiply pointer-events-none"></div>
        </div>
    </div>

    <!-- Fila Inferior: Banner de Marca, Contacto y Servicios -->
    <div class="w-full bg-gradient-to-r from-[#00a2ff] via-[#dcf1ff] to-white py-4 px-6 md:px-12 flex flex-col sm:flex-row items-center justify-between border-t border-sky-200 gap-4">
        <!-- Logo y Nombre -->
        <div class="flex items-center gap-3">
            <img src="img/AGECSO.jpg" alt="AGECSO Logo" class="h-12 w-12 rounded-full border-2 border-white shadow-md object-cover">
            <span class="text-2xl font-black text-white tracking-widest drop-shadow-[0_1.5px_1.5px_rgba(0,0,0,0.15)] uppercase">
                AGECSO
            </span>
        </div>

        <!-- Derechos Reservados (Centrado en el degradado) -->
        <div class="hidden lg:block text-center text-sky-800 text-[11px] font-semibold leading-normal max-w-sm">
            <p>&copy; <?php echo date('Y'); ?> AGECSO - Software Rueda de Negocios. Todos los derechos reservados.</p>
            <p class="text-sky-700/80 mt-0.5 font-medium">Conectando la oferta y la demanda para fortalecer el relacionamiento.</p>
        </div>

        <!-- Enlaces Contacto y Servicios -->
        <div class="flex items-center gap-8 text-sky-800 font-bold tracking-widest text-xs mt-3 sm:mt-0">
            <a href="#" class="hover:text-sky-950 border-b-2 border-sky-800/80 pb-0.5 uppercase transition duration-200">
                CONTACTO
            </a>
            <a href="#" class="hover:text-sky-950 border-b-2 border-sky-800/80 pb-0.5 uppercase transition duration-200">
                SERVICIOS
            </a>
        </div>
    </div>
</div>

<!-- Estilos animados para la validación personalizada -->
<style>
@keyframes slideDownFade {
    0% {
        opacity: 0;
        transform: translateY(-8px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-slide-down {
    animation: slideDownFade 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>

<!-- Script interactivo para Toggle Password y Validación Personalizada -->
<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const correoInput = document.getElementById('correo');
    const passwordInput = document.getElementById('password');

    // Interceptar evento 'invalid' nativo para el Correo (Usuario)
    correoInput.addEventListener('invalid', function(e) {
        e.preventDefault(); // Previene la burbuja gris/naranja fea del navegador
        let msg = "Por favor, ingresa tu correo electrónico.";
        if (correoInput.value !== "") {
            msg = "Incluye un signo '@' en la dirección de correo electrónico (ej. nombre@empresa.com).";
        }
        showCustomError(correoInput, msg);
    });

    // Interceptar evento 'invalid' nativo para la Contraseña
    passwordInput.addEventListener('invalid', function(e) {
        e.preventDefault(); // Previene el tooltip por defecto del navegador
        showCustomError(passwordInput, "Por favor, ingresa tu contraseña para continuar.");
    });

    // Ocultar/limpiar errores dinámicamente cuando el usuario digita algo válido
    correoInput.addEventListener('input', function() {
        if (correoInput.validity.valid) {
            clearCustomError(correoInput);
        } else if (correoInput.value === "") {
            clearCustomError(correoInput);
        }
    });

    passwordInput.addEventListener('input', function() {
        if (passwordInput.value !== "") {
            clearCustomError(passwordInput);
        }
    });

    function showCustomError(input, text) {
        const errorContainer = document.getElementById(input.id + '-error');
        const errorText = document.getElementById(input.id + '-error-text');
        if (errorContainer && errorText) {
            errorText.textContent = text;
            errorContainer.classList.remove('hidden');
            // Dar estilo de borde e input de error
            input.classList.remove('border-gray-300', 'focus:ring-sky-400');
            input.classList.add('border-red-500', 'focus:ring-red-400', 'bg-red-50/10');
        }
    }

    function clearCustomError(input) {
        const errorContainer = document.getElementById(input.id + '-error');
        if (errorContainer) {
            errorContainer.classList.add('hidden');
            // Restaurar estilos normales
            input.classList.remove('border-red-500', 'focus:ring-red-400', 'bg-red-50/10');
            input.classList.add('border-gray-300', 'focus:ring-sky-400');
        }
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
