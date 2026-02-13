@php
    $fmtPrice = fn ($value) => rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
    $monthly = $pricing['monthly'] ?? 30;
    $quarterly = $pricing['quarterly'] ?? 50;
    $quarterlyOriginal = $pricing['quarterly_original'] ?? 60;
    $currencySymbol = $pricing['currency_symbol'] ?? 'S/';
    $savings = max(0, (float) $quarterlyOriginal - (float) $quarterly);
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
                    <a class="bg-purple-primary hover:bg-purple-dark text-white px-5 py-2.5 rounded-xl transition-all duration-300 text-sm font-bold shadow-lg shadow-purple-100 hover:shadow-xl"
                       href="{{ route('register') }}" wire:navigate>
                        Empezar ahora
                    </a>
                </nav>

                <div class="md:hidden flex items-center gap-3">
                    <a class="text-sm font-semibold hover:text-purple-primary transition-colors text-gray-700"
                       href="{{ route('login') }}" wire:navigate>
                        Ingresar
                    </a>
                    <a class="bg-purple-primary hover:bg-purple-dark text-white px-4 py-2 rounded-xl transition-all duration-300 text-sm font-bold shadow-lg shadow-purple-100"
                       href="{{ route('register') }}" wire:navigate>
                        Empezar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="relative h-[700px] flex items-center overflow-hidden" id="inicio">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-white/20 z-10"></div>
                <img alt="Grupo de bailarinas entrenando locking" class="w-full h-full object-cover grayscale opacity-20" src="{{ asset('images/imagen4.jpg') }}">
                <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/70 to-white/40 z-20"></div>
            </div>

            <div class="relative z-30 container mx-auto px-4 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    <div class="max-w-3xl border-l-4 border-purple-primary pl-8 py-4">
                    <p class="text-purple-primary font-bold tracking-[0.2em] mb-4 uppercase text-sm page-enter">
                        Academia online de Locking
                    </p>
                    <h1 class="font-display font-bold text-5xl md:text-7xl text-black mb-6 leading-tight page-enter" style="animation-delay: 0.05s;">
                        Aprende <span class="italic font-normal text-purple-primary">locking</span> con cursos estructurados
                    </h1>
                    <p class="text-lg md:text-xl text-gray-600 mb-10 font-medium max-w-xl leading-relaxed page-enter" style="animation-delay: 0.1s;">
                        Aprende desde cero, a tu ritmo. Entrena fundamentos, grooves, combos y freestyle con una ruta clara.
                    </p>
                        <div class="flex flex-col sm:flex-row gap-6 page-enter" style="animation-delay: 0.15s;">
                            <a class="bg-purple-primary hover:bg-purple-dark text-white px-8 py-4 rounded-xl font-bold text-center transition duration-300 shadow-lg shadow-purple-100 hover:shadow-xl"
                               href="{{ route('register') }}" wire:navigate>
                                Empezar ahora
                            </a>
                            <a class="group border border-gray-200 text-gray-900 px-8 py-4 rounded-xl font-semibold text-center hover:border-purple-primary hover:text-purple-primary transition duration-300 flex items-center justify-center gap-3 text-sm bg-white/70 backdrop-blur-sm"
                               href="#video-demo">
                                <span class="inline-flex items-center justify-center w-6 h-6 border border-black/15 group-hover:border-purple-primary rounded-full">
                                    <svg class="w-4 h-4 ml-0.5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                </span>
                                Ver demo
                            </a>
                        </div>
                    </div>

                    <div class="hidden lg:block page-enter" style="animation-delay: 0.1s;">
                        <div class="rounded-3xl overflow-hidden border border-gray-100 shadow-2xl shadow-gray-200 bg-black">
                            <div class="relative aspect-video">
                                <iframe
                                    class="absolute inset-0 w-full h-full"
                                    src="https://www.youtube.com/embed/oMY7XiHefVM?autoplay=1&mute=1&loop=1&playlist=oMY7XiHefVM&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3&playsinline=1"
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
            <div class="container mx-auto px-4 flex flex-col lg:flex-row gap-16 items-start">
                <div class="lg:w-5/12 lg:sticky lg:top-24 page-enter">
                    <h2 class="font-display font-bold text-4xl md:text-5xl mb-8 text-black leading-tight">
                        Clases con estructura <span class="italic text-purple-primary font-normal">y progreso real</span>
                    </h2>
                    <p class="text-gray-600 font-medium text-lg leading-relaxed max-w-xl">
                        Las clases son claras y prácticas, pensadas para que entrenes hoy y mañana estés mejor. Para personas que quieren empezar o subir de nivel sin presión.
                    </p>

                    <div class="mt-10 space-y-3 border-t border-gray-100 pt-8">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 text-purple-primary font-black">✓</span>
                            <p class="text-gray-700 font-medium">Fundamentos, grooves, combos, musicalidad y freestyle</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 text-purple-primary font-black">✓</span>
                            <p class="text-gray-700 font-medium">Para personas que quieren empezar o subir de nivel sin presión</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 text-purple-primary font-black">✓</span>
                            <p class="text-gray-700 font-medium">Clases claras + práctica guiada + repetición para dominar</p>
                        </div>
                    </div>

                    <div class="mt-10">
                        <a href="{{ route('register') }}" wire:navigate
                           class="inline-flex items-center justify-center px-8 py-4 bg-purple-primary hover:bg-purple-dark text-white font-bold rounded-xl shadow-lg shadow-purple-100 hover:shadow-xl transition duration-300">
                            Empezar ahora
                        </a>
                    </div>
                </div>

                <div class="lg:w-7/12 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach([
                        ['title' => 'Fundamentos', 'meta' => 'Lo esencial para empezar', 'text' => 'Postura, timing, control y moves base para construir seguridad.', 'bullets' => ['Moves esenciales', 'Postura y líneas', 'Timing y musicalidad'], 'icon' => 'M10 6a2 2 0 012-2h0a2 2 0 012 2v2h2a2 2 0 012 2v0a2 2 0 01-2 2h-2v2a2 2 0 01-2 2h0a2 2 0 01-2-2v-2H8a2 2 0 01-2-2v0a2 2 0 012-2h2V6z'],
                        ['title' => 'Grooves & musicalidad', 'meta' => 'Que se sienta vivo', 'text' => 'Grooves entrenables para conectar con la música y soltar el cuerpo.', 'bullets' => ['Grooves base', 'Acentos y pausas', 'Energía y actitud'], 'icon' => 'M12 2l3 7h7l-5.5 4 2 7L12 16l-6.5 4 2-7L2 9h7z'],
                        ['title' => 'Combos & variaciones', 'meta' => 'Sube de nivel', 'text' => 'Secuencias para mejorar coordinación, limpieza y variaciones con sabor.', 'bullets' => ['Combos guiados', 'Variaciones', 'Limpieza y control'], 'icon' => 'M8 5v14l11-7z'],
                        ['title' => 'Freestyle & coreos', 'meta' => 'Escenario y cypher', 'text' => 'Herramientas para improvisar y rutinas para presencia y performance.', 'bullets' => ['Freestyle tools', 'Coreografías', 'Presencia y performance'], 'icon' => 'M12 21a9 9 0 100-18 9 9 0 000 18zm0-4a1 1 0 110-2 1 1 0 010 2zm1-12h-2v8h2V5z'],
                    ] as $card)
                        <div class="group flex flex-col p-6 bg-white border border-gray-100 hover:border-purple-primary/40 transition-all duration-300 rounded-2xl shadow-lg hover:shadow-xl page-enter">
                            <div class="p-3 bg-gray-50 text-purple-primary rounded-xl w-fit mb-4 group-hover:bg-purple-primary group-hover:text-white transition-colors duration-300">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="{{ $card['icon'] }}"/></svg>
                            </div>
                            <h3 class="font-display font-bold text-lg text-gray-900 mb-1">{{ $card['title'] }}</h3>
                            <p class="text-xs text-purple-primary font-bold uppercase tracking-wider mb-3">{{ $card['meta'] }}</p>
                            <p class="text-gray-600 font-medium leading-relaxed text-sm">{{ $card['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-24 bg-gray-50 border-y border-gray-100" id="video-demo">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row items-center gap-16">
                    <div class="lg:w-5/12 space-y-8 order-2 lg:order-1 page-enter">
                        <p class="text-purple-primary font-bold tracking-[0.2em] uppercase text-sm">Aprende a tu manera</p>
                        <h2 class="font-display font-bold text-4xl md:text-5xl text-black leading-tight">
                            Entrena a <span class="text-purple-primary italic font-normal">tu ritmo</span>
                        </h2>
                        <p class="text-gray-600 font-medium text-lg leading-relaxed">
                            Clases diseñadas para que practiques de verdad. Repite cada clase las veces que necesites, corrige detalles y avanza con seguridad.
                        </p>
                        <ul class="space-y-5 mt-8 border-t border-gray-100 pt-8">
                            <li class="flex items-start gap-4 text-gray-900">
                                <div class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm shrink-0">
                                    <svg class="w-5 h-5 text-purple-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold block">Sin horarios fijos</span>
                                    <span class="text-gray-600 text-sm">Entrena cuando quieras y repite cada clase sin límite</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4 text-gray-900">
                                <div class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm shrink-0">
                                    <svg class="w-5 h-5 text-purple-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold block">Ruta clara por nivel</span>
                                    <span class="text-gray-600 text-sm">De fundamentos a freestyle, paso a paso</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4 text-gray-900">
                                <div class="bg-white p-2.5 rounded-xl border border-gray-100 shadow-sm shrink-0">
                                    <svg class="w-5 h-5 text-purple-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold block">Progreso real</span>
                                    <span class="text-gray-600 text-sm">Técnica, groove, musicalidad y presencia escénica</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="lg:w-7/12 w-full order-1 lg:order-2 page-enter" style="animation-delay: 0.05s;">
                        <div class="relative bg-black rounded-2xl overflow-hidden shadow-2xl shadow-gray-200 aspect-video group border border-gray-100">
                            <iframe
                                class="absolute inset-0 w-full h-full"
                                src="https://www.youtube.com/embed/8b18KD5O3y8?rel=0&modestbranding=1"
                                title="Fragmento de clase"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                        </div>

                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="rounded-2xl overflow-hidden border border-gray-100 bg-black shadow-xl aspect-video">
                                <iframe
                                    class="w-full h-full"
                                    src="https://www.youtube.com/embed/sE9yBbvekb0?rel=0&modestbranding=1"
                                    title="Fragmento de entrenamiento"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen></iframe>
                            </div>
                            <div class="rounded-2xl overflow-hidden border border-gray-100 bg-black shadow-xl aspect-video">
                                <iframe
                                    class="w-full h-full"
                                    src="https://www.youtube.com/embed/oMY7XiHefVM?rel=0&modestbranding=1"
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
                    <h2 class="font-display font-bold text-4xl mb-4 text-gray-900">Instructoras</h2>
                </div>

                @php
                    $instructorName = 'Tatiana Cerna';
                    $instructorPhoto = asset('images/imagen2.jpg');
                    $instructorIg = 'https://www.instagram.com/girls_lockers/';
                @endphp

                <div class="bg-white rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden page-enter">
                    <div class="flex flex-col lg:flex-row">
                        <div class="lg:w-5/12 relative h-[420px] lg:h-auto">
                            <img alt="{{ $instructorName }}" class="w-full h-full object-cover object-center grayscale hover:grayscale-0 transition-all duration-700" src="{{ $instructorPhoto }}">
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

                            <p class="text-lg md:text-xl text-gray-600 font-medium mb-8 italic border-l-4 border-purple-primary pl-6 py-2">
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
                                   class="bg-purple-primary hover:bg-purple-dark text-white px-8 py-4 rounded-xl font-bold text-center transition duration-300 shadow-lg shadow-purple-100 hover:shadow-xl">
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
        <section class="py-24 bg-white" id="planes">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16 page-enter">
                    <h2 class="font-display font-bold text-4xl mb-4 text-gray-900">Membresía</h2>
                    <p class="text-gray-600 font-medium text-lg">Invierte en tu formación y entrena con estructura.</p>
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
                                <span class="text-purple-primary text-lg mr-3 mt-0.5">✓</span> Ruta clara por nivel: básico → intermedio → coreos → freestyle
                            </li>
                            <li class="flex items-start text-gray-700 text-sm">
                                <span class="text-purple-primary text-lg mr-3 mt-0.5">✓</span> Enfocado en progreso real (técnica + groove + musicalidad)
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
                            @if($savings > 0)
                                <p class="text-sm text-purple-600 mt-2 font-semibold">Ahorra {{ $currencySymbol }}{{ $fmtPrice($savings) }}</p>
                            @endif
                        </div>
                        <hr class="my-6 border-gray-100"/>
                        <ul class="space-y-4 mb-8 flex-1">
                            <li class="flex items-start text-gray-700 text-sm">
                                <span class="text-purple-600 text-lg mr-3 mt-0.5">✓</span> <span class="font-bold text-gray-900">Todo lo del plan mensual</span>
                            </li>
                            <li class="flex items-start text-gray-700 text-sm">
                                <span class="text-purple-600 text-lg mr-3 mt-0.5">✓</span> Profundizas en variaciones, limpieza y performance
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
                            Aprende desde <span class="text-purple-primary italic font-normal">cero</span>
                        </h2>
                        <p class="mission-text-1 text-gray-600 text-lg md:text-2xl mt-6 font-medium leading-relaxed">
                            Ya sea que estés empezando o tengas experiencia, encuentra el curso adecuado para tu nivel y sigue creciendo.
                        </p>
                        <div class="mt-10">
                            <a href="{{ route('register') }}" wire:navigate
                               class="bg-purple-primary hover:bg-purple-dark text-white px-8 py-4 rounded-xl font-bold text-center transition duration-300 shadow-lg shadow-purple-100 hover:shadow-xl inline-flex">
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
        <footer class="py-12 bg-white border-t border-gray-100">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div>
                        <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" class="h-10 w-auto object-contain mb-4">
                    </div>

                    <div>
                        <h4 class="font-display font-bold text-gray-900 mb-4">Enlaces</h4>
                        <ul class="space-y-2">
                            <li><a href="#instructoras" class="text-gray-600 hover:text-purple-primary transition-colors font-medium">Instructoras</a></li>
                            <li><a href="#planes" class="text-gray-600 hover:text-purple-primary transition-colors font-medium">Planes</a></li>
                            <li><a href="#video-demo" class="text-gray-600 hover:text-purple-primary transition-colors font-medium">Demo</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-display font-bold text-gray-900 mb-4">Cuenta</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('register') }}" wire:navigate class="text-gray-600 hover:text-purple-primary transition-colors font-medium">Registrarse</a></li>
                            <li><a href="{{ route('login') }}" wire:navigate class="text-gray-600 hover:text-purple-primary transition-colors font-medium">Iniciar sesión</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-8 text-center">
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
