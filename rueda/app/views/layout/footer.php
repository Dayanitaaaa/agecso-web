    </main>
    <?php if (!isset($hide_footer) || !$hide_footer): ?>
    <footer class="<?php echo $footer_bg ?? 'bg-[#00a2ff]'; ?> text-white py-8 mt-auto border-t border-sky-400/20 shadow-[0_-4px_20px_rgba(0,162,255,0.08)]">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-semibold">&copy; <?php echo date('Y'); ?> AGECSO - Software Rueda de Negocios. Todos los derechos reservados.</p>
            <p class="text-xs text-sky-100 mt-2 text-balance font-medium">Conectando la oferta y la demanda para fortalecer el relacionamiento empresarial.</p>
        </div>
    </footer>
    <?php endif; ?>
</body>
</html>

