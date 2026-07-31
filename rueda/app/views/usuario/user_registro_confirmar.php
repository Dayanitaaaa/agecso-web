<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../includes/CsrfService.php';
include __DIR__ . '/../layout/header.php';
// Recuperar datos de sesión
$regData = $_SESSION['reg_data'] ?? [];
?>

<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full bg-white p-8 rounded-xl shadow-lg">
        <div class="text-center mb-6">
            <div class="flex items-center justify-center mb-4">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">Confirmación de Datos</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Verifica tu Información</h2>
            <p class="mt-2 text-sm text-gray-600">Por favor revisa que la información sea verídica</p>
        </div>

        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-200">
                    <tr class="border-b border-gray-200">
                        <td class="py-3 text-gray-600 font-medium w-1/3">Razón social:</td>
                        <td class="py-3 text-gray-900 font-semibold"><?php echo htmlspecialchars($regData['razon_social'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 text-gray-600 font-medium">NIT:</td>
                        <td class="py-3 text-gray-900">
                            <?php echo htmlspecialchars($regData['nit'] ?? 'N/A'); ?>
                            <?php if(!empty($regData['digito_verificacion'])): ?>
                                - <?php echo htmlspecialchars($regData['digito_verificacion']); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 text-gray-600 font-medium">Responsabilidad IVA:</td>
                        <td class="py-3 text-gray-900"><?php echo ($regData['responsable_iva'] ?? '0') == '1' ? 'Responsable' : 'No Responsable'; ?></td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 text-gray-600 font-medium">Tamaño Empresa:</td>
                        <td class="py-3 text-gray-900"><?php echo ucfirst($regData['tamaño_empresa'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 text-gray-600 font-medium">Ubicación:</td>
                        <td class="py-3 text-gray-900"><?php echo htmlspecialchars($regData['ubicacion_geografica'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 text-gray-600 font-medium">Representante:</td>
                        <td class="py-3 text-gray-900"><?php echo htmlspecialchars($regData['representante_legal'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 text-gray-600 font-medium">Forma Jurídica:</td>
                        <td class="py-3 text-gray-900">
                            <?php echo htmlspecialchars($regData['tipo_asociacion'] ?? 'N/A'); ?>
                            <?php if(!empty($regData['sub_tipo_asociacion'])): ?>
                                (<?php echo htmlspecialchars($regData['sub_tipo_asociacion']); ?>)
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 text-gray-600 font-medium">Tipo Persona:</td>
                        <td class="py-3 text-gray-900">
                            <?php 
                                $tipo = $regData['tipo_persona'] ?? 'juridica';
                                if($tipo == 'natural') echo 'Persona Natural';
                                elseif($tipo == 'juridica') echo 'Persona Jurídica';
                                else echo 'ESAL / Otros';
                            ?>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 text-gray-600 font-medium">CIIU:</td>
                        <td class="py-3 text-gray-900"><?php echo htmlspecialchars($regData['ciiu_clase'] ?? 'N/A'); ?> - <?php echo htmlspecialchars($regData['nombre_sector'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td class="py-3 text-gray-600 font-medium">Perfil:</td>
                        <td class="py-3 text-gray-900"><?php echo ($regData['rol_id'] ?? '') == '4' ? 'Comprador' : 'Vendedor/Proveedor'; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-800 text-center">
                <i class="fas fa-info-circle mr-2"></i>
                Por favor verificar que los datos estén diligenciados en base a la realidad de su organización
            </p>
        </div>

        <div class="flex items-center justify-between space-x-4">
            <a href="index.php?controlador=usuario&accion=registro&editar=1" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-4 rounded-lg font-medium text-center transition">
                <i class="fas fa-arrow-left mr-2"></i> Corregir
            </a>
            <form action="index.php?controlador=usuario&accion=registro" method="POST" class="flex-1">
                <?php echo CsrfService::getInputField('registro_confirmar'); ?>
                <input type="hidden" name="paso" value="confirmar">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition">
                    Continuar <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
