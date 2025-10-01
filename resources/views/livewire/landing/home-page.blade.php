<div class="min-h-screen bg-purple-deep">
    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden" id="hero">
        <div class="absolute inset-0 bg-gradient-hero"></div>
        <div class="absolute inset-0 opacity-30" style="background: var(--gradient-glow);"></div>

        <div class="relative z-10 container mx-auto px-4 py-20 text-center">
            <h1 class="font-display text-4xl md:text-5xl lg:text-6xl mb-6 page-enter">
                <span class="text-pink-gradient">Empodera</span>
                <br>
                <span class="text-cream">tu movimiento</span>
            </h1>

            <p class="text-cream/80 text-lg md:text-xl max-w-2xl mx-auto mb-10 page-enter" style="animation-delay: 0.1s;">
                Escuela Internacional de Locking para chicas que quieren conquistar el mundo del street dance
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center page-enter" style="animation-delay: 0.2s;">
                <a href="{{ route('register') }}" wire:navigate class="btn-primary btn-pulse">
                    Comienza Gratis
                </a>
                <a href="#cursos" class="btn-secondary">
                    Ver Clases
                </a>
            </div>
        </div>
    </section>

    <!-- Vision Section -->
    <section class="py-20 bg-purple-darker">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="font-display text-3xl md:text-4xl text-cream mb-6">
                    Nuestra Visión
                </h2>
                <p class="text-cream/80 text-lg leading-relaxed mb-8">
                    En <span class="text-pink-vibrant font-accent">Girl Lockers</span>, creemos que cada chica tiene el poder de dominar el locking y convertirse en una fuerza imparable en el street dance mundial. Nuestra misión es empoderar a lockers de todo el mundo con contenido premium, instructores top y una comunidad global de apoyo.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                    <div class="card-glass text-center p-6">
                        <div class="text-pink-vibrant text-4xl mb-3">💪</div>
                        <h3 class="font-display text-xl text-cream mb-2">Empoderamiento</h3>
                        <p class="text-cream/70 text-sm">Desarrolla tu confianza y estilo único</p>
                    </div>
                    <div class="card-glass text-center p-6">
                        <div class="text-pink-vibrant text-4xl mb-3">🌍</div>
                        <h3 class="font-display text-xl text-cream mb-2">Comunidad Global</h3>
                        <p class="text-cream/70 text-sm">Conecta con lockers de todo el mundo</p>
                    </div>
                    <div class="card-glass text-center p-6">
                        <div class="text-pink-vibrant text-4xl mb-3">✨</div>
                        <h3 class="font-display text-xl text-cream mb-2">Excelencia</h3>
                        <p class="text-cream/70 text-sm">Aprende de las mejores instructoras</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 bg-purple-deep">
        <div class="container mx-auto px-4">
            <h2 class="font-display text-3xl md:text-4xl text-center text-cream mb-12">
                ¿Por qué <span class="text-pink-gradient">Girl Lockers</span>?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <div class="card-premium hover-lift">
                    <div class="text-pink-vibrant text-2xl mb-4">⏰</div>
                    <h3 class="font-display text-xl text-cream mb-3">Acceso 24/7</h3>
                    <p class="text-cream/70">Aprende a tu ritmo, cuando y donde quieras. Todas las lecciones disponibles en cualquier momento.</p>
                </div>
                <div class="card-premium hover-lift">
                    <div class="text-pink-vibrant text-2xl mb-4">👩‍🏫</div>
                    <h3 class="font-display text-xl text-cream mb-3">Instructoras Top</h3>
                    <p class="text-cream/70">Aprende de las mejores lockers del mundo con años de experiencia en competencias internacionales.</p>
                </div>
                <div class="card-premium hover-lift">
                    <div class="text-pink-vibrant text-2xl mb-4">📱</div>
                    <h3 class="font-display text-xl text-cream mb-3">Mobile-First</h3>
                    <p class="text-cream/70">Diseño optimizado para móvil. Practica desde tu teléfono en cualquier lugar.</p>
                </div>
                <div class="card-premium hover-lift">
                    <div class="text-pink-vibrant text-2xl mb-4">🎯</div>
                    <h3 class="font-display text-xl text-cream mb-3">Progresión Clara</h3>
                    <p class="text-cream/70">Cursos estructurados desde principiante hasta avanzado con objetivos claros.</p>
                </div>
                <div class="card-premium hover-lift">
                    <div class="text-pink-vibrant text-2xl mb-4">💬</div>
                    <h3 class="font-display text-xl text-cream mb-3">Comunidad Activa</h3>
                    <p class="text-cream/70">Comenta, comparte y conecta con otras lockers en cada lección.</p>
                </div>
                <div class="card-premium hover-lift">
                    <div class="text-pink-vibrant text-2xl mb-4">🎁</div>
                    <h3 class="font-display text-xl text-cream mb-3">Prueba Gratis</h3>
                    <p class="text-cream/70">Accede a lecciones de prueba sin compromiso. Experimenta la calidad antes de suscribirte.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Levels Section -->
    <section class="py-20 bg-purple-darker" id="cursos">
        <div class="container mx-auto px-4">
            <h2 class="font-display text-3xl md:text-4xl text-center text-cream mb-4">
                Cursos para Todos los Niveles
            </h2>
            <p class="text-center text-cream/70 mb-12 max-w-2xl mx-auto">
                Desde tus primeros pasos hasta moves avanzados, tenemos el camino perfecto para tu desarrollo como locker
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @php
                    $levels = [
                        'beginner' => [
                            'name' => 'Principiante',
                            'icon' => '🌱',
                            'description' => 'Aprende los fundamentos del locking. Movimientos básicos, ritmo y musicalidad.',
                            'color' => 'text-green-400'
                        ],
                        'intermediate' => [
                            'name' => 'Intermedio',
                            'icon' => '🔥',
                            'description' => 'Desarrolla tu estilo. Combina movimientos, freestyle y expresión personal.',
                            'color' => 'text-orange-400'
                        ],
                        'advanced' => [
                            'name' => 'Avanzado',
                            'icon' => '💎',
                            'description' => 'Domina técnicas avanzadas. Coreografías complejas y preparación para battles.',
                            'color' => 'text-purple-400'
                        ]
                    ];
                @endphp

                @foreach($levels as $key => $level)
                <div class="card-premium text-center hover-lift">
                    <div class="{{ $level['color'] }} text-5xl mb-4">{{ $level['icon'] }}</div>
                    <h3 class="font-display text-2xl text-cream mb-3">{{ $level['name'] }}</h3>
                    <p class="text-cream/70 mb-6">{{ $level['description'] }}</p>
                    @if($featuredCourses->where('level', $key)->isNotEmpty())
                        <div class="text-pink-vibrant text-sm font-bold">
                            {{ $featuredCourses->where('level', $key)->count() }} {{ $featuredCourses->where('level', $key)->count() === 1 ? 'curso disponible' : 'cursos disponibles' }}
                        </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('register') }}" wire:navigate class="btn-primary">
                    Explora Todos los Cursos
                </a>
            </div>
        </div>
    </section>

    <!-- Community Section -->
    <section class="py-20 bg-purple-deep">
        <div class="container mx-auto px-4">
            <h2 class="font-display text-3xl md:text-4xl text-center text-cream mb-12">
                Únete a la Comunidad
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-5xl mx-auto items-center">
                <div>
                    <div class="card-glass p-8">
                        <div class="text-pink-vibrant text-5xl mb-4">👯‍♀️</div>
                        <h3 class="font-display text-2xl text-cream mb-4">Más que una Escuela</h3>
                        <p class="text-cream/80 leading-relaxed mb-6">
                            Girl Lockers es una comunidad global de chicas apasionadas por el locking. Comparte tu progreso, recibe feedback, haz amigas y crece junto a otras lockers de todo el mundo.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="text-pink-vibrant mr-3">✓</span>
                                <span class="text-cream/80">Comenta y conecta en cada lección</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-pink-vibrant mr-3">✓</span>
                                <span class="text-cream/80">Comparte tu progreso y recibe apoyo</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-pink-vibrant mr-3">✓</span>
                                <span class="text-cream/80">Participa en challenges y eventos online</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="card-premium text-center p-6">
                        <div class="text-4xl font-bold text-pink-vibrant mb-2">500+</div>
                        <div class="text-cream/70 text-sm">Estudiantes</div>
                    </div>
                    <div class="card-premium text-center p-6">
                        <div class="text-4xl font-bold text-pink-vibrant mb-2">50+</div>
                        <div class="text-cream/70 text-sm">Lecciones</div>
                    </div>
                    <div class="card-premium text-center p-6">
                        <div class="text-4xl font-bold text-pink-vibrant mb-2">10+</div>
                        <div class="text-cream/70 text-sm">Cursos</div>
                    </div>
                    <div class="card-premium text-center p-6">
                        <div class="text-4xl font-bold text-pink-vibrant mb-2">24/7</div>
                        <div class="text-cream/70 text-sm">Acceso</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-purple-darker">
        <div class="container mx-auto px-4 text-center">
            <h2 class="font-display text-3xl md:text-5xl text-cream mb-6">
                ¿Lista para <span class="text-pink-gradient">empoderar</span> tu baile?
            </h2>
            <p class="text-cream/80 text-lg md:text-xl max-w-2xl mx-auto mb-10">
                Comienza gratis con nuestras lecciones de prueba. Sin tarjeta de crédito requerida.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" wire:navigate class="btn-primary btn-pulse">
                    Registrarse Gratis
                </a>
                <a href="{{ route('login') }}" wire:navigate class="btn-ghost">
                    Ya tengo cuenta
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 bg-purple-darkest border-t border-pink-vibrant/20">
        <div class="container mx-auto px-4 text-center text-cream/60 text-sm">
            <p class="mb-2">© 2025 <span class="font-accent text-pink-vibrant">Girl Lockers</span>. Todos los derechos reservados.</p>
            <p>Empoderando chicas lockers en todo el mundo 💪✨</p>
        </div>
    </footer>
</div>

@push('scripts')
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
</script>
@endpush
