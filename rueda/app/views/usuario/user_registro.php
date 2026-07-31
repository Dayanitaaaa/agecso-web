<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../includes/CsrfService.php';
include __DIR__ . '/../layout/header.php';
// Recuperar datos de sesión si existen
$regData = $_SESSION['reg_data'] ?? [];
?>

<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
        <div>
            <img src="img/LOGO AGECSO 2021.jpg" alt="AGECSO Logo" class="mx-auto h-16 w-auto rounded-xl shadow-md border border-gray-100 object-contain bg-white mb-4 p-1.5 transition duration-300 hover:scale-105">
            <div class="flex items-center justify-center mb-4">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Paso 1 de 2</span>
            </div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">Registro de Empresa</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Información legal y normativa</p>
        </div>
        
        <?php if(isset($mensaje)) echo $mensaje; ?>

        <form class="mt-8 space-y-4" action="index.php?controlador=usuario&accion=registro" method="POST">
            <?php echo CsrfService::getInputField('registro_paso1'); ?>
            <input type="hidden" name="paso" value="1">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Persona</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="relative flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="tipo_persona" value="natural" class="hidden" required onchange="toggleFields('natural')" <?php echo ($regData['tipo_persona'] ?? '') == 'natural' ? 'checked' : ''; ?>>
                            <i class="fas fa-user text-lg mb-1"></i>
                            <span class="text-[10px] font-bold uppercase">Natural</span>
                        </label>
                        <label class="relative flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="tipo_persona" value="juridica" class="hidden" onchange="toggleFields('juridica')" <?php echo ($regData['tipo_persona'] ?? 'juridica') == 'juridica' ? 'checked' : ''; ?>>
                            <i class="fas fa-building text-lg mb-1"></i>
                            <span class="text-[10px] font-bold uppercase">Jurídica</span>
                        </label>
                        <label class="relative flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="tipo_persona" value="esal_otro" class="hidden" onchange="toggleFields('esal_otro')" <?php echo ($regData['tipo_persona'] ?? '') == 'esal_otro' ? 'checked' : ''; ?>>
                            <i class="fas fa-hand-holding-heart text-lg mb-1"></i>
                            <span class="text-[10px] font-bold uppercase">ESAL / Otros</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="razon_social" id="label_razon_social" class="block text-sm font-medium text-gray-700">Razón Social</label>
                    <input id="razon_social" name="razon_social" type="text" required 
                        value="<?php echo htmlspecialchars($regData['razon_social'] ?? ''); ?>"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="Ej: Tech Innovators S.A.S.">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tipo_asociacion" class="block text-sm font-medium text-gray-700">Forma Jurídica / Tipo</label>
                        <select id="tipo_asociacion" name="tipo_asociacion" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" onchange="updateSubTypes()">
                            <!-- Se llena dinámicamente con JS -->
                        </select>
                    </div>
                    <div id="container_sub_tipo" class="hidden">
                        <label for="sub_tipo_asociacion" class="block text-sm font-medium text-gray-700">Sub-tipo / Detalle</label>
                        <select id="sub_tipo_asociacion" name="sub_tipo_asociacion" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <!-- Se llena dinámicamente con JS -->
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nit" id="label_nit" class="block text-sm font-medium text-gray-700">NIT / Identificación</label>
                        <div class="flex gap-2">
                            <input id="nit" name="nit" type="text" required inputmode="numeric" autocomplete="off" maxlength="10" pattern="\d{1,10}" 
                                value="<?php echo htmlspecialchars($regData['nit'] ?? ''); ?>"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder="900000000" oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)">
                            <input id="digito_verificacion" name="digito_verificacion" type="text" inputmode="numeric" autocomplete="off" maxlength="1" pattern="\d{1}"
                                value="<?php echo htmlspecialchars($regData['digito_verificacion'] ?? ''); ?>"
                                class="mt-1 w-12 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-center" 
                                placeholder="DV" oninput="this.value=this.value.replace(/\D/g,'').slice(0,1)">
                        </div>
                    </div>
                    <div>
                        <label for="responsable_iva" class="block text-sm font-medium text-gray-700">Responsabilidad IVA</label>
                        <select id="responsable_iva" name="responsable_iva" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="1" <?php echo ($regData['responsable_iva'] ?? '') == '1' ? 'selected' : ''; ?>>Responsable de IVA</option>
                            <option value="0" <?php echo ($regData['responsable_iva'] ?? '0') == '0' ? 'selected' : ''; ?>>No Responsable de IVA</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="tamaño_empresa" class="block text-sm font-medium text-gray-700">Tamaño de Empresa (Ingresos)</label>
                    <select id="tamaño_empresa" name="tamaño_empresa" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="micro" <?php echo ($regData['tamaño_empresa'] ?? '') == 'micro' ? 'selected' : ''; ?>>Microempresa</option>
                        <option value="pequeña" <?php echo ($regData['tamaño_empresa'] ?? '') == 'pequeña' ? 'selected' : ''; ?>>Pequeña Empresa</option>
                        <option value="mediana" <?php echo ($regData['tamaño_empresa'] ?? '') == 'mediana' ? 'selected' : ''; ?>>Mediana Empresa</option>
                        <option value="grande" <?php echo ($regData['tamaño_empresa'] ?? '') == 'grande' ? 'selected' : ''; ?>>Gran Empresa</option>
                    </select>
                </div>

                <div>
                    <label for="ubicacion_geografica" class="block text-sm font-medium text-gray-700">Ubicación Geográfica</label>
                    <input id="ubicacion_geografica" name="ubicacion_geografica" type="text" required 
                        value="<?php echo htmlspecialchars($regData['ubicacion_geografica'] ?? ''); ?>"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="Ej: Bogotá, Colombia">
                </div>
            </div>

            <div>
                <label for="representante_legal" class="block text-sm font-medium text-gray-700">Representante Legal</label>
                <input id="representante_legal" name="representante_legal" type="text" required 
                    value="<?php echo htmlspecialchars($regData['representante_legal'] ?? ''); ?>"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                    placeholder="Nombre completo según documento">
            </div>

            <div>
                <label for="rol_id" class="block text-sm font-medium text-gray-700">Perfil en la Rueda</label>
                <select id="rol_id" name="rol_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="4" <?php echo ($regData['rol_id'] ?? '') == '4' ? 'selected' : ''; ?>>Comprador (Busco productos/servicios)</option>
                    <option value="3" <?php echo ($regData['rol_id'] ?? '') == '3' ? 'selected' : ''; ?>>Vendedor / Proveedor (Ofrezco productos/servicios)</option>
                </select>
            </div>

            <div class="relative">
                <label for="sector_busqueda" class="block text-sm font-medium text-gray-700">Actividad Económica (CIIU)</label>
                <input type="text" id="sector_busqueda" placeholder="Buscar por código o nombre..." autocomplete="off" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    value="<?php echo htmlspecialchars($regData['ciiu_clase'] ?? ''); ?>">
                <input type="hidden" id="sector_id" name="sector_id" required 
                    value="<?php echo htmlspecialchars($regData['sector_id'] ?? ''); ?>">
                <div id="resultados_busqueda" class="absolute z-50 w-full bg-white border border-gray-300 rounded-b-md shadow-lg max-h-60 overflow-y-auto hidden"></div>
                <div id="sector_seleccionado" class="hidden mt-2 p-2 bg-blue-50 border border-blue-200 rounded text-sm">
                    <strong>Seleccionado:</strong> <span id="sector_texto"></span>
                    <button type="button" id="limpiar_sector" class="ml-2 text-red-600 hover:text-red-800 underline text-xs">Cambiar</button>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Siguiente: Verificar Información <i class="fas fa-arrow-right ml-2"></i>
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
