<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Software de</span>
                        <span class="block text-blue-600 xl:inline">Ruedas de Negocios</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        La plataforma tecnológica que facilita la organización y ejecución de encuentros empresariales exitosos. Conecta tu oferta con la demanda estratégica.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="index.php?controlador=usuario&accion=registro" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                Registrar mi Empresa
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="index.php?controlador=usuario&accion=login" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg md:px-10">
                                Iniciar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1557426282-081deeaa8681?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Networking empresarial">
    </div>
</section>

<section class="py-12 bg-gray-50 text-center">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">¿Cómo funciona?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-blue-600 text-4xl mb-4"><i class="fas fa-user-plus"></i></div>
                <h3 class="text-xl font-semibold mb-2">1. Registro</h3>
                <p class="text-gray-600">Crea tu perfil como Comprador o Vendedor e identifica tu oferta o demanda.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-blue-600 text-4xl mb-4"><i class="fas fa-handshake"></i></div>
                <h3 class="text-xl font-semibold mb-2">2. Matchmaking</h3>
                <p class="text-gray-600">Encuentra aliados estratégicos y agenda citas de negocios personalizadas.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-blue-600 text-4xl mb-4"><i class="fas fa-chart-line"></i></div>
                <h3 class="text-xl font-semibold mb-2">3. Resultados</h3>
                <p class="text-gray-600">Mide el impacto de tus reuniones y genera reportes de gestión.</p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
