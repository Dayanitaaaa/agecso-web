<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../includes/CsrfService.php';
include __DIR__ . '/../layout/header.php';
// Recuperar datos de sesión si existen
$regData = $_SESSION['reg_data'] ?? [];
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
                    Paso 1 de 2
                </div>
                
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Registro de Empresa</h2>
                <p class="mt-2 text-gray-500 font-medium">Información legal y normativa para la Rueda</p>
            </div>
        </div>
        
        <?php if(isset($mensaje)) echo $mensaje; ?>

        <form class="mt-10 space-y-6 relative" action="index.php?controlador=usuario&accion=registro" method="POST">
            <?php echo CsrfService::getInputField('registro_paso1'); ?>
            <input type="hidden" name="paso" value="1">
            
            <div class="space-y-6">
                <!-- Selector de Perfil en la Rueda (Vendedor vs Comprador) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">¿Cómo deseas participar en la Rueda?</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="group relative flex flex-col items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-amber-300 hover:bg-amber-50/30 transition-all duration-200 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/70 has-[:checked]:shadow-sm">
                            <input type="radio" name="rol_id_radio" value="3" class="hidden" onchange="seleccionarRol(3)" <?php echo ($regData['rol_id'] ?? '3') == '3' ? 'checked' : ''; ?>>
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 group-hover:bg-amber-100/50 text-gray-400 group-has-[:checked]:text-amber-600 group-has-[:checked]:bg-amber-100 transition-colors mb-2">
                                <i class="fas fa-store text-xl"></i>
                            </div>
                            <span class="text-xs font-black uppercase tracking-wider text-gray-700 group-has-[:checked]:text-amber-800">Vendedor / Proveedor</span>
                            <span class="text-[10px] text-gray-400 text-center mt-0.5">Ofrecer mis productos y servicios</span>
                        </label>

                        <label class="group relative flex flex-col items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-sky-300 hover:bg-sky-50/30 transition-all duration-200 has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50/70 has-[:checked]:shadow-sm">
                            <input type="radio" name="rol_id_radio" value="4" class="hidden" onchange="seleccionarRol(4)" <?php echo ($regData['rol_id'] ?? '') == '4' ? 'checked' : ''; ?>>
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 group-hover:bg-sky-100/50 text-gray-400 group-has-[:checked]:text-sky-600 group-has-[:checked]:bg-sky-100 transition-colors mb-2">
                                <i class="fas fa-shopping-bag text-xl"></i>
                            </div>
                            <span class="text-xs font-black uppercase tracking-wider text-gray-700 group-has-[:checked]:text-sky-800">Comprador</span>
                            <span class="text-[10px] text-gray-400 text-center mt-0.5">Buscar y cotizar con proveedores</span>
                        </label>
                    </div>
                    <input type="hidden" name="rol_id" id="rol_id" value="<?php echo htmlspecialchars($regData['rol_id'] ?? '3'); ?>">
                </div>

                <!-- Selector de Tipo de Persona -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3 ml-1">Tipo de Persona</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="group relative flex flex-col items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-blue-200 hover:bg-blue-50/30 transition-all duration-200 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:shadow-sm">
                            <input type="radio" name="tipo_persona" value="natural" class="hidden" required onchange="toggleFields('natural')" <?php echo ($regData['tipo_persona'] ?? '') == 'natural' ? 'checked' : ''; ?>>
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 group-hover:bg-blue-100/50 text-gray-400 group-has-[:checked]:text-blue-600 group-has-[:checked]:bg-blue-100 transition-colors mb-2">
                                <i class="fas fa-user text-xl"></i>
                            </div>
                            <span class="text-[11px] font-black uppercase tracking-wider text-gray-500 group-has-[:checked]:text-blue-700">Natural</span>
                        </label>

                        <label class="group relative flex flex-col items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-blue-200 hover:bg-blue-50/30 transition-all duration-200 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:shadow-sm">
                            <input type="radio" name="tipo_persona" value="juridica" class="hidden" onchange="toggleFields('juridica')" <?php echo ($regData['tipo_persona'] ?? 'juridica') == 'juridica' ? 'checked' : ''; ?>>
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 group-hover:bg-blue-100/50 text-gray-400 group-has-[:checked]:text-blue-600 group-has-[:checked]:bg-blue-100 transition-colors mb-2">
                                <i class="fas fa-building text-xl"></i>
                            </div>
                            <span class="text-[11px] font-black uppercase tracking-wider text-gray-500 group-has-[:checked]:text-blue-700">Jurídica</span>
                        </label>

                        <label class="group relative flex flex-col items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-blue-200 hover:bg-blue-50/30 transition-all duration-200 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:shadow-sm">
                            <input type="radio" name="tipo_persona" value="esal_otro" class="hidden" onchange="toggleFields('esal_otro')" <?php echo ($regData['tipo_persona'] ?? '') == 'esal_otro' ? 'checked' : ''; ?>>
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 group-hover:bg-blue-100/50 text-gray-400 group-has-[:checked]:text-blue-600 group-has-[:checked]:bg-blue-100 transition-colors mb-2">
                                <i class="fas fa-hand-holding-heart text-xl"></i>
                            </div>
                            <span class="text-[11px] font-black uppercase tracking-wider text-gray-500 group-has-[:checked]:text-blue-700">ESAL / Otros</span>
                        </label>
                    </div>
                </div>

                <!-- Campos de Razón Social y Forma Jurídica -->
                <div class="grid grid-cols-1 gap-5">
                    <div class="form-group">
                        <label for="razon_social" id="label_razon_social" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Razón Social</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-signature text-xs"></i>
                            </div>
                            <input id="razon_social" name="razon_social" type="text" required 
                                value="<?php echo htmlspecialchars($regData['razon_social'] ?? ''); ?>"
                                class="block w-full pl-9 pr-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium" 
                                placeholder="Ej: Tech Innovators S.A.S.">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tipo_asociacion" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Forma Jurídica</label>
                            <select id="tipo_asociacion" name="tipo_asociacion" class="block w-full px-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium" onchange="updateSubTypes()">
                                <!-- Se llena dinámicamente con JS -->
                            </select>
                        </div>
                        <div id="container_sub_tipo" class="hidden">
                            <label for="sub_tipo_asociacion" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Sub-tipo / Detalle</label>
                            <select id="sub_tipo_asociacion" name="sub_tipo_asociacion" class="block w-full px-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium">
                                <!-- Se llena dinámicamente con JS -->
                            </select>
                        </div>
                    </div>
                </div>

                <!-- NIT y Responsabilidad IVA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="nit" id="label_nit" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">NIT / Identificación</label>
                        <div class="flex gap-2">
                            <input id="nit" name="nit" type="text" required inputmode="numeric" autocomplete="off" maxlength="10" pattern="\d{1,10}" 
                                value="<?php echo htmlspecialchars($regData['nit'] ?? ''); ?>"
                                class="block w-full px-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium" 
                                placeholder="900000000" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)">
                            <input id="digito_verificacion" name="digito_verificacion" type="text" inputmode="numeric" autocomplete="off" maxlength="1" pattern="\d{1}"
                                value="<?php echo htmlspecialchars($regData['digito_verificacion'] ?? ''); ?>"
                                class="w-14 px-2 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-bold text-center" 
                                placeholder="DV" oninput="this.value=this.value.replace(/\D/g,'').slice(0,1)">
                        </div>
                    </div>
                    <div>
                        <label for="responsable_iva" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Responsabilidad IVA</label>
                        <select id="responsable_iva" name="responsable_iva" class="block w-full px-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium">
                            <option value="1" <?php echo ($regData['responsable_iva'] ?? '') == '1' ? 'selected' : ''; ?>>Responsable de IVA</option>
                            <option value="0" <?php echo ($regData['responsable_iva'] ?? '0') == '0' ? 'selected' : ''; ?>>No Responsable</option>
                        </select>
                    </div>
                </div>

                <!-- Tamaño Empresa y Ubicación -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="tamaño_empresa" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Tamaño de Empresa</label>
                        <select id="tamaño_empresa" name="tamaño_empresa" class="block w-full px-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium">
                            <option value="micro" <?php echo ($regData['tamaño_empresa'] ?? '') == 'micro' ? 'selected' : ''; ?>>Microempresa</option>
                            <option value="pequeña" <?php echo ($regData['tamaño_empresa'] ?? '') == 'pequeña' ? 'selected' : ''; ?>>Pequeña Empresa</option>
                            <option value="mediana" <?php echo ($regData['tamaño_empresa'] ?? '') == 'mediana' ? 'selected' : ''; ?>>Mediana Empresa</option>
                            <option value="grande" <?php echo ($regData['tamaño_empresa'] ?? '') == 'grande' ? 'selected' : ''; ?>>Gran Empresa</option>
                        </select>
                    </div>
                    <div>
                        <label for="ubicacion_geografica" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Ubicación</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-location-dot text-xs"></i>
                            </div>
                            <input id="ubicacion_geografica" name="ubicacion_geografica" type="text" required 
                                value="<?php echo htmlspecialchars($regData['ubicacion_geografica'] ?? ''); ?>"
                                class="block w-full pl-9 pr-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium" 
                                placeholder="Ej: Bogotá, Colombia">
                        </div>
                    </div>
                </div>

                <!-- Representante y Perfil -->
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label for="representante_legal" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Representante Legal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-user-tie text-xs"></i>
                            </div>
                            <input id="representante_legal" name="representante_legal" type="text" required 
                                value="<?php echo htmlspecialchars($regData['representante_legal'] ?? ''); ?>"
                                class="block w-full pl-9 pr-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium" 
                                placeholder="Nombre completo">
                        </div>
                    </div>
                </div>

                <!-- CIIU Personalizado -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="relative">
                        <label for="ciiu_personalizado" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Código CIIU</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-barcode text-xs"></i>
                            </div>
                            <input type="text" id="ciiu_personalizado" name="ciiu_personalizado" required 
                                value="<?php echo htmlspecialchars($regData['ciiu_personalizado'] ?? ''); ?>"
                                class="block w-full pl-9 pr-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium"
                                placeholder="Ej: 6201">
                        </div>
                    </div>
                    <div class="relative">
                        <label for="ciiu_nombre_personalizado" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Nombre de la Actividad</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-info-circle text-xs"></i>
                            </div>
                            <input type="text" id="ciiu_nombre_personalizado" name="ciiu_nombre_personalizado" required 
                                value="<?php echo htmlspecialchars($regData['ciiu_nombre_personalizado'] ?? ''); ?>"
                                class="block w-full pl-9 pr-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium"
                                placeholder="Ej: Actividades de programación informática">
                        </div>
                    </div>
                </div>
                <p class="mt-1.5 text-[10px] text-gray-400 ml-1">Ingresa el código y nombre de tu actividad económica principal según el RUT.</p>
            </div>

            <div class="pt-6">
                <button type="submit" class="group w-full relative flex justify-center py-4 px-4 border border-transparent text-sm font-black rounded-2xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-lg shadow-blue-500/20">
                    Siguiente: Verificar Información 
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const asociaciones = {
    juridica: [
        { val: 'S.A.S.', label: 'S.A.S. (Sociedad por Acciones Simplificada)' },
        { val: 'S.A.', label: 'S.A. (Sociedad Anónima)' },
        { val: 'Limitada', label: 'Limitada (Ltda.)' },
        { val: 'Comandita', label: 'Sociedad en Comandita' },
        { val: 'E.U.', label: 'E.U. (Empresa Unipersonal)' },
        { val: 'Colectiva', label: 'Sociedad Colectiva' },
        { val: 'Extranjera', label: 'Sucursal de Sociedad Extranjera' }
    ],
    natural: [
        { val: 'Comerciante', label: 'Persona Natural Comerciante' },
        { val: 'No Comerciante', label: 'Persona Natural no Comerciante (Freelance)' },
        { val: 'Regimen Simple', label: 'Régimen Simple (Persona Natural)' }
    ],
    esal_otro: [
        { val: 'Asociacion', label: 'Asociación / Corporación' },
        { val: 'Fundacion', label: 'Fundación' },
        { val: 'Cooperativa', label: 'Cooperativa / Precooperativa' },
        { val: 'Fondo', label: 'Fondo de Empleados' },
        { val: 'Publica', label: 'Entidad Pública' }
    ]
};

