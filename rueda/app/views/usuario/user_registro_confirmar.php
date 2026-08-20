<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../includes/CsrfService.php';
include __DIR__ . '/../layout/header.php';
// Recuperar datos de sesión
$regData = $_SESSION['reg_data'] ?? [];
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-gray-50 to-blue-50/30">
    <div class="max-w-2xl w-full space-y-8 bg-white p-8 md:p-12 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-100 relative overflow-hidden">
        <!-- Decoración de fondo -->
        <div class="absolute top-0 left-0 -mt-10 -ml-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        
        <div class="relative">
            <div class="text-center mb-10">
                <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold tracking-wide uppercase mb-4">
                    Confirmación de Datos
                </div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Verifica tu Información</h2>
                <p class="mt-2 text-gray-500 font-medium">Asegúrate de que todos los datos sean correctos antes de finalizar</p>
            </div>

            <div class="bg-gray-50/50 rounded-3xl p-2 md:p-6 border border-gray-100 mb-8">
                <div class="overflow-hidden rounded-2xl bg-white border border-gray-100 shadow-sm">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-4 px-6 text-gray-500 font-bold bg-gray-50/30 w-1/3">Razón social</td>
                                <td class="py-4 px-6 text-gray-900 font-black"><?php echo htmlspecialchars($regData['razon_social'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 text-gray-500 font-bold bg-gray-50/30">NIT / Identificación</td>
                                <td class="py-4 px-6 text-gray-900 font-bold">
                                    <?php echo htmlspecialchars($regData['nit'] ?? 'N/A'); ?>
                                    <?php if(!empty($regData['digito_verificacion'])): ?>
                                        <span class="text-gray-400 font-normal mx-1">-</span> <?php echo htmlspecialchars($regData['digito_verificacion']); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 text-gray-500 font-bold bg-gray-50/30">Responsabilidad IVA</td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold <?php echo ($regData['responsable_iva'] ?? '0') == '1' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-gray-100 text-gray-600 border border-gray-200'; ?>">
                                        <?php echo ($regData['responsable_iva'] ?? '0') == '1' ? 'Responsable' : 'No Responsable'; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 text-gray-500 font-bold bg-gray-50/30">Tamaño Empresa</td>
                                <td class="py-4 px-6 text-gray-800 font-medium"><?php echo ucfirst($regData['tamaño_empresa'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 text-gray-500 font-bold bg-gray-50/30">Ubicación</td>
                                <td class="py-4 px-6 text-gray-800 font-medium"><?php echo htmlspecialchars($regData['ubicacion_geografica'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 text-gray-500 font-bold bg-gray-50/30">Representante</td>
                                <td class="py-4 px-6 text-gray-800 font-medium"><?php echo htmlspecialchars($regData['representante_legal'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 text-gray-500 font-bold bg-gray-50/30">Forma Jurídica</td>
                                <td class="py-4 px-6 text-gray-800 font-medium">
                                    <?php echo htmlspecialchars($regData['tipo_asociacion'] ?? 'N/A'); ?>
                                    <?php if(!empty($regData['sub_tipo_asociacion'])): ?>
                                        <span class="text-gray-400 text-xs ml-1">(<?php echo htmlspecialchars($regData['sub_tipo_asociacion']); ?>)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 text-gray-500 font-bold bg-gray-50/30">Tipo Persona</td>
                                <td class="py-4 px-6 text-gray-800 font-medium">
                                    <?php 
                                        $tipo = $regData['tipo_persona'] ?? 'juridica';
                                        if($tipo == 'natural') echo 'Persona Natural';
                                        elseif($tipo == 'juridica') echo 'Persona Jurídica';
                                        else echo 'ESAL / Otros';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 text-gray-500 font-bold bg-gray-50/30">Actividad (CIIU)</td>
                                <td class="py-4 px-6 text-gray-800 text-xs font-bold">
                                    <span class="text-blue-600"><?php echo htmlspecialchars($regData['ciiu_clase'] ?? 'N/A'); ?></span> 
                                    <span class="text-gray-400 mx-1">|</span>
                                    <?php echo htmlspecialchars($regData['nombre_sector'] ?? 'N/A'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 text-gray-500 font-bold bg-gray-50/30">Perfil Rueda</td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wider <?php echo ($regData['rol_id'] ?? '') == '4' ? 'bg-blue-600 text-white' : 'bg-orange-500 text-white'; ?>">
                                        <?php echo ($regData['rol_id'] ?? '') == '4' ? 'Comprador' : 'Vendedor / Proveedor'; ?>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4 mb-8 flex items-center shadow-sm">
                <i class="fas fa-info-circle text-blue-500 text-xl mr-4"></i>
                <p class="text-xs text-blue-700 font-bold leading-relaxed">
                    Por favor verifica que los datos correspondan a la realidad de tu organización. Esta información será visible para otros participantes.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="index.php?controlador=usuario&accion=registro&editar=1" class="w-full sm:w-auto flex-1 flex justify-center items-center py-4 px-6 border border-gray-200 text-sm font-black rounded-2xl text-gray-500 bg-gray-50 hover:bg-gray-100 hover:text-gray-700 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Corregir Datos
                </a>
                <form action="index.php?controlador=usuario&accion=registro" method="POST" class="w-full sm:w-auto flex-1">
                    <?php echo CsrfService::getInputField('registro_confirmar'); ?>
                    <input type="hidden" name="paso" value="confirmar">
                    <button type="submit" class="group w-full flex justify-center items-center py-4 px-6 border border-transparent text-sm font-black rounded-2xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all duration-200 shadow-lg shadow-blue-500/20">
                        Todo correcto, continuar
                        <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
