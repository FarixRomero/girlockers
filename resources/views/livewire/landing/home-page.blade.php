@php
    $fmtPrice = fn ($value) => rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
    $monthly = $pricing['monthly'] ?? 40;
    $quarterly = $pricing['quarterly'] ?? 100;
    $currencySymbol = $pricing['currency_symbol'] ?? 'S/';
@endphp

<div class="min-h-screen bg-white text-gray-900 selection:bg-purple-primary selection:text-white">
    <header class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <a href="#inicio" class="flex items-center">
                    <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" class="h-12 w-auto object-contain">
                </a>

                <nav class="hidden md:flex space-x-10 items-center">
                    <a class="text-sm font-semibold hover:text-purple-primary transition-colors text-gray-600" href="#inicio">Inicio</a>
                    <a class="text-sm font-semibold hover:text-purple-primary transition-colors text-gray-600" href="#clases">Clases</a>
                    <a class="text-sm font-semibold hover:text-purple-primary transition-colors text-gray-600" href="#instructoras">Instructoras</a>
                    <a class="text-sm font-semibold hover:text-purple-primary transition-colors text-gray-600" href="#planes">Precios</a>

                    <a class="text-sm font-semibold hover:text-purple-primary transition-colors text-gray-700" href="{{ route('login') }}" wire:navigate>
                        Ingresar
                    </a>
                    <a class="bg-brand-dark hover:bg-purple-dark text-white px-5 py-2.5 rounded-xl transition-all duration-300 text-sm font-bold shadow-lg shadow-purple-100 hover:shadow-xl"
                       href="{{ route('register') }}" wire:navigate>
                        Empezar ahora
                    </a>
                </nav>

                <div class="md:hidden flex items-center" x-data="{ open: false }">
                    <!-- Hamburger button -->
                    <button @click="open = !open"
                            class="p-2 rounded-lg text-gray-600 hover:text-purple-primary hover:bg-purple-50 transition-colors"
                            aria-label="Menú">
                        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <!-- Mobile menu fullscreen (teleportado al body para evitar problemas de stacking context) -->
                    <template x-teleport="body">
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-x-full"
                             x-transition:enter-end="opacity-100 translate-x-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-x-0"
                             x-transition:leave-end="opacity-0 translate-x-full"
                             style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:#ffffff;z-index:9999;display:flex;flex-direction:column;">

                            <!-- Header del menú -->
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:0 1.5rem;height:4rem;border-bottom:1px solid #f3f4f6;">
                                <a href="#inicio" @click="open = false">
                                    <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" style="height:2.5rem;width:auto;object-fit:contain;">
                                </a>
                                <button @click="open = false"
                                        class="p-2 rounded-lg text-gray-600 hover:text-purple-primary hover:bg-purple-50 transition-colors"
                                        aria-label="Cerrar menú">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Links de navegación -->
                            <nav class="flex-1 flex flex-col justify-center px-8 gap-2">
                                <a @click="open = false" href="#inicio"
                                   class="text-2xl font-bold text-gray-800 hover:text-purple-primary py-4 border-b border-gray-100 transition-colors text-center">
                                    Inicio
                                </a>
                                <a @click="open = false" href="#clases"
                                   class="text-2xl font-bold text-gray-800 hover:text-purple-primary py-4 border-b border-gray-100 transition-colors text-center">
                                    Clases
                                </a>
                                <a @click="open = false" href="#instructoras"
                                   class="text-2xl font-bold text-gray-800 hover:text-purple-primary py-4 border-b border-gray-100 transition-colors text-center">
                                    Instructoras
                                </a>
                                <a @click="open = false" href="#planes"
                                   class="text-2xl font-bold text-gray-800 hover:text-purple-primary py-4 border-b border-gray-100 transition-colors text-center">
                                    Precios
                                </a>
                            </nav>

                            <!-- Botones de acción -->
                            <div class="px-8 flex flex-col gap-3">
                                <a href="{{ route('login') }}" wire:navigate
                                   class="w-full text-center py-4 rounded-xl border-2 border-purple-primary text-purple-primary font-bold text-base hover:bg-purple-50 transition-colors">
                                    Ingresar
                                </a>
                                <a href="{{ route('register') }}" wire:navigate
                                   class="w-full text-center py-4 rounded-xl bg-brand-dark hover:bg-purple-dark text-white font-bold text-base shadow-lg shadow-purple-100 transition-all duration-300">
                                    Empezar ahora
                                </a>
                            </div>

                            <!-- Footer con redes sociales -->
                            <div class="px-8 py-6 flex justify-center gap-6 border-t border-gray-100 mt-4">
                                <a href="https://www.instagram.com/girls_lockers/" target="_blank"
                                   class="flex items-center gap-2 text-gray-500 hover:text-pink-500 transition-colors">
                                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                    <span class="text-sm font-semibold">@girls_lockers</span>
                                </a>
                                <a href="https://www.youtube.com/@Girls_Lockers" target="_blank"
                                   class="flex items-center gap-2 text-gray-500 hover:text-red-500 transition-colors">
                                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                    <span class="text-sm font-semibold">@Girls_Lockers</span>
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="relative h-[700px] flex items-center overflow-hidden" id="inicio">
            <div class="absolute inset-0 z-0">
                <img alt="Grupo de bailarinas entrenando locking" class="w-full h-full object-cover" src="{{ asset('images/imagen4.jpg') }}">
                <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/70 to-black/40 z-10"></div>
            </div>

            <div class="relative z-30 container mx-auto px-4 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    <div class="max-w-3xl border-l-4 border-purple-light pl-8 py-4">
                    <p class="text-purple-light font-bold tracking-[0.2em] mb-4 uppercase text-sm page-enter">
                        Academia online de Locking
                    </p>
                    <h1 class="font-display font-bold text-5xl md:text-7xl text-white mb-6 leading-tight page-enter" style="animation-delay: 0.05s;">
                        Aprende <span class="font-bold text-purple-light">locking</span> paso a paso
                    </h1>
                    <p class="text-lg md:text-xl text-gray-300 mb-10 font-medium max-w-xl leading-relaxed page-enter" style="animation-delay: 0.1s;">
                        Entrena a tu ritmo, desarrolla tu estilo y descubre tu esencia mientras construyes seguridad, disciplina y confianza.
                    </p>
                        <div class="flex flex-col sm:flex-row gap-6 page-enter" style="animation-delay: 0.15s;">
                            <a class="bg-brand-dark hover:bg-purple-dark text-white px-8 py-4 rounded-xl font-bold text-center transition duration-300 shadow-lg shadow-purple-primary/30 hover:shadow-xl"
                               href="{{ route('register') }}" wire:navigate>
                                Empezar hoy
                            </a>
                            <a class="group border border-white/30 text-white px-8 py-4 rounded-xl font-semibold text-center hover:border-purple-light hover:text-purple-light transition duration-300 flex items-center justify-center gap-3 text-sm bg-white/10 backdrop-blur-sm"
                               href="#video-demo">
                                <span class="inline-flex items-center justify-center w-6 h-6 border border-white/30 group-hover:border-purple-light rounded-full">
                                    <svg class="w-4 h-4 ml-0.5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                </span>
                                Ver demostraciones
                            </a>
                        </div>
                    </div>

                    <div class="hidden lg:block page-enter" style="animation-delay: 0.1s;">
                        <div class="rounded-3xl overflow-hidden border border-white/10 shadow-2xl bg-black">
                            <div class="relative aspect-video">
                                <iframe
                                    class="absolute inset-0 w-full h-full"
                                    src="https://www.youtube.com/embed/HefC_rMCs-Q?autoplay=1&mute=1&loop=1&playlist=HefC_rMCs-Q&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3&playsinline=1"
                                    title="Preview clase"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-white" id="clases">
            <div class="container mx-auto px-4 flex flex-col lg:flex-row gap-16 items-center">
                <div class="lg:w-1/2 page-enter">
                    <p class="text-purple-primary font-bold tracking-[0.2em] uppercase text-sm mb-3">Primer curso</p>
                    <h2 class="font-display font-bold text-4xl md:text-5xl mb-6 text-black leading-tight">
                        Locking desde <span class="text-brand-dark">cero</span>
                    </h2>
                    <p class="text-gray-600 font-medium text-lg leading-relaxed max-w-xl">
                        Ya sea que estés empezando o tengas experiencia, aquí encontrarás las herramientas que te ayudarán a avanzar en tu danza.
                    </p>

                    <div class="mt-6 space-y-2 border-t border-gray-100 pt-5">
                        <div class="flex items-center gap-2.5">
                            <span class="text-purple-primary font-black text-sm">✓ </span>
                            <p class="text-gray-700 text-sm font-medium">Desarrolla tu estilo con una guía clara y progresiva.</p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="text-purple-primary font-black text-sm">✓ </span>
                            <p class="text-gray-700 text-sm font-medium">Repite cada sesión las veces que necesites</p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="text-purple-primary font-black text-sm">✓ </span>
                            <p class="text-gray-700 text-sm font-medium">Técnica, timing, groove y performance</p>
                        </div>
                    </div>

                    <div class="mt-10">
                        <a href="{{ route('register') }}" wire:navigate
                           class="inline-flex items-center justify-center px-8 py-4 bg-brand-dark hover:bg-purple-dark text-white font-bold rounded-xl shadow-lg shadow-purple-100 hover:shadow-xl transition duration-300">
                            Empezar ahora
                        </a>
                    </div>
                </div>

                <!-- Módulos -->
                <div class="lg:w-1/2 space-y-3">

                    <!-- Módulo I — destacado -->
                    <div class="bg-white border border-purple-primary/30 rounded-2xl overflow-hidden shadow-xl shadow-purple-primary/10 page-enter">

                        <!-- Header -->
                        <div class="relative px-6 pt-6 pb-5 border-b border-gray-100">
                            <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-purple-primary via-purple-light to-transparent rounded-t-2xl"></div>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest text-purple-primary bg-purple-50 px-2.5 py-1 rounded-full mb-3">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-primary"></span>
                                Módulo I · Disponible
                            </span>
                            <h3 class="font-display font-bold text-gray-900 text-xl mb-1">Pasos Fundamentales</h3>
                            <p class="text-xs text-gray-400 font-medium">Básico &nbsp;·&nbsp; 10 sesiones</p>
                        </div>

                        <!-- Imagen destacada -->
                        <div class="overflow-hidden h-64">
                            <img src="{{ asset('images/imagen.jpg') }}" alt="Pasos Fundamentales" class="w-full h-full object-cover object-center">
                        </div>

                        <!-- Sesiones -->
                        <div class="px-6 py-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Contenido del módulo</p>
                            <ul class="grid grid-cols-2 gap-x-4 gap-y-2">
                                @foreach([
                                    ['Lock',             '01'],
                                    ['Keeping Time',     '02'],
                                    ['Wrist Roll',       '03'],
                                    ['Point',            '04'],
                                    ['Back Clap',        '05'],
                                    ['Lock Lock',        '06'],
                                    ['Give Yourself Five','07'],
                                    ['y más...',         '08'],
                                ] as [$move, $n])
                                    <li class="flex items-center gap-2.5">
                                        <span class="text-[10px] font-bold text-gray-300 w-4 shrink-0">{{ $n }}</span>
                                        <span class="text-sm text-gray-700 font-medium">{{ $move }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <p class="text-xs text-gray-400 mt-3">+ Coreografía y freestyle con los 8 fundamentos</p>
                        </div>

                        <!-- CTA -->
                        <div class="px-6 pb-6">
                            <a href="{{ route('register') }}" wire:navigate
                               class="flex items-center justify-center gap-2 w-full py-3 bg-brand-dark hover:bg-purple-dark text-white text-sm font-bold rounded-xl transition duration-300 group">
                                Empezar curso
                                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Módulos secundarios -->
                    @foreach([
                        ['num' => 'II',  'name' => 'Pasos Compuestos y Complementarios', 'level' => 'Intermedio'],
                        ['num' => 'III', 'name' => 'Power Moves & Tricks',               'level' => 'Avanzado'],
                        ['num' => '✦',  'name' => 'Acondicionamiento, cultura y comunidad', 'level' => 'Complementario'],
                    ] as $mod)
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 page-enter">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-gray-300 w-5 text-center">{{ $mod['num'] }}</span>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">{{ $mod['name'] }}</p>
                                    <p class="text-xs text-gray-400 font-medium">{{ $mod['level'] }}</p>
                                </div>
                            </div>
                            <a href="{{ route('login') }}" wire:navigate
                               class="text-xs text-purple-primary font-semibold hover:underline shrink-0">
                                Descubrir →
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>

        <section class="py-24 border-y border-gray-100 relative overflow-hidden" id="video-demo">
            <!-- Fondo con degradado suave -->
            <div class="absolute inset-0 bg-gradient-to-br from-purple-ultralight via-white to-gray-50 pointer-events-none"></div>
            <!-- Glow central -->
            <div class="absolute top-1/2 left-1/4 -translate-y-1/2 w-[600px] h-[600px] bg-purple-primary/10 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute top-1/2 right-1/4 -translate-y-1/2 w-[400px] h-[400px] bg-brand-dark/8 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="relative z-10 container mx-auto px-4">
                <div class="flex flex-col lg:flex-row items-center gap-16">
                    <div class="lg:w-5/12 space-y-8 order-2 lg:order-1 page-enter">
                        <p class="text-brand-dark font-bold tracking-[0.2em] uppercase text-sm">Aprende a tu manera</p>
                        <h2 class="font-display font-bold text-4xl md:text-5xl text-brand-dark leading-tight">
                            Entrena a <span class="font-bold">tu ritmo</span>
                        </h2>
                        <p class="text-gray-600 font-medium text-lg leading-relaxed">
                            Clases diseñadas para que practiques y entrenes. Repite cada clase las veces que necesites, corrige detalles y avanza con seguridad.
                        </p>
                        <ul class="space-y-5 mt-8 border-t border-gray-100 pt-8">
                            <li class="flex items-start gap-4 text-brand-dark">
                                <div class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm shrink-0">
                                    <svg class="w-5 h-5 text-brand-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold block">Sin horarios fijos</span>
                                    <span class="text-gray-600 text-sm">Entrena cuando quieras y repite cada clase sin límite</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4 text-brand-dark">
                                <div class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm shrink-0">
                                    <svg class="w-5 h-5 text-brand-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold block">Ruta clara por nivel</span>
                                    <span class="text-gray-600 text-sm">De fundamentos a compuestos y power moves, paso a paso</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4 text-brand-dark">
                                <div class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm shrink-0">
                                    <svg class="w-5 h-5 text-brand-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold block">Progreso real</span>
                                    <span class="text-gray-600 text-sm">Aprende los pasos, sus nombres y la base del locking</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="lg:w-7/12 w-full order-1 lg:order-2 page-enter" style="animation-delay: 0.05s;">
                        <div class="relative bg-black rounded-2xl overflow-hidden shadow-2xl shadow-gray-200 aspect-video group border border-gray-100">
                            <iframe
                                class="absolute inset-0 w-full h-full"
                                src="https://www.youtube.com/embed/q6sA7DxgI6Q?si=8qJTWtTGwxMSpn-C&autoplay=1&mute=1&rel=0&modestbranding=1"
                                title="Fragmento de clase"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                        </div>

                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="rounded-2xl overflow-hidden border border-gray-100 bg-black shadow-xl aspect-video">
                                <iframe
                                    class="w-full h-full"
                                    src="https://www.youtube.com/embed/8b18KD5O3y8?rel=0&modestbranding=1&autoplay=1&mute=1&rel=0&modestbranding=1"
                                    title="Fragmento de entrenamiento"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen></iframe>
                            </div>
                            <div class="rounded-2xl overflow-hidden border border-gray-100 bg-black shadow-xl aspect-video">
                                <iframe
                                    class="w-full h-full"
                                    src="https://www.youtube.com/embed/oMY7XiHefVM?rel=0&modestbranding=1&autoplay=1&mute=1&rel=0&modestbranding=1"
                                    title="Fragmento de entrenamiento"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Instructoras -->
        <section class="py-24 bg-white" id="instructoras">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16 page-enter">
                    <h2 class="font-display font-bold text-4xl mb-4 text-brand-dark">Instructoras</h2>
                </div>

                @php
                    $instructorName = 'Tatiana Cerna';
                    $instructorPhoto = asset('images/tatiana.png');
                    $instructorIg = 'https://www.instagram.com/girls_lockers/';
                @endphp

                <div class="bg-white rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden page-enter">
                    <div class="flex flex-col lg:flex-row">
                        <div class="lg:w-5/12 relative h-[420px] lg:h-auto">
                            <img alt="{{ $instructorName }}" class="w-full h-full object-cover object-center transition-all duration-700" src="{{ $instructorPhoto }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent lg:bg-gradient-to-r lg:from-transparent lg:to-black/10"></div>
                        </div>
                        <div class="lg:w-7/12 p-8 md:p-12 lg:p-16 flex flex-col justify-center">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="font-display font-bold text-4xl md:text-5xl text-gray-900 mb-2">{{ $instructorName }}</h3>
                                    <div class="flex items-center text-purple-primary font-bold tracking-wider text-xs uppercase">
                                        Lima - Perú 🇵🇪
                                    </div>
                                </div>
                                <div class="hidden md:block">
                                    <span class="text-6xl text-purple-primary/20">“</span>
                                </div>
                            </div>

                            <p class="text-lg md:text-xl text-gray-600 font-medium mb-8 border-l-4 border-purple-primary pl-6 py-2">
                                Bailarina profesional peruana con una trayectoria de más de 12 años de experiencia. Maestra especializada en el estilo Locking.
                            </p>

                            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                                @foreach([
                                    'Líder de @reler_crew, Creadora y directora de @girls_lockers (Espacio de empoderamiento Locking).',
                                    'Creadora y directora del evento @seminario_locking.',
                                    'Graduada en la 4ta promoción de Escenik en la carrera profesional de danza moderna.',
                                    'Participación como bailarina profesional en la Ceremonia de Inauguración de los juegos panamericanos / LIMA2019.',
                                    'Participaciones en diferentes competencias de freestyle y coreográficas.',
                                    'Jueza y tallerista en festivales nacionales e internacionales.',
                                ] as $item)
                                    <div class="flex items-start">
                                        <span class="text-purple-primary mr-3 mt-1 text-xl">✓</span>
                                        <span class="text-gray-700 text-sm font-medium">{{ $item }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-8 bg-gray-50 border border-gray-100 rounded-2xl p-6">
                                <p class="text-xs font-bold uppercase tracking-wider text-purple-primary mb-2">Capacitada por</p>
                                <p class="text-gray-700 text-sm font-medium leading-relaxed">
                                    Don Campbell (EEUU), Jimmy Scoo B doo (EEUU), Tash (CANADÁ), Sundance (EEUU), Toni Gogo (EEUU), Willow (FRA), P lock (FRA), Locking Khan (COR), Rubén Chi (NETH), Firelock (EEUU), Vovan (RUSIA), Gemini (FRA), Hurrikane (EEUU), entre otros.
                                </p>
                            </div>

                            <div class="mt-10 flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('register') }}" wire:navigate
                                   class="bg-brand-dark hover:bg-purple-dark text-white px-8 py-4 rounded-xl font-bold text-center transition duration-300 shadow-lg shadow-purple-100 hover:shadow-xl">
                                    Empezar ahora
                                </a>
                                @if($instructorIg)
                                    <a class="border border-gray-200 text-gray-900 px-8 py-4 rounded-xl font-semibold text-center hover:border-purple-primary hover:text-purple-primary transition duration-300 text-sm bg-white"
                                       href="{{ $instructorIg }}" target="_blank" rel="noopener noreferrer">
                                        Instagram
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Planes -->
        <section class="py-24 relative overflow-hidden" id="planes">
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/imagen2.jpg') }}" alt="" class="w-full h-full object-cover object-center">
                <div class="absolute inset-0 bg-black/75"></div>
            </div>
            <div class="relative z-10 container mx-auto px-4">
                <div class="text-center mb-16 page-enter">
                    <h2 class="font-display font-bold text-4xl mb-4 text-white">Membresía</h2>
                    <p class="text-gray-400 font-medium text-lg">Elige el plan que se adapte a ti y empieza a entrenar hoy.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <div class="bg-white p-10 border-2 border-gray-100 transition duration-300 relative flex flex-col shadow-lg hover:shadow-xl rounded-3xl overflow-hidden page-enter">
                        <div class="mb-6">
                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-3 py-1 uppercase tracking-wider rounded-full">Plan Mensual</span>
                            <h3 class="mt-6 font-display font-bold text-5xl text-gray-900">
                                {{ $currencySymbol }}{{ $fmtPrice($monthly) }}<span class="text-lg font-normal text-gray-500 ml-1">/mes</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-2 font-medium">Flexible para empezar</p>
                        </div>
                        <hr class="my-6 border-gray-100"/>
                        <ul class="space-y-4 mb-8 flex-1">
                            <li class="flex items-start text-gray-700 text-sm">
                                <span class="text-purple-primary text-lg mr-3 mt-0.5">✓</span> Entrena cuando quieras y repite las clases las veces que necesites
                            </li>
                            <li class="flex items-start text-gray-700 text-sm">
                                <span class="text-purple-primary text-lg mr-3 mt-0.5">✓</span> Ruta clara por nivel: fundamentos → compuestos → danzas sociales → power moves
                            </li>
                            <li class="flex items-start text-gray-700 text-sm">
                                <span class="text-purple-primary text-lg mr-3 mt-0.5">✓</span> Enfocado en progreso real
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" wire:navigate
                           class="w-full py-4 rounded-xl font-bold text-base transition-all bg-gray-100 text-gray-900 hover:bg-gray-200 text-center">
                            Elegir plan mensual
                        </a>
                    </div>

                    <div class="bg-white p-10 border-4 border-purple-600 shadow-2xl relative flex flex-col rounded-3xl overflow-hidden page-enter">
                        <div class="absolute top-4 right-4 z-10">
                            <span class="bg-gradient-to-r from-purple-600 to-pink-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg uppercase">
                            Recomendado
                            </span>
                        </div>
                        <div class="mb-6">
                            <span class="bg-purple-50 text-purple-700 text-xs font-bold px-3 py-1 uppercase tracking-wider rounded-full">Plan Trimestral</span>
                            <h3 class="mt-6 font-display font-bold text-5xl text-gray-900">
                                {{ $currencySymbol }}{{ $fmtPrice($quarterly) }}<span class="text-lg font-normal text-gray-500 ml-1">/3 meses</span>
                            </h3>
                        </div>
                        <hr class="my-6 border-gray-100"/>
                        <ul class="space-y-4 mb-8 flex-1">
                            <li class="flex items-start text-gray-700 text-sm">
                                <span class="text-purple-600 text-lg mr-3 mt-0.5">✓</span> <span class="font-bold text-gray-900">Todo lo del plan mensual</span>
                            </li>
                            <li class="flex items-start text-gray-700 text-sm">
                                <span class="text-purple-600 text-lg mr-3 mt-0.5">✓</span> Profundizas en variaciones, limpieza y fundamentos.
                            </li>
                            <li class="flex items-start text-gray-700 text-sm">
                                <span class="text-purple-600 text-lg mr-3 mt-0.5">✓</span> Constancia real: 12 semanas para ver un antes y un después
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" wire:navigate
                           class="w-full py-4 rounded-xl font-bold text-base transition-all bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg hover:shadow-xl hover:scale-[1.02] text-center">
                            Elegir plan trimestral
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Comunidad / misión (refuerzo emocional) -->
        <section class="py-24 relative overflow-hidden bg-white" id="mission">
            <div class="absolute inset-0 bg-white">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-purple-primary/10 via-white to-white opacity-70"></div>
            </div>

            <div class="relative z-10 container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="page-enter">
                        <h2 class="mission-title font-display font-bold text-4xl md:text-6xl text-gray-900 leading-tight">
                            Aprende desde <span class="text-purple-primary font-bold">cero</span>
                        </h2>
                        <p class="mission-text-1 text-gray-600 text-lg md:text-2xl mt-6 font-medium leading-relaxed">
                            Ya sea que estés empezando o tengas experiencia, encuentra el curso adecuado para tu nivel y sigue creciendo.
                        </p>
                        <div class="mt-10">
                            <a href="{{ route('register') }}" wire:navigate
                               class="bg-brand-dark hover:bg-purple-dark text-white px-8 py-4 rounded-xl font-bold text-center transition duration-300 shadow-lg shadow-purple-100 hover:shadow-xl inline-flex">
                                Empezar ahora
                            </a>
                        </div>
                    </div>

                    <div class="page-enter" style="animation-delay: 0.05s;">
                        <div class="rounded-2xl overflow-hidden shadow-2xl border border-gray-100">
                            <img src="{{ asset('images/aprende-desde-cero.jpg') }}" alt="Aprende locking desde cero" class="w-full h-[360px] md:h-[480px] object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 bg-gray-900 border-t border-gray-800">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div>
                        <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" class="h-10 w-auto object-contain mb-4">
                    </div>

                    <div>
                        <h4 class="font-display font-bold text-white mb-4">Enlaces</h4>
                        <ul class="space-y-2">
                            <li><a href="#instructoras" class="text-gray-400 hover:text-purple-light transition-colors font-medium">Instructoras</a></li>
                            <li><a href="#planes" class="text-gray-400 hover:text-purple-light transition-colors font-medium">Planes</a></li>
                            <li><a href="#video-demo" class="text-gray-400 hover:text-purple-light transition-colors font-medium">Demo</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-display font-bold text-white mb-4">Cuenta</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('register') }}" wire:navigate class="text-gray-400 hover:text-purple-light transition-colors font-medium">Registrarse</a></li>
                            <li><a href="{{ route('login') }}" wire:navigate class="text-gray-400 hover:text-purple-light transition-colors font-medium">Iniciar sesión</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 pt-8 text-center">
                    <p class="text-gray-500 text-sm mb-2">
                        © {{ date('Y') }}. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </footer>
    </main>
</div>

@push('scripts')
@vite(['resources/js/landing-animations.js'])
<script type="module">
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            const target = href ? document.querySelector(href) : null;
            if (!target) return;
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeSlideUp 0.6s ease-out forwards';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.page-enter').forEach(el => observer.observe(el));
</script>
@endpush
