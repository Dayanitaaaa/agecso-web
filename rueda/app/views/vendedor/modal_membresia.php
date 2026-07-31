<!-- Modal de Gestión y Pago de Membresía de Vendedor -->
<div id="modalMembresia" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fondo oscuro -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" aria-hidden="true" onclick="cerrarModalMembresia()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
            <form action="index.php?controlador=vendedor&accion=pagarMembresia" method="POST" id="formMembresia">
                <div class="bg-white px-8 pt-8 pb-6">
                    <!-- Cabecera -->
                    <div class="flex items-center mb-6">
                        <div class="bg-amber-100 text-amber-600 p-3 rounded-2xl mr-4">
                            <i class="fas fa-crown text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Membresía AGECSO Vendedor</h3>
                            <p class="text-xs text-gray-500">Activación gratuita durante el periodo de pruebas</p>
                        </div>
                    </div>

                    <!-- Estado Actual -->
                    <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Estado de tu Suscripción</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?php echo ($empresa['membresia_estado'] ?? 'inactivo') === 'activo' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'; ?>">
                                <i class="fas <?php echo ($empresa['membresia_estado'] ?? 'inactivo') === 'activo' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-1"></i>
                                <?php echo ($empresa['membresia_estado'] ?? 'inactivo') === 'activo' ? 'Activo' : 'Inactivo / Expirado'; ?>
                            </span>
                        </div>
                        <?php if (($empresa['membresia_estado'] ?? 'inactivo') === 'activo' && !empty($empresa['membresia_vencimiento'])): ?>
                            <div class="text-right">
                                <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Expira el</p>
                                <p class="text-xs font-bold text-gray-700"><?php echo date('d/m/Y', strtotime($empresa['membresia_vencimiento'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Selección de Plan -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-3">1. Selecciona tu Plan de Vendedor</label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Plan Mensual -->
                            <div onclick="seleccionarPlan('mensual')" class="relative border-2 rounded-2xl p-4 flex flex-col cursor-pointer hover:bg-gray-50 transition duration-200 <?php echo ($empresa['membresia_plan'] ?? '') === 'mensual' || empty($empresa['membresia_plan']) || ($empresa['membresia_plan'] ?? '') === 'ninguno' ? 'border-amber-500 bg-amber-50/10' : 'border-gray-100'; ?>" id="label_plan_mensual">
                                <input type="radio" name="plan_membresia" id="plan_mensual" value="mensual" class="sr-only" <?php echo ($empresa['membresia_plan'] ?? '') === 'mensual' || empty($empresa['membresia_plan']) || ($empresa['membresia_plan'] ?? '') === 'ninguno' ? 'checked' : ''; ?>>
                                <span class="text-xs font-extrabold text-amber-600 uppercase mb-1">Mensual</span>
                                <span class="text-2xl font-black text-gray-800">$25.000<span class="text-xs font-medium text-gray-400">/mes</span></span>
                                <span class="text-[10px] text-gray-400 mt-2">Acceso completo a eventos y agendamiento por 30 días.</span>
                            </div>
                            
                            <!-- Plan Anual -->
                            <div onclick="seleccionarPlan('anual')" class="relative border-2 rounded-2xl p-4 flex flex-col cursor-pointer hover:bg-gray-50 transition duration-200 <?php echo ($empresa['membresia_plan'] ?? '') === 'anual' ? 'border-amber-500 bg-amber-50/10' : 'border-gray-100'; ?>" id="label_plan_anual">
                                <input type="radio" name="plan_membresia" id="plan_anual" value="anual" class="sr-only" <?php echo ($empresa['membresia_plan'] ?? '') === 'anual' ? 'checked' : ''; ?>>
                                <span class="absolute top-2 right-2 bg-emerald-500 text-white text-[8px] font-extrabold px-1.5 py-0.5 rounded-full uppercase">Ahorra 25%</span>
                                <span class="text-xs font-extrabold text-amber-600 uppercase mb-1">Anual</span>
                                <span class="text-2xl font-black text-gray-800">$225.000<span class="text-xs font-medium text-gray-400">/año</span></span>
                                <span class="text-[10px] text-gray-400 mt-2">Acceso ininterrumpido por 365 días a todos los eventos del año.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Activación Gratuita -->
                    <div class="mb-2 bg-emerald-50/45 border border-emerald-200/50 rounded-2xl p-5">
                        <h4 class="text-xs font-bold text-emerald-800 uppercase mb-2 flex items-center">
                            <i class="fas fa-gift mr-1.5 text-emerald-500"></i> Activación Gratuita para Pruebas
                        </h4>
                        <p class="text-xs text-emerald-700 leading-relaxed">
                            Durante este periodo de pruebas puedes activar tu membresía sin costo. Selecciona el plan y haz clic en activar para disfrutar del acceso completo.
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-5 flex space-x-4">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center px-6 py-3.5 border border-transparent text-sm font-bold rounded-2xl text-white bg-emerald-500 hover:bg-emerald-600 transition shadow-lg shadow-emerald-200">
                        <i class="fas fa-check-circle mr-2"></i> Activar Suscripción Gratis
                    </button>
                    <button type="button" onclick="cerrarModalMembresia()" class="px-6 py-3.5 text-sm font-bold rounded-2xl text-gray-500 bg-white border border-gray-200 hover:bg-gray-100 transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalMembresia() {
    document.getElementById('modalMembresia').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function cerrarModalMembresia() {
    document.getElementById('modalMembresia').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function seleccionarPlan(plan) {
    const labelMensual = document.getElementById('label_plan_mensual');
    const labelAnual = document.getElementById('label_plan_anual');
    const radioMensual = document.getElementById('plan_mensual');
    const radioAnual = document.getElementById('plan_anual');
    
    if (plan === 'mensual') {
        radioMensual.checked = true;
        labelMensual.classList.add('border-amber-500', 'bg-amber-50/10');
        labelMensual.classList.remove('border-gray-100');
        labelAnual.classList.add('border-gray-100');
        labelAnual.classList.remove('border-amber-500', 'bg-amber-50/10');
    } else {
        radioAnual.checked = true;
        labelAnual.classList.add('border-amber-500', 'bg-amber-50/10');
        labelAnual.classList.remove('border-gray-100');
        labelMensual.classList.add('border-gray-100');
        labelMensual.classList.remove('border-amber-500', 'bg-amber-50/10');
    }
}
</script>