const subTipos = {
    'Comandita': [
        { val: 'Simple', label: 'Comandita Simple (S. en C.)' },
        { val: 'Acciones', label: 'Comandita por Acciones (S. en C. por A.)' }
    ]
};

function toggleFields(tipo) {
    const labelRazon = document.getElementById('label_razon_social');
    const inputRazon = document.getElementById('razon_social');
    const labelNit = document.getElementById('label_nit');
    const inputNit = document.getElementById('nit');
    const selectTipo = document.getElementById('tipo_asociacion');

    if (tipo === 'natural') {
        labelRazon.innerText = 'Nombre Completo';
        inputRazon.placeholder = 'Ej: Juan Pérez';
        labelNit.innerText = 'Cédula de Ciudadanía / NIT';
    } else {
        labelRazon.innerText = 'Razón Social';
        inputRazon.placeholder = 'Ej: Tech Innovators S.A.S.';
        labelNit.innerText = 'NIT / Identificación Fiscal';
    }

    // Actualizar select de asociaciones
    selectTipo.innerHTML = asociaciones[tipo].map(a => `<option value="${a.val}">${a.label}</option>`).join('');
    updateSubTypes();
}

function updateSubTypes() {
    const tipo = document.getElementById('tipo_asociacion').value;
    const container = document.getElementById('container_sub_tipo');
    const selectSub = document.getElementById('sub_tipo_asociacion');

    if (subTipos[tipo]) {
        container.classList.remove('hidden');
        selectSub.innerHTML = subTipos[tipo].map(s => `<option value="${s.val}">${s.label}</option>`).join('');
        selectSub.required = true;
    } else {
        container.classList.add('hidden');
        selectSub.innerHTML = '';
        selectSub.required = false;
    }
}

// Inicializar campos según valor previo
document.addEventListener('DOMContentLoaded', () => {
    const tipoChecked = document.querySelector('input[name="tipo_persona"]:checked');
    if (tipoChecked) toggleFields(tipoChecked.value);
    else toggleFields('juridica'); // Default
});

function seleccionarRol(id) {
    const el = document.getElementById('rol_id');
    if (el) el.value = id;
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
