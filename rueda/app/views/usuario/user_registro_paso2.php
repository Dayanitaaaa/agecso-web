<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../includes/CsrfService.php';
include __DIR__ . '/../layout/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-gray-50 to-blue-50/30">
    <div class="max-w-xl w-full space-y-8 bg-white p-8 md:p-12 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-100 relative overflow-hidden">
        <!-- Decoración de fondo -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        
        <div class="relative">
            <div class="flex flex-col items-center">
                <div class="p-3 bg-white rounded-2xl shadow-sm border border-gray-50 mb-6 transition-transform hover:scale-105 duration-300">
                    <img src="img/LOGO AGECSO 2021.jpg" alt="AGECSO Logo" class="h-14 w-auto object-contain">
                </div>
                
                <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold tracking-wide uppercase mb-4">
                    <span class="flex h-2 w-2 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
                    Paso 2 de 2
                </div>
                
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Credenciales de Acceso</h2>
                <p class="mt-2 text-gray-500 font-medium text-center">Configura tu correo y contraseña para ingresar a la plataforma</p>
            </div>
        </div>
        
        <?php if(isset($mensaje)) echo $mensaje; ?>

        <form class="mt-10 space-y-6 relative" action="index.php?controlador=usuario&accion=registro" method="POST">
            <?php echo CsrfService::getInputField('registro_paso2'); ?>
            <input type="hidden" name="paso" value="2">
            
            <div class="space-y-6">
                <!-- Correo Electrónico -->
                <div class="form-group">
                    <label for="correo" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Correo Electrónico Corporativo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-envelope text-sm"></i>
                        </div>
                        <input id="correo" name="correo" type="email" required autocomplete="email" maxlength="254" pattern="^[^\s@]+@[^\s@]+\.[^\s@]{2,}$" 
                            class="block w-full pl-11 pr-4 py-4 border border-gray-200 rounded-2xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium" 
                            placeholder="ejemplo@empresa.com">
                    </div>
                    <p class="mt-2 text-[10px] text-gray-400 font-medium ml-1 flex items-center">
                        <i class="fas fa-info-circle mr-1 text-[8px]"></i>
                        Debe incluir un dominio válido (ej: .com, .org, .co)
                    </p>
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Contraseña de Acceso</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-lock text-sm"></i>
                        </div>
                        <input id="password" name="password" type="password" required minlength="6" 
                            class="block w-full pl-11 pr-4 py-4 border border-gray-200 rounded-2xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium" 
                            placeholder="••••••••">
                    </div>
                    <p class="mt-2 text-[10px] text-gray-400 font-medium ml-1">Mínimo 6 caracteres alfanuméricos.</p>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="form-group">
                    <label for="password_confirm" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Confirmar Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-shield-check text-sm"></i>
                        </div>
                        <input id="password_confirm" name="password_confirm" type="password" required minlength="6" 
                            class="block w-full pl-11 pr-4 py-4 border border-gray-200 rounded-2xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium" 
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- POLÍTICA DE TRATAMIENTO Y PROTECCIÓN DE DATOS PERSONALES -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 mb-2 ml-1">
                        <i class="fas fa-shield-alt text-blue-600 text-sm"></i>
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-800">Política de Tratamiento y Protección de Datos Personales</h3>
                    </div>

                    <!-- Contenedor scrollable con el texto completo de la política -->
                    <div class="bg-gray-50/90 border border-gray-200/80 rounded-2xl p-4 text-xs text-gray-600 leading-relaxed max-h-44 overflow-y-auto space-y-3 shadow-inner custom-scrollbar">
                        <p class="font-bold text-gray-800">
                            En AGECSO – Asociación Grupo de Empresarios y Comerciantes de Sabana de Occidente, estamos comprometidos con la protección y el uso responsable de los datos personales suministrados por empresarios, comerciantes, aliados y demás usuarios que se registren en nuestra plataforma.
                        </p>
                        
                        <div>
                            <p class="font-bold text-gray-700 mb-1">Los datos recopilados serán utilizados exclusivamente para:</p>
                            <ul class="list-disc pl-4 space-y-1 text-gray-600">
                                <li>Gestionar el registro y vinculación a AGECSO.</li>
                                <li>Informar sobre eventos, programas, beneficios, oportunidades comerciales y actividades de la Asociación.</li>
                                <li>Facilitar conexiones y relacionamiento empresarial, cuando exista autorización para ello.</li>
                                <li>Mantener actualizada nuestra base de datos y mejorar nuestros servicios.</li>
                            </ul>
                        </div>

                        <p>
                            AGECSO se compromete a proteger la información personal, utilizarla únicamente para las finalidades autorizadas y aplicar las medidas necesarias para evitar su pérdida, alteración, acceso o uso no autorizado.
                        </p>

                        <p>
                            Al registrarse en nuestra página web, el titular autoriza el tratamiento de sus datos personales de acuerdo con esta política y con la normativa colombiana vigente sobre protección de datos personales.
                        </p>

                        <p>
                            El titular podrá solicitar en cualquier momento la consulta, actualización, corrección o eliminación de sus datos, así como retirar su autorización cuando legalmente sea procedente.
                        </p>

                        <p>
                            Para ejercer sus derechos o realizar consultas relacionadas con el tratamiento de datos personales, puede comunicarse con AGECSO a través de los canales oficiales publicados en nuestra página web.
                        </p>

                        <p class="font-black text-blue-700 pt-1 border-t border-gray-200/60">
                            AGECSO – Conectamos los nodos del desarrollo económico.
                        </p>
                    </div>

                    <!-- Casilla de Autorización -->
                    <div class="mt-3.5 flex items-start gap-3 p-3 bg-blue-50/40 border border-blue-100 rounded-xl">
                        <div class="flex items-center h-5">
                            <input id="politica_datos" name="politica_datos" type="checkbox" required value="1"
                                class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer">
                        </div>
                        <label for="politica_datos" class="text-xs font-bold text-gray-700 cursor-pointer select-none leading-snug">
                            He leído y autorizo el tratamiento de mis datos personales de acuerdo con la <span class="text-blue-600 underline font-extrabold">Política de Protección de Datos de AGECSO</span>.
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center gap-4">
                <a href="index.php?controlador=usuario&accion=registro" class="w-full sm:w-auto px-6 py-4 text-sm font-black text-gray-400 hover:text-gray-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
                <button type="submit" class="group w-full relative flex justify-center py-4 px-8 border border-transparent text-sm font-black rounded-2xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-lg shadow-blue-500/20">
                    Finalizar Registro
                    <i class="fas fa-check-circle ml-2 transform group-hover:scale-110 transition-transform"></i>
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
    const politicaCheck = document.getElementById('politica_datos');

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
        return;
    }

    if (politicaCheck && !politicaCheck.checked) {
        e.preventDefault();
        alert('Debes aceptar la Política de Tratamiento y Protección de Datos Personales para registrarte.');
        politicaCheck.focus();
        return;
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
