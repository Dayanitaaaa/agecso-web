<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Rueda de Negocios AGECSO</title>
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
                Recuperar Acceso
            </h1>
            <div class="w-16 h-1 bg-[#00a2ff] mx-auto mt-2 rounded-full"></div>
            <p class="text-gray-500 mt-4 font-medium">Plataforma Rueda de Negocios</p>
        </div>

        <?= $mensaje ?>

        <form action="index.php?controlador=usuario&accion=forgotPassword" method="POST" class="space-y-8">
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 ml-4 tracking-wider uppercase">Correo Electrónico</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-400 group-focus-within:text-[#00a2ff] transition-colors">
                        <i class="fas fa-envelope text-lg"></i>
                    </span>
                    <input type="email" name="correo" required 
                        class="block w-full pl-14 pr-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-full text-gray-700 shadow-sm focus:ring-4 focus:ring-sky-100 focus:border-[#00a2ff] focus:bg-white transition-all duration-300 outline-none placeholder-gray-300"
                        placeholder="ejemplo@empresa.com">
                </div>
                <p class="text-[10px] text-gray-400 ml-6">Te enviaremos un enlace seguro para restablecer tu contraseña.</p>
            </div>

            <button type="submit" 
                class="w-full flex justify-center py-4 px-6 border-transparent rounded-full shadow-[0_4px_20px_rgba(0,162,255,0.3)] text-base font-black text-white bg-[#00a2ff] hover:bg-[#008fe5] hover:shadow-[0_8px_25px_rgba(0,162,255,0.4)] transition-all duration-300 transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest">
                Enviar Enlace
            </button>
        </form>

        <div class="mt-10 text-center border-t border-gray-100 pt-8">
            <a href="index.php?controlador=usuario&accion=login" class="text-sm font-bold text-[#00a2ff] hover:text-[#002e53] transition-colors flex items-center justify-center gap-2 group">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> 
                VOLVER AL INICIO DE SESIÓN
            </a>
        </div>
    </div>

    <!-- Footer simple -->
    <div class="absolute bottom-6 left-0 w-full text-center text-white/60 text-[10px] font-medium tracking-widest uppercase">
        © <?php echo date('Y'); ?> AGECSO - Matchmaking Inteligente
    </div>
</body>
</html>
