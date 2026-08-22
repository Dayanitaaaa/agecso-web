<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - Rueda de Negocios AGECSO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-agecso-gradient {
            background: linear-gradient(135deg, #002e53 0%, #00a2ff 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .float-animation {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-agecso-gradient min-h-screen flex items-center justify-center p-4">
    
    <!-- Decoración de fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-sky-400/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-md w-full glass-card rounded-[2.5rem] shadow-2xl p-10 relative z-10">
        <div class="text-center mb-10">
            <div class="inline-block p-4 bg-white rounded-full shadow-lg mb-6 float-animation">
                <img src="https://agecso.org/assets/img/AGECSO.jpg" alt="AGECSO" class="h-20 w-20 object-cover rounded-full">
            </div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight uppercase">
                Nueva Contraseña
            </h1>
            <div class="w-16 h-1 bg-[#00a2ff] mx-auto mt-2 rounded-full"></div>
            <p class="text-gray-500 mt-4 font-medium">Establece tu nuevo acceso</p>
        </div>

        <?= $mensaje ?>

        <form action="index.php?controlador=usuario&accion=resetPassword&token=<?= htmlspecialchars($_GET['token'] ?? '') ?>" method="POST" class="space-y-6">
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 ml-4 tracking-wider uppercase">Nueva Contraseña</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-400 group-focus-within:text-[#00a2ff] transition-colors">
                        <i class="fas fa-lock text-lg"></i>
                    </span>
                    <input type="password" name="password" required minlength="6"
                        class="block w-full pl-14 pr-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-full text-gray-700 shadow-sm focus:ring-4 focus:ring-sky-100 focus:border-[#00a2ff] focus:bg-white transition-all duration-300 outline-none placeholder-gray-300"
                        placeholder="Mínimo 6 caracteres">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 ml-4 tracking-wider uppercase">Confirmar Contraseña</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-400 group-focus-within:text-[#00a2ff] transition-colors">
                        <i class="fas fa-check-double text-lg"></i>
                    </span>
                    <input type="password" name="confirm_password" required minlength="6"
                        class="block w-full pl-14 pr-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-full text-gray-700 shadow-sm focus:ring-4 focus:ring-sky-100 focus:border-[#00a2ff] focus:bg-white transition-all duration-300 outline-none placeholder-gray-300"
                        placeholder="Repite la contraseña">
                </div>
            </div>

            <button type="submit" 
                class="w-full flex justify-center py-4 px-6 border-transparent rounded-full shadow-[0_4px_20px_rgba(0,162,255,0.3)] text-base font-black text-white bg-[#00a2ff] hover:bg-[#008fe5] hover:shadow-[0_8px_25px_rgba(0,162,255,0.4)] transition-all duration-300 transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest">
                Actualizar Contraseña
            </button>
        </form>

        <div class="mt-10 text-center border-t border-gray-100 pt-8">
            <a href="index.php?controlador=usuario&accion=login" class="text-sm font-bold text-gray-400 hover:text-[#00a2ff] transition-colors uppercase tracking-widest">
                Cancelar y volver
            </a>
        </div>
    </div>

    <!-- Footer simple -->
    <div class="absolute bottom-6 left-0 w-full text-center text-white/60 text-[10px] font-medium tracking-widest uppercase">
        © <?php echo date('Y'); ?> AGECSO - Matchmaking Inteligente
    </div>
</body>
</html>
