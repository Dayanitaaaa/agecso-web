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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                    <div>
                        <label for="rol_id" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Perfil en la Rueda</label>
                        <select id="rol_id" name="rol_id" class="block w-full px-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium">
                            <option value="3" <?php echo ($regData['rol_id'] ?? '') == '3' ? 'selected' : ''; ?>>Comprador</option>
                            <option value="4" <?php echo ($regData['rol_id'] ?? '') == '4' ? 'selected' : ''; ?>>Vendedor / Proveedor</option>
                        </select>
                    </div>
                </div>

                <!-- CIIU con Buscador -->
                <div class="relative">
                    <label for="sector_busqueda" class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Actividad Económica (CIIU)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-search text-xs"></i>
                        </div>
                        <input type="text" id="sector_busqueda" placeholder="Buscar por código o nombre..." autocomplete="off" 
                            class="block w-full pl-9 pr-3 py-3 border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-medium"
                            value="<?php echo htmlspecialchars($regData['ciiu_clase'] ?? ''); ?>">
                    </div>
                    <input type="hidden" id="sector_id" name="sector_id" required 
                        value="<?php echo htmlspecialchars($regData['sector_id'] ?? ''); ?>">
                    
                    <div id="resultados_busqueda" class="absolute z-[100] w-full mt-1 bg-white border border-gray-100 rounded-2xl shadow-2xl max-h-60 overflow-y-auto hidden"></div>
                    
                    <div id="sector_seleccionado" class="hidden mt-3 p-4 bg-blue-50/50 border border-blue-100 rounded-2xl text-sm flex justify-between items-center animate-in fade-in slide-in-from-top-1 duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-blue-500 mr-3 text-lg"></i>
                            <div>
                                <p class="text-[10px] text-blue-600 font-bold uppercase tracking-wider mb-0.5">Actividad Seleccionada</p>
                                <span id="sector_texto" class="font-bold text-gray-800"></span>
                            </div>
                        </div>
                        <button type="button" id="limpiar_sector" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
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

const sectoresCIIU = <?php echo json_encode($sectores); ?>;
const busquedaInput = document.getElementById('sector_busqueda');
const resultadosDiv = document.getElementById('resultados_busqueda');
const sectorIdInput = document.getElementById('sector_id');
const sectorSeleccionadoDiv = document.getElementById('sector_seleccionado');
const sectorTextoSpan = document.getElementById('sector_texto');
const limpiarBtn = document.getElementById('limpiar_sector');

function mostrarSectores(query = '') {
    query = query.trim().toLowerCase();
    let filtrados = [];
    if (query === '') {
        // Mostrar los primeros 10 sectores por defecto
        filtrados = sectoresCIIU.slice(0, 10);
    } else {
        filtrados = sectoresCIIU.filter(s => 
            s.ciiu_clase.toLowerCase().includes(query) || 
            s.nombreSector.toLowerCase().includes(query)
        ).slice(0, 10);
    }
    
    if (filtrados.length === 0) {
        resultadosDiv.innerHTML = '<div class="p-3 text-gray-500 text-sm">No se encontraron resultados</div>';
    } else {
        resultadosDiv.innerHTML = filtrados.map(s => `
            <div class="p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 text-sm text-left" onclick="seleccionarSector(${s.id}, '${s.ciiu_clase}', '${s.nombreSector.replace(/'/g, "\\'")}')">
                <span class="font-bold text-blue-700">${s.ciiu_clase}</span> - ${s.nombreSector}
            </div>
        `).join('');
    }
    resultadosDiv.classList.remove('hidden');
}

busquedaInput.addEventListener('input', function() {
    mostrarSectores(this.value);
});

busquedaInput.addEventListener('focus', function() {
    mostrarSectores(this.value);
});

busquedaInput.addEventListener('click', function() {
    mostrarSectores(this.value);
});

function seleccionarSector(id, ciiu, nombre) {
    sectorIdInput.value = id;
    busquedaInput.classList.add('hidden');
    resultadosDiv.classList.add('hidden');
    sectorTextoSpan.innerHTML = `<b>${ciiu}</b> - ${nombre}`;
    sectorSeleccionadoDiv.classList.remove('hidden');
}

limpiarBtn.addEventListener('click', function() {
    sectorIdInput.value = '';
    busquedaInput.value = '';
    busquedaInput.classList.remove('hidden');
    sectorSeleccionadoDiv.classList.add('hidden');
    busquedaInput.focus();
});

document.addEventListener('click', e => { if (!busquedaInput.contains(e.target) && !resultadosDiv.contains(e.target)) resultadosDiv.classList.add('hidden'); });

// Precargar sector si hay datos en sesión
<?php if (!empty($regData['sector_id']) && !empty($regData['ciiu_clase'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    busquedaInput.classList.add('hidden');
    sectorTextoSpan.innerHTML = `<b><?php echo $regData['ciiu_clase']; ?></b> - <?php echo htmlspecialchars($regData['nombre_sector'] ?? ''); ?>`;
    sectorSeleccionadoDiv.classList.remove('hidden');
});
<?php endif; ?>
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
