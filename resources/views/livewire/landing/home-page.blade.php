<div class="min-h-screen bg-white">
    <!-- Hero Section - Video con Máscara Oscura -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden" id="hero">
        <!-- Background Video de YouTube -->
        <div class="absolute inset-0">
            <!-- Video de YouTube en modo autoplay y loop -->
            <iframe
                class="absolute top-1/2 left-1/2 w-[177.77777778vh] h-[56.25vw] min-h-screen min-w-full -translate-x-1/2 -translate-y-1/2"
                src="https://www.youtube.com/embed/HefC_rMCs-Q?autoplay=1&mute=1&loop=1&playlist=HefC_rMCs-Q&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3&playsinline=1"
                title="Girls Lockers Hero Video"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen>
            </iframe>
            <!-- Máscara degradado oscura para contraste -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/60 to-black/80"></div>
        </div>

        <div class="relative z-10 container mx-auto px-4 py-20 text-center">
            <div class="max-w-4xl mx-auto">
                <!-- Titular Principal en Blanco -->
                <h1 class="font-display font-black text-5xl md:text-7xl lg:text-8xl mb-6 leading-tight page-enter">
                    <span class="text-white">TU ESPACIO.</span>
                    <br>
                    <span class="text-white">TU RITMO.</span>
                    <br>
                    <span class="text-purple-light">TU PODER.</span>
                </h1>

                <p class="text-white/90 text-xl md:text-2xl max-w-3xl mx-auto mb-12 page-enter font-medium" style="animation-delay: 0.1s;">
                    La primera comunidad y plataforma de aprendizaje de locking,<br class="hidden md:block">
                    creada <span class="text-purple-light font-bold">por</span> y <span class="text-purple-light font-bold">para</span> mujeres.
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center page-enter" style="animation-delay: 0.2s;">
                    <a href="{{ route('register') }}" wire:navigate
                       class="px-10 py-5 bg-purple-primary hover:bg-purple-dark text-white font-bold text-lg rounded-lg shadow-purple-glow-lg transition-all duration-300 transform hover:scale-105">
                        ÚNETE A LA COMUNIDAD
                    </a>
                    <a href="#boveda"
                       class="px-10 py-5 bg-white/20 backdrop-blur-sm border-2 border-white text-white hover:bg-white hover:text-purple-primary font-bold text-lg rounded-lg transition-all duration-300">
                        EXPLORA LA BÓVEDA
                    </a>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Nuestra Misión Section - Fondo con Gradiente -->
    <section class="py-24 bg-gradient-to-br from-purple-ultralight via-white to-gray-ultralight relative overflow-hidden" id="mission">
        <!-- Background Pattern de Gotas -->
        <div class="absolute inset-0 opacity-5">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="drops" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                        <!-- Gotas -->
                        <ellipse cx="20" cy="30" rx="8" ry="12" fill="currentColor" class="text-purple-primary"/>
                        <ellipse cx="60" cy="15" rx="6" ry="10" fill="currentColor" class="text-purple-primary"/>
                        <ellipse cx="45" cy="60" rx="7" ry="11" fill="currentColor" class="text-purple-primary"/>
                        <ellipse cx="80" cy="75" rx="9" ry="13" fill="currentColor" class="text-purple-primary"/>
                        <ellipse cx="15" cy="85" rx="5" ry="9" fill="currentColor" class="text-purple-primary"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#drops)"/>
            </svg>
        </div>

        <!-- Liquid Blobs Animados con Motion -->
        <div class="mission-blob-1 absolute top-20 -left-20 w-96 h-96 bg-purple-primary/5 rounded-full filter blur-3xl"></div>
        <div class="mission-blob-2 absolute top-40 -right-20 w-96 h-96 bg-purple-light/5 rounded-full filter blur-3xl"></div>
        <div class="mission-blob-3 absolute -bottom-20 left-1/3 w-96 h-96 bg-purple-dark/5 rounded-full filter blur-3xl"></div>

        <!-- Emoticones de Candados Decorativos con Motion -->
        <div class="mission-lock-1 absolute top-10 left-10 text-6xl opacity-10">🔒</div>
        <div class="mission-lock-2 absolute top-32 right-20 text-5xl opacity-10">🔓</div>
        <div class="mission-lock-3 absolute bottom-20 left-32 text-7xl opacity-10">🔒</div>
        <div class="mission-lock-4 absolute bottom-40 right-16 text-4xl opacity-10">🔓</div>
        <div class="mission-lock-5 absolute top-1/2 left-1/4 text-5xl opacity-10">🔒</div>
        <div class="mission-lock-6 absolute top-1/3 right-1/3 text-6xl opacity-10">🔓</div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Grid de Imágenes Animadas - Solo visible en pantalla grande -->
                    <div class="order-2 lg:order-1 hidden lg:block">
                        <div class="mission-gallery grid grid-cols-2 gap-4">
                            <!-- Imagen 1 -->
                            <div class="mission-gallery-item-1 relative rounded-2xl overflow-hidden shadow-xl aspect-[4/5] group cursor-pointer">
                                <img src="{{ asset('images/girlslockers.jpg') }}"
                                     alt="Girls Lockers Community 1"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-purple-primary/60 to-transparent opacity-80 group-hover:opacity-40 transition-opacity"></div>
                            </div>

                            <!-- Imagen 2 -->
                            <div class="mission-gallery-item-2 relative rounded-2xl overflow-hidden shadow-xl aspect-[4/5] group cursor-pointer mt-8">
                                <img src="{{ asset('images/girlslockers.jpg') }}"
                                     alt="Girls Lockers Community 2"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-purple-light/60 to-transparent opacity-80 group-hover:opacity-40 transition-opacity"></div>
                            </div>

                            <!-- Imagen 3 -->
                            <div class="mission-gallery-item-3 relative rounded-2xl overflow-hidden shadow-xl aspect-[4/5] group cursor-pointer -mt-4">
                                <img src="{{ asset('images/girlslockers.jpg') }}"
                                     alt="Girls Lockers Community 3"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-purple-dark/60 to-transparent opacity-80 group-hover:opacity-40 transition-opacity"></div>
                            </div>

                            <!-- Imagen 4 -->
                            <div class="mission-gallery-item-4 relative rounded-2xl overflow-hidden shadow-xl aspect-[4/5] group cursor-pointer mt-4">
                                <img src="{{ asset('images/girlslockers.jpg') }}"
                                     alt="Girls Lockers Community 4"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-purple-primary/60 to-transparent opacity-80 group-hover:opacity-40 transition-opacity"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Imagen única para móvil -->
                    <div class="order-2 lg:order-1 lg:hidden">
                        <div class="mission-image relative rounded-2xl overflow-hidden shadow-2xl">
                            <img src="{{ asset('images/girlslockers.jpg') }}"
                                 alt="Girls Lockers Fundadoras"
                                 class="w-full h-[500px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-purple-primary/30 to-transparent"></div>
                        </div>
                    </div>

                    <!-- Texto de la misión -->
                    <div class="order-1 lg:order-2">
                        <h2 class="mission-title font-display font-black text-5xl md:text-7xl text-black mb-8 leading-tight">
                            Nuestra <span class="text-purple-primary">Misión</span>
                        </h2>
                        <div class="space-y-8 text-gray-dark text-2xl md:text-3xl leading-relaxed font-bold">
                            <p class="mission-text-1">
                                Un espacio seguro para <span class="text-purple-primary">aprender</span> y <span class="text-purple-primary">dominar</span> el locking.
                            </p>
                            <p class="mission-text-2 text-black text-3xl md:text-4xl">
                                Una comunidad.<br>Una familia.<br>Un movimiento.
                            </p>
                        </div>

                        <!-- Stats pequeñas -->
                        <div class="grid grid-cols-3 gap-4 mt-8">
                            <div class="mission-stat-1 text-center p-4 bg-purple-ultralight rounded-xl cursor-pointer">
                                <div class="text-3xl font-black text-purple-primary mb-1">500+</div>
                                <div class="text-gray-dark text-sm uppercase tracking-wider">Lockers</div>
                            </div>
                            <div class="mission-stat-2 text-center p-4 bg-purple-ultralight rounded-xl cursor-pointer">
                                <div class="text-3xl font-black text-purple-primary mb-1">50+</div>
                                <div class="text-gray-dark text-sm uppercase tracking-wider">Lecciones</div>
                            </div>
                            <div class="mission-stat-3 text-center p-4 bg-purple-ultralight rounded-xl cursor-pointer">
                                <div class="text-3xl font-black text-purple-primary mb-1">24/7</div>
                                <div class="text-gray-dark text-sm uppercase tracking-wider">Acceso</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ¿Qué Encontrarás en Girls Lockers? - Fondo Gris Claro -->
    <section class="py-24 bg-gray-ultralight relative overflow-hidden" id="features">
        <!-- Blobs de fondo -->
        <div class="absolute -top-20 right-0 w-[600px] h-[600px] bg-purple-primary/5 rounded-full filter blur-3xl animate-blob-slower"></div>
        <div class="absolute bottom-0 -left-20 w-[500px] h-[500px] bg-purple-light/5 rounded-full filter blur-3xl animate-blob" style="animation-delay: 4s;"></div>
        <div class="container mx-auto px-4">
            <h2 class="features-title font-display font-black text-5xl md:text-7xl text-center text-black mb-6 leading-tight">
                ¿Qué Encontrarás en <span class="font-script text-purple-primary bg-gradient-to-r from-purple-primary to-purple-light bg-clip-text text-transparent italic text-6xl md:text-8xl">Girls Lockers</span>?
            </h2>
            <p class="features-subtitle text-gray-dark text-center text-xl md:text-2xl mb-20 max-w-3xl mx-auto font-bold tracking-wide">
                Todo lo que necesitas para dominar el locking, en un solo lugar
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Columna 1: Clases Exclusivas -->
                <div class="feature-card-1 bg-white rounded-3xl p-8 shadow-2xl hover:shadow-purple-glow transition-all duration-500 transform hover:-translate-y-4 border border-gray-light relative overflow-hidden group">
                    <!-- Efecto de brillo animado -->
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-primary/0 via-purple-primary/5 to-purple-primary/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <div class="relative z-10">
                        <div class="flex justify-center mb-6">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-primary to-purple-light rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-display font-black text-3xl text-black text-center mb-4 tracking-tight group-hover:text-purple-primary transition-colors duration-300">
                            Clases<br>Exclusivas
                        </h3>
                        <p class="text-gray-dark text-center text-lg leading-relaxed font-medium mb-6">
                            Aprende desde los fundamentos hasta combos avanzados con nuestras instructoras expertas. Contenido nuevo cada semana.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center text-gray-dark font-semibold">
                                <span class="text-2xl text-purple-primary mr-3">✓</span>
                                <span>Progresión estructurada</span>
                            </li>
                            <li class="flex items-center text-gray-dark font-semibold">
                                <span class="text-2xl text-purple-primary mr-3">✓</span>
                                <span>Videos en alta calidad</span>
                            </li>
                            <li class="flex items-center text-gray-dark font-semibold">
                                <span class="text-2xl text-purple-primary mr-3">✓</span>
                                <span>Acceso ilimitado</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Columna 2: La Bóveda de Recursos -->
                <div class="feature-card-2 bg-gradient-to-br from-purple-primary to-purple-dark rounded-3xl p-8 shadow-2xl hover:shadow-purple-glow-lg transition-all duration-500 transform hover:-translate-y-4 md:scale-105 relative overflow-hidden group">
                    <!-- Efecto de estrellas -->
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute top-4 right-4 text-3xl">✨</div>
                        <div class="absolute top-16 left-8 text-2xl">⭐</div>
                        <div class="absolute bottom-8 right-12 text-2xl">💫</div>
                    </div>

                    <div class="relative z-10">
                        <div class="flex justify-center mb-6">
                            <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center shadow-2xl transform group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300">
                                <svg class="w-12 h-12 text-purple-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-display font-black text-3xl text-white text-center mb-4 tracking-tight">
                            La Bóveda de<br>Recursos
                        </h3>
                        <p class="text-white/90 text-center text-lg leading-relaxed font-medium mb-6">
                            Accede a nuestra colección curada de videos clásicos, entrevistas y galerías de inspiración para nutrir tu cultura locker.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center text-white font-semibold">
                                <span class="text-2xl text-purple-ultralight mr-3">✓</span>
                                <span>Historia del locking</span>
                            </li>
                            <li class="flex items-center text-white font-semibold">
                                <span class="text-2xl text-purple-ultralight mr-3">✓</span>
                                <span>Batallas icónicas</span>
                            </li>
                            <li class="flex items-center text-white font-semibold">
                                <span class="text-2xl text-purple-ultralight mr-3">✓</span>
                                <span>Galería de inspiración</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Columna 3: Conecta y Comparte -->
                <div class="feature-card-3 bg-white rounded-3xl p-8 shadow-2xl hover:shadow-purple-glow transition-all duration-500 transform hover:-translate-y-4 border border-gray-light relative overflow-hidden group">
                    <!-- Efecto de brillo animado -->
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-light/0 via-purple-light/5 to-purple-light/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <div class="relative z-10">
                        <div class="flex justify-center mb-6">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-light to-purple-primary rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-display font-black text-3xl text-black text-center mb-4 tracking-tight group-hover:text-purple-primary transition-colors duration-300">
                            Conecta y<br>Comparte
                        </h3>
                        <p class="text-gray-dark text-center text-lg leading-relaxed font-medium mb-6">
                            Forma parte de nuestro cypher digital. Comparte tu progreso, recibe feedback y participa en retos con lockers de todo el mundo.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center text-gray-dark font-semibold">
                                <span class="text-2xl text-purple-primary mr-3">✓</span>
                                <span>Comunidad global</span>
                            </li>
                            <li class="flex items-center text-gray-dark font-semibold">
                                <span class="text-2xl text-purple-primary mr-3">✓</span>
                                <span>Feedback constructivo</span>
                            </li>
                            <li class="flex items-center text-gray-dark font-semibold">
                                <span class="text-2xl text-purple-primary mr-3">✓</span>
                                <span>Challenges mensuales</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- La Bóveda de Recursos - Showcase -->
    <section class="py-24 bg-white relative overflow-hidden" id="boveda">
        <!-- Blobs grandes animados -->
        <div class="absolute top-1/4 -left-32 w-[700px] h-[700px] bg-purple-primary/5 rounded-full filter blur-3xl animate-blob"></div>
        <div class="absolute bottom-1/4 -right-32 w-[600px] h-[600px] bg-purple-light/5 rounded-full filter blur-3xl animate-blob-slow" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-purple-dark/5 rounded-full filter blur-3xl animate-blob-slower"></div>
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="font-display font-black text-4xl md:text-5xl text-black mb-6">
                    La <span class="text-purple-primary">Bóveda</span> de Recursos
                </h2>
                <p class="text-gray-dark text-lg max-w-3xl mx-auto">
                    Descubre la colección más completa de contenido de locking curado especialmente para ti
                </p>
            </div>

            <!-- Clases Destacadas -->
            <div class="mb-16">
                <h3 class="font-display font-bold text-2xl text-purple-primary mb-8 flex items-center">
                    <span class="bg-purple-ultralight p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-purple-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </span>
                    Clases Destacadas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @if($featuredCourses && $featuredCourses->count() > 0)
                        @foreach($featuredCourses->take(3) as $course)
                        <a href="{{ route('login') }}" wire:navigate class="group">
                            <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-purple-glow transition-all duration-300 transform hover:-translate-y-1 border border-gray-light">
                                <div class="relative h-48 bg-gray-ultralight overflow-hidden">
                                    @if($course->thumbnail_url)
                                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-primary to-purple-light">
                                            <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-3 right-3">
                                        <span class="bg-purple-primary text-white text-xs font-bold px-3 py-1 rounded-full uppercase">
                                            {{ $course->level === 'beginner' ? 'Principiante' : ($course->level === 'intermediate' ? 'Intermedio' : 'Avanzado') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h4 class="font-display font-bold text-xl text-black mb-2 group-hover:text-purple-primary transition-colors">
                                        {{ $course->title }}
                                    </h4>
                                    <p class="text-gray-dark text-sm line-clamp-2">
                                        {{ $course->description }}
                                    </p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    @else
                        <!-- Placeholder si no hay cursos -->
                        @for($i = 1; $i <= 3; $i++)
                        <div class="bg-white rounded-xl overflow-hidden shadow-lg border border-gray-light">
                            <div class="h-48 bg-gradient-to-br from-purple-primary to-purple-light flex items-center justify-center">
                                <div class="text-center text-white">
                                    <svg class="w-16 h-16 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="font-bold">Clase {{ $i }}</p>
                                </div>
                            </div>
                            <div class="p-6">
                                <h4 class="font-display font-bold text-xl text-black mb-2">
                                    Próximamente
                                </h4>
                                <p class="text-gray-dark text-sm">
                                    Estamos preparando contenido increíble para ti
                                </p>
                            </div>
                        </div>
                        @endfor
                    @endif
                </div>
            </div>

            <!-- Videos Clave de YouTube -->
            <div class="mb-16">
                <h3 class="font-display font-bold text-2xl text-black mb-8 flex items-center">
                    <span class="bg-purple-ultralight p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-purple-primary" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </span>
                    Historia del Locking & Batallas Icónicas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Video 1 -->
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-purple-glow transition-all duration-300 border border-gray-light">
                        <div class="aspect-video">
                            <iframe
                                class="w-full h-full"
                                src="https://www.youtube.com/embed/HefC_rMCs-Q"
                                title="Historia del Locking"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class="p-6">
                            <span class="inline-block bg-purple-ultralight text-purple-primary text-xs font-bold px-3 py-1 rounded-full uppercase mb-3">
                                Historia
                            </span>
                            <h4 class="font-display font-bold text-lg text-black">
                                Los Orígenes del Locking
                            </h4>
                        </div>
                    </div>

                    <!-- Video 2 -->
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-purple-glow transition-all duration-300 border border-gray-light">
                        <div class="aspect-video">
                            <iframe
                                class="w-full h-full"
                                src="https://www.youtube.com/embed/8b18KD5O3y8"
                                title="Batalla Icónica"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class="p-6">
                            <span class="inline-block bg-purple-ultralight text-purple-primary text-xs font-bold px-3 py-1 rounded-full uppercase mb-3">
                                Batalla
                            </span>
                            <h4 class="font-display font-bold text-lg text-black">
                                Momentos Legendarios
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Galería de Inspiración -->
            <div>
                <h3 class="font-display font-bold text-2xl text-black mb-8 flex items-center">
                    <span class="bg-purple-ultralight p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-purple-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </span>
                    Galería de Inspiración
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="relative aspect-square rounded-lg overflow-hidden group cursor-pointer shadow-lg hover:shadow-purple-glow transition-all duration-300 border border-gray-light">
                        <img src="{{ asset('images/girlslockers.jpg') }}"
                             alt="Locking Inspiration {{ $i }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-purple-primary/60 via-purple-primary/20 to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <p class="text-white font-bold text-sm">Estilo Único #{{ $i }}</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonios Section -->
    <section class="py-32 bg-gradient-to-br from-gray-ultralight via-white to-purple-ultralight relative overflow-hidden" id="testimonials">
        <!-- Blobs de testimonios animados -->
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-purple-primary/10 rounded-full filter blur-3xl animate-blob-slower"></div>
        <div class="absolute bottom-0 right-0 w-[450px] h-[450px] bg-purple-light/10 rounded-full filter blur-3xl animate-blob" style="animation-delay: 3s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-purple-dark/5 rounded-full filter blur-3xl animate-blob-slow"></div>

        <!-- Elementos decorativos -->
        <div class="absolute top-20 right-20 text-6xl opacity-10 animate-float">💜</div>
        <div class="absolute bottom-32 left-16 text-5xl opacity-10 animate-float-slow" style="animation-delay: 1s;">✨</div>
        <div class="absolute top-1/3 left-10 text-4xl opacity-10 animate-float" style="animation-delay: 2s;">🎵</div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-20">
                <h2 class="testimonials-title font-display font-black text-5xl md:text-7xl text-black mb-6 leading-tight">
                    Lo Que Dicen Nuestras<br>
                    <span class="bg-gradient-to-r from-purple-primary via-purple-light to-purple-primary bg-clip-text text-transparent">Lockers</span>
                </h2>
                <p class="testimonials-subtitle text-gray-dark text-xl md:text-2xl max-w-3xl mx-auto font-bold italic tracking-wide">
                    Historias reales de mujeres que están transformando su baile y su confianza
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-7xl mx-auto">
                <!-- Testimonio 1 -->
                <div class="testimonial-card-1 bg-white/90 backdrop-blur-sm rounded-3xl p-8 shadow-2xl hover:shadow-purple-glow-lg border-4 border-purple-primary/20 hover:border-purple-primary transition-all duration-500 relative overflow-hidden group transform hover:-rotate-1">
                    <!-- Efecto de destello -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-purple-primary/20 rounded-full filter blur-2xl group-hover:scale-150 transition-transform duration-700"></div>

                    <div class="relative z-10">
                        <!-- Quote decorativa -->
                        <div class="absolute -top-4 -left-4 w-20 h-20 opacity-10">
                            <svg class="w-full h-full text-purple-primary" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>

                        <div class="flex items-center mb-6">
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-primary via-purple-light to-purple-primary flex items-center justify-center text-white font-black text-2xl shadow-xl transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                MG
                            </div>
                            <div class="ml-5">
                                <p class="text-black font-black text-xl tracking-tight">@LockerGirl_Lima</p>
                                <p class="text-purple-primary font-bold text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    Lima, Perú
                                </p>
                            </div>
                        </div>

                        <p class="text-gray-dark text-lg leading-relaxed font-medium">
                            <span class="text-5xl text-purple-primary/30 leading-none">"</span>
                            Aquí encontré la <span class="font-bold text-purple-primary">confianza</span> para empezar a batallar. La comunidad es increíble y las instructoras son top nivel.
                            <span class="text-5xl text-purple-primary/30 leading-none">"</span>
                        </p>

                        <!-- Estrellas de rating -->
                        <div class="flex gap-1 mt-6">
                            <span class="text-yellow-400 text-2xl">⭐</span>
                            <span class="text-yellow-400 text-2xl">⭐</span>
                            <span class="text-yellow-400 text-2xl">⭐</span>
                            <span class="text-yellow-400 text-2xl">⭐</span>
                            <span class="text-yellow-400 text-2xl">⭐</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonio 2 - Destacado -->
                <div class="testimonial-card-2 bg-gradient-to-br from-purple-primary via-purple-dark to-purple-primary rounded-3xl p-8 shadow-2xl hover:shadow-purple-glow-lg border-4 border-purple-light md:scale-105 transition-all duration-500 relative overflow-hidden group">
                    <!-- Efecto de brillo -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-white/0 via-white/10 to-white/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <!-- Estrellas decorativas -->
                    <div class="absolute top-4 right-4 text-4xl animate-pulse">✨</div>
                    <div class="absolute bottom-8 left-6 text-3xl animate-pulse" style="animation-delay: 0.5s;">💫</div>

                    <div class="relative z-10">
                        <!-- Quote decorativa -->
                        <div class="absolute -top-4 -left-4 w-20 h-20 opacity-20">
                            <svg class="w-full h-full text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>

                        <div class="flex items-center mb-6">
                            <div class="w-20 h-20 rounded-2xl bg-white flex items-center justify-center text-purple-primary font-black text-2xl shadow-2xl transform group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300">
                                SK
                            </div>
                            <div class="ml-5">
                                <p class="text-white font-black text-xl tracking-tight">@Soul_Locker_Mx</p>
                                <p class="text-purple-ultralight font-bold text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    Ciudad de México
                                </p>
                            </div>
                        </div>

                        <p class="text-white text-lg leading-relaxed font-medium">
                            <span class="text-5xl text-white/30 leading-none">"</span>
                            Por fin un espacio donde puedo ser <span class="font-bold text-purple-ultralight">yo misma</span>. Las lecciones son claras y la progresión es perfecta para principiantes.
                            <span class="text-5xl text-white/30 leading-none">"</span>
                        </p>

                        <!-- Estrellas de rating -->
                        <div class="flex gap-1 mt-6">
                            <span class="text-yellow-300 text-2xl">⭐</span>
                            <span class="text-yellow-300 text-2xl">⭐</span>
                            <span class="text-yellow-300 text-2xl">⭐</span>
                            <span class="text-yellow-300 text-2xl">⭐</span>
                            <span class="text-yellow-300 text-2xl">⭐</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonio 3 -->
                <div class="testimonial-card-3 bg-white/90 backdrop-blur-sm rounded-3xl p-8 shadow-2xl hover:shadow-purple-glow-lg border-4 border-purple-light/20 hover:border-purple-light transition-all duration-500 relative overflow-hidden group transform hover:rotate-1">
                    <!-- Efecto de destello -->
                    <div class="absolute -top-24 -left-24 w-48 h-48 bg-purple-light/20 rounded-full filter blur-2xl group-hover:scale-150 transition-transform duration-700"></div>

                    <div class="relative z-10">
                        <!-- Quote decorativa -->
                        <div class="absolute -top-4 -left-4 w-20 h-20 opacity-10">
                            <svg class="w-full h-full text-purple-light" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>

                        <div class="flex items-center mb-6">
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-light via-purple-primary to-purple-light flex items-center justify-center text-white font-black text-2xl shadow-xl transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                LP
                            </div>
                            <div class="ml-5">
                                <p class="text-black font-black text-xl tracking-tight">@LaPunkera</p>
                                <p class="text-purple-primary font-bold text-sm flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    Buenos Aires
                                </p>
                            </div>
                        </div>

                        <p class="text-gray-dark text-lg leading-relaxed font-medium">
                            <span class="text-5xl text-purple-light/30 leading-none">"</span>
                            La bóveda de recursos es <span class="font-bold text-purple-primary">oro puro</span>. Videos históricos que nunca había visto. Esto es cultura locker de verdad.
                            <span class="text-5xl text-purple-light/30 leading-none">"</span>
                        </p>

                        <!-- Estrellas de rating -->
                        <div class="flex gap-1 mt-6">
                            <span class="text-yellow-400 text-2xl">⭐</span>
                            <span class="text-yellow-400 text-2xl">⭐</span>
                            <span class="text-yellow-400 text-2xl">⭐</span>
                            <span class="text-yellow-400 text-2xl">⭐</span>
                            <span class="text-yellow-400 text-2xl">⭐</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-24 bg-gradient-to-br from-purple-primary via-purple-dark to-purple-primary relative overflow-hidden">
        <!-- Pattern overlay -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.1) 35px, rgba(255,255,255,.1) 70px);"></div>
        </div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <h2 class="font-display font-black text-5xl md:text-7xl text-white mb-6 leading-tight">
                ¿Lista para el <span class="text-purple-ultralight">lock</span>?
            </h2>
            <p class="text-white/90 text-xl md:text-2xl max-w-2xl mx-auto mb-12 font-medium">
                Únete a cientos de lockers que ya están dominando su arte
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ route('register') }}" wire:navigate
                   class="px-12 py-6 bg-white hover:bg-gray-ultralight text-purple-primary font-bold text-xl rounded-lg shadow-2xl transition-all duration-300 transform hover:scale-105">
                    COMIENZA TU VIAJE LOCKER
                </a>
                <a href="#boveda"
                   class="px-12 py-6 bg-black/20 backdrop-blur-sm border-2 border-white hover:bg-white hover:text-purple-primary text-white font-bold text-xl rounded-lg shadow-xl transition-all duration-300">
                    EXPLORA GRATIS
                </a>
            </div>

            <!-- Social Proof -->
            <div class="mt-16 flex flex-wrap justify-center items-center gap-8 text-white/90">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span class="font-bold">500+ Estudiantes Felices</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-bold">Instructoras Certificadas</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-bold">Acceso 24/7</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 bg-white border-t border-gray-light">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Logo y descripción -->
                <div>
                    <h3 class="font-script text-3xl font-bold text-purple-primary mb-4 italic">Girls Lockers</h3>
                    <p class="text-gray-dark text-sm leading-relaxed">
                        La primera comunidad de locking por y para mujeres. Empoderando lockers en todo el mundo.
                    </p>
                </div>

                <!-- Links rápidos -->
                <div>
                    <h4 class="font-display font-bold text-black mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('register') }}" wire:navigate class="text-gray-dark hover:text-purple-primary transition-colors">Registrarse</a></li>
                        <li><a href="{{ route('login') }}" wire:navigate class="text-gray-dark hover:text-purple-primary transition-colors">Iniciar Sesión</a></li>
                        <li><a href="#boveda" class="text-gray-dark hover:text-purple-primary transition-colors">La Bóveda</a></li>
                    </ul>
                </div>

                <!-- Social -->
                <div>
                    <h4 class="font-display font-bold text-black mb-4">Síguenos</h4>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 bg-purple-ultralight hover:bg-purple-primary text-purple-primary hover:text-white rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-purple-ultralight hover:bg-purple-primary text-purple-primary hover:text-white rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-purple-ultralight hover:bg-purple-primary text-purple-primary hover:text-white rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-light pt-8 text-center">
                <p class="text-gray-dark text-sm mb-2">
                    © 2025 <span class="font-script font-bold text-purple-primary text-2xl italic">Girls Lockers</span>. Todos los derechos reservados.
                </p>
                <p class="text-gray-medium text-xs">
                    Empoderando chicas lockers en todo el mundo 💪✨
                </p>
            </div>
        </div>
    </footer>
</div>

@push('scripts')
@vite(['resources/js/landing-animations.js'])
<script type="module">
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Page enter animations
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

    document.querySelectorAll('.page-enter').forEach(el => {
        observer.observe(el);
    });
</script>
@endpush
