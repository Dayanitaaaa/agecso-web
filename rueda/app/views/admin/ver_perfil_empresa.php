<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="min-h-screen bg-gray-50/50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado del Perfil -->
        <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 overflow-hidden mb-8">
            <div class="h-36 bg-gradient-to-r from-[#fede32] via-[#ffe34d] to-[#e6b300] relative">
                <!-- Círculos decorativos de fondo -->
                <div class="absolute -right-5 -top-5 w-24 h-24 bg-white/20 rounded-full blur-xl"></div>
                <div class="absolute left-10 bottom-2 w-16 h-14 bg-white/10 rounded-full blur-lg"></div>
            </div>
            <div class="px-8 pb-8">
                <div class="relative flex justify-between items-end -mt-14 mb-6">
                    <div class="p-1.5 bg-white rounded-3xl shadow-sm border border-gray-100">
                        <div class="w-24 h-24 rounded-2xl bg-amber-50 border border-amber-100/50 flex items-center justify-center text-amber-500 text-4xl font-black">
                            <?php echo strtoupper(substr($perfil['razon_social'] ?? 'E', 0, 1)); ?>
                        </div>
                    </div>
                    <!-- Badge de estado -->
                    <?php 
                        $estado = $perfil['estado_verificacion'] ?? 'pendiente';
                        $estadoColors = [
                            'pendiente' => 'bg-amber-50 text-amber-800 border border-amber-200/50',
                            'aprobada' => 'bg-emerald-50 text-emerald-800 border border-emerald-200/50',
                            'rechazada' => 'bg-rose-50 text-red-800 border border-rose-200/50'
                        ];
                        $estadoIcons = [
                            'pendiente' => 'fa-clock',
                            'aprobada' => 'fa-check-circle',
                            'rechazada' => 'fa-times-circle'
                        ];
                    ?>
                    <span class="inline-flex items-center px-4 py-2.5 rounded-full text-xs font-extrabold shadow-sm <?php echo $estadoColors[$estado] ?? 'bg-gray-100 text-gray-800'; ?>">
                        <i class="fas <?php echo $estadoIcons[$estado] ?? 'fa-question'; ?> mr-1.5"></i>
                        <?php echo ucfirst(htmlspecialchars($estado)); ?>
                    </span>
                </div>
                
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                            <?php echo htmlspecialchars($perfil['razon_social'] ?? $perfil['nombreUsuario']); ?>
                        </h1>
                        <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-gray-400 mt-2">
                            <span class="flex items-center">
                                <i class="fas fa-tag mr-2 text-amber-500"></i>
                                <?php echo htmlspecialchars($perfil['ciiu_personalizado'] ?: ($perfil['ciiu_clase'] ?? 'N/A')); ?> - <?php echo htmlspecialchars($perfil['nombreSector'] ?? 'Sin sector'); ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-amber-500"></i>
                                <?php echo htmlspecialchars($perfil['ubicacionGeografica'] ?? 'Ubicación no especificada'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if(($perfil['verificada'] ?? 0) == 1): ?>
                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm">
                                <i class="fas fa-check-circle mr-1"></i> VERIFICADA
                            </span>
                        <?php endif; ?>
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-100 uppercase shadow-sm">
                            <?php echo ucfirst(htmlspecialchars($perfil['tamaño_empresa'] ?? 'micro')); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Columna Izquierda: Información Legal -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 p-8 hover:shadow-[0_8px_30px_rgba(0,0,0,0.02)] transition-all duration-300">
                    <h2 class="text-lg font-extrabold text-gray-800 mb-6 flex items-center gap-2 border-b border-gray-50 pb-4">
                        <i class="fas fa-file-contract text-amber-500"></i>
                        Información Legal y Tributaria
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Identificación (NIT)</p>
                            <p class="text-gray-800 font-bold text-sm flex items-center gap-1.5 flex-wrap">
                                <span>
                                    <?php echo htmlspecialchars($perfil['nit'] ?? 'No registrado'); ?>
                                    <?php if(!empty($perfil['digito_verificacion'])): ?>
                                        - <?php echo htmlspecialchars($perfil['digito_verificacion']); ?>
                                    <?php endif; ?>
                                </span>
                                <a href="https://www.rues.org.co/" target="_blank" class="inline-flex items-center text-[9px] font-black text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 px-2 py-0.5 rounded-md transition" title="Consultar legalidad en el RUES Oficial de Colombia">
                                    <i class="fas fa-search-dollar mr-1"></i> Verificar en RUES
                                </a>
                            </p>
                        </div>
                        
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Tipo de Persona</p>
                            <p class="text-gray-800 font-bold text-sm">
                                <?php 
                                    $tp = $perfil['tipo_persona'] ?? 'juridica';
                                    echo ($tp == 'natural') ? 'Persona Natural' : (($tp == 'juridica') ? 'Persona Jurídica' : 'ESAL / Otros');
                                ?>
                            </p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Forma Jurídica</p>
                            <p class="text-gray-800 font-bold text-sm">
                                <?php echo htmlspecialchars($perfil['tipo_asociacion'] ?? 'N/A'); ?>
                                <?php if(!empty($perfil['sub_tipo_asociacion'])): ?>
                                    <span class="text-gray-400 text-xs font-semibold">(<?php echo htmlspecialchars($perfil['sub_tipo_asociacion']); ?>)</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Responsabilidad IVA</p>
                            <p class="text-gray-800 font-bold text-sm">
                                <?php echo ($perfil['responsable_iva'] ?? 0) == 1 ? 'Sujeto Responsable' : 'No Responsable'; ?>
                            </p>
                        </div>

                        <div class="col-span-full pt-6 border-t border-gray-100 flex flex-col gap-3">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Representante Legal</p>
                            <div class="flex items-center gap-3 bg-gray-50/50 p-3 rounded-2xl border border-gray-100/50 w-fit pr-6">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200/50 flex items-center justify-center text-amber-600 font-extrabold">
                                    <?php echo strtoupper(substr($perfil['representante_legal'] ?? 'R', 0, 1)); ?>
                                </div>
                                <div>
                                    <p class="text-gray-800 font-bold text-sm"><?php echo htmlspecialchars($perfil['representante_legal'] ?? 'No asignado'); ?></p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase">Representante Oficial</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sobre la Empresa -->
                <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 p-8 hover:shadow-[0_8px_30px_rgba(0,0,0,0.02)] transition-all duration-300">
                    <h2 class="text-lg font-extrabold text-gray-800 mb-4 flex items-center gap-2 pb-2 border-b border-gray-50">
                        <i class="fas fa-info-circle text-amber-500"></i>
                        Sobre la Empresa
                    </h2>
                    <p class="text-gray-500 leading-relaxed italic text-sm font-medium">
                        "<?php echo htmlspecialchars($perfil['descripcion'] ?? 'Esta empresa aún no ha agregado una descripción corporativa.'); ?>"
                    </p>
                </div>
            </div>

            <!-- Columna Derecha: Datos de Contacto y Acciones -->
            <div class="space-y-8">
                <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 p-8 hover:shadow-[0_8px_30px_rgba(0,0,0,0.02)] transition-all duration-300">
                    <h2 class="text-md font-black text-gray-800 mb-6 flex items-center gap-1.5"><i class="fas fa-address-card text-amber-500"></i> Información de Contacto</h2>
                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0 border border-amber-100/55">
                                <i class="fas fa-envelope text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Correo Electrónico</p>
                                <p class="text-xs text-gray-800 font-bold mt-0.5 break-all"><?php echo htmlspecialchars($perfil['email']); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0 border border-amber-100/55">
                                <i class="fas fa-calendar-alt text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Miembro desde</p>
                                <p class="text-xs text-gray-800 font-bold mt-0.5"><?php echo date('d M, Y', strtotime($perfil['createdAt'])); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0 border border-amber-100/55">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Usuario</p>
                                <p class="text-xs text-gray-800 font-bold mt-0.5"><?php echo htmlspecialchars($perfil['nombreUsuario']); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0 border border-amber-100/55">
                                <i class="fas fa-toggle-on text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Estado del Usuario</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black mt-1 uppercase tracking-wider <?php echo ($perfil['usuarioActivo'] ?? 0) == 1 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-red-700 border border-rose-100'; ?>">
                                    <?php echo ($perfil['usuarioActivo'] ?? 0) == 1 ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Acciones Admin (Dorado y Carbón Contrastado) -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl shadow-xl p-8 text-white relative overflow-hidden border border-slate-800">
                    <!-- Círculo de fondo decorativo -->
                    <div class="absolute -right-12 -bottom-12 w-28 h-28 bg-[#fede32]/10 rounded-full blur-2xl"></div>
                    
                    <h3 class="font-extrabold text-md mb-6 flex items-center gap-2 border-b border-white/5 pb-4"><i class="fas fa-user-shield text-[#fede32]"></i> Estado en la Rueda</h3>
                    <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <span class="text-slate-400 text-xs font-bold">ID de Registro</span>
                            <span class="font-mono text-xs text-[#fede32] font-extrabold">#<?php echo str_pad($perfil['id'], 5, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <span class="text-slate-400 text-xs font-bold">Perfil Actual</span>
                            <span class="font-black text-xs uppercase tracking-wider text-slate-100"><?php echo ucfirst(htmlspecialchars($estado)); ?></span>
                        </div>
                    </div>
                    <div class="mt-8 space-y-3 relative z-10">
                        <?php if ($estado !== 'aprobada'): ?>
                            <a href="index.php?controlador=admin&accion=gestionarEmpresa&id=<?php echo $perfil['id']; ?>&estado=aprobada" 
                               class="block w-full text-center bg-[#fede32] hover:bg-[#e6b300] text-slate-900 py-3 rounded-xl font-black transition duration-200 text-xs shadow-md shadow-[#fede32]/10">
                                <i class="fas fa-check mr-1.5"></i> Aprobar Registro
                            </a>
                        <?php endif; ?>
                        <?php if ($estado !== 'rechazada'): ?>
                            <a href="index.php?controlador=admin&accion=gestionarEmpresa&id=<?php echo $perfil['id']; ?>&estado=rechazada" 
                               class="block w-full text-center bg-white/10 hover:bg-white/20 text-white py-3 rounded-xl font-black transition duration-200 text-xs"
                               onclick="return confirm('¿Estás seguro de rechazar esta empresa?')">
                                <i class="fas fa-times mr-1.5"></i> Rechazar Registro
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (($perfil['roleId'] ?? 0) == 3): ?>
                    <!-- Panel de Membresía (Solo para Proveedor / Vendedor) -->
                    <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 p-8 hover:shadow-[0_8px_30px_rgba(0,0,0,0.02)] transition-all duration-300 mt-8">
                        <h3 class="font-extrabold text-md mb-6 flex items-center gap-2 border-b border-gray-50 pb-4">
                            <i class="fas fa-crown text-amber-500"></i> 
                            Membresía de Vendedor
                        </h3>
                        
                        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'membresia_actualizada'): ?>
                            <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center text-[11px] text-emerald-800 font-bold">
                                <i class="fas fa-check-circle mr-2 text-emerald-600"></i> ¡Membresía actualizada con éxito!
                            </div>
                        <?php endif; ?>

                        <form action="index.php?controlador=admin&accion=guardarMembresia" method="POST" class="space-y-4">
                            <input type="hidden" name="empresa_id" value="<?php echo $perfil['id']; ?>">
                            
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1.5">Plan de Membresía</label>
                                <select name="membresia_plan" class="block w-full border border-gray-200 rounded-xl p-2.5 text-xs font-bold text-gray-700 bg-gray-50 focus:ring-amber-500 focus:border-amber-500">
                                    <option value="ninguno" <?php echo ($perfil['membresia_plan'] ?? 'ninguno') === 'ninguno' ? 'selected' : ''; ?>>Ninguno</option>
                                    <option value="mensual" <?php echo ($perfil['membresia_plan'] ?? '') === 'mensual' ? 'selected' : ''; ?>>Plan Mensual</option>
                                    <option value="anual" <?php echo ($perfil['membresia_plan'] ?? '') === 'anual' ? 'selected' : ''; ?>>Plan Anual</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1.5">Estado del Acceso</label>
                                <select name="membresia_estado" class="block w-full border border-gray-200 rounded-xl p-2.5 text-xs font-bold text-gray-700 bg-gray-50 focus:ring-amber-500 focus:border-amber-500">
                                    <option value="inactivo" <?php echo ($perfil['membresia_estado'] ?? 'inactivo') === 'inactivo' ? 'selected' : ''; ?>>Inactivo / Pendiente de Pago</option>
                                    <option value="activo" <?php echo ($perfil['membresia_estado'] ?? '') === 'activo' ? 'selected' : ''; ?>>Activo (Acceso Completo)</option>
                                    <option value="vencido" <?php echo ($perfil['membresia_estado'] ?? '') === 'vencido' ? 'selected' : ''; ?>>Vencido (Acceso Bloqueado)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1.5">Fecha de Vencimiento</label>
                                <input type="date" name="membresia_vencimiento" value="<?php echo !empty($perfil['membresia_vencimiento']) ? date('Y-m-d', strtotime($perfil['membresia_vencimiento'])) : ''; ?>" class="block w-full border border-gray-200 rounded-xl p-2.5 text-xs font-bold text-gray-700 bg-gray-50 focus:ring-amber-500 focus:border-amber-500">
                            </div>

                            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold py-3 rounded-xl transition duration-200 flex items-center justify-center gap-1.5 shadow-md">
                                <i class="fas fa-save text-xs text-[#fede32]"></i> Guardar Cambios
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
