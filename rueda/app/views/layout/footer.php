    </main>
    <?php if (!isset($hide_footer) || !$hide_footer): ?>
    <footer class="<?php echo $footer_bg ?? 'bg-[#00a2ff]'; ?> text-white py-8 mt-auto border-t border-sky-400/20 shadow-[0_-4px_20px_rgba(0,162,255,0.08)]">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-semibold">&copy; <?php echo date('Y'); ?> AGECSO - Software Rueda de Negocios. Todos los derechos reservados.</p>
            <p class="text-xs text-sky-100 mt-2 text-balance font-medium">Conectando la oferta y la demanda para fortalecer el relacionamiento empresarial.</p>
        </div>
        
        <!-- Campanita de notificaciones en el footer -->
        <div class="fixed bottom-6 right-6 z-50">
            <button id="btn-notificaciones" class="w-14 h-14 rounded-full bg-white shadow-2xl flex items-center justify-center hover:scale-110 transition-transform duration-200 group">
                <div class="relative">
                    <i class="fas fa-bell text-gray-700 text-xl group-hover:text-blue-600 transition-colors"></i>
                    <span id="notif-contador" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-rose-500 text-white text-xs font-black flex items-center justify-center hidden">0</span>
                </div>
            </button>
        </div>
    </footer>
    <?php endif; ?>
</body>
</html>

<script>
// Cargar contador de notificaciones en el footer
document.addEventListener('DOMContentLoaded', function() {
    fetch('index.php?controlador=usuario&accion=obtenerAlertas')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.contador > 0) {
                const contador = document.getElementById('notif-contador');
                if (contador) {
                    contador.textContent = data.contador;
                    contador.classList.remove('hidden');
                }
            }
        })
        .catch(error => console.error('Error cargando contador de alertas:', error));
});
</script>

