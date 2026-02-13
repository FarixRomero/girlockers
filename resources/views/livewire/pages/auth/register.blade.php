<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'student'; // Auto-assign student role

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header Section -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Únete a la comunidad</h2>
        <p class="text-gray-600">Crea tu cuenta y comienza tu viaje en el baile</p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nombre Completo" class="text-gray-700 font-semibold mb-2" />
            <x-text-input
                wire:model="name"
                id="name"
                class="block w-full px-4 py-3 text-base"
                type="text"
                name="name"
                placeholder="Tu nombre"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Correo Electrónico" class="text-gray-700 font-semibold mb-2" />
            <x-text-input
                wire:model="email"
                id="email"
                class="block w-full px-4 py-3 text-base"
                type="email"
                name="email"
                placeholder="tu@email.com"
                required
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Contraseña" class="text-gray-700 font-semibold mb-2" />
            <x-text-input
                wire:model="password"
                id="password"
                class="block w-full px-4 py-3 text-base"
                type="password"
                name="password"
                placeholder="Mínimo 8 caracteres"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirmar Contraseña" class="text-gray-700 font-semibold mb-2" />
            <x-text-input
                wire:model="password_confirmation"
                id="password_confirmation"
                class="block w-full px-4 py-3 text-base"
                type="password"
                name="password_confirmation"
                placeholder="Repite tu contraseña"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms Notice -->
        <div class="pt-2">
            <p class="text-xs text-gray-500 text-center">
                Al registrarte, aceptas nuestros términos de servicio y política de privacidad
            </p>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-primary-button class="w-full justify-center text-lg py-3.5">
                Crear mi cuenta
            </x-primary-button>
        </div>
    </form>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="flex items-center">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="mx-3 mt-4 text-xs text-gray-400 font-normal">¿Ya tienes cuenta?</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>
    </div>

    <!-- Login Link -->
    <div class="text-center">
        <a
            href="{{ route('login') }}"
            wire:navigate
            class="inline-block px-8 py-3 text-base font-semibold text-purple-600 bg-purple-50 hover:bg-purple-100 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 hover:shadow-md"
        >
            Iniciar Sesión
        </a>
    </div>
</div>
