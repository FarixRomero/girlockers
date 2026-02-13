<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        // Role-based redirect
        $user = auth()->user();
        $defaultRoute = $user->isAdmin() ? 'admin.dashboard' : 'dashboard';

        $this->redirectIntended(default: route($defaultRoute, absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header Section -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Bienvenida de vuelta</h2>
        <p class="text-gray-600">Ingresa a tu cuenta para continuar tu aprendizaje</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Correo Electrónico" class="text-gray-700 font-semibold mb-2" />
            <x-text-input
                wire:model="form.email"
                id="email"
                class="block w-full px-4 py-3 text-base"
                type="email"
                name="email"
                placeholder="tu@email.com"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Contraseña" class="text-gray-700 font-semibold mb-2" />
            <x-text-input
                wire:model="form.password"
                id="password"
                class="block w-full px-4 py-3 text-base"
                type="password"
                name="password"
                placeholder="••••••••"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password Row -->
        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center cursor-pointer group">
                <input
                    wire:model="form.remember"
                    id="remember"
                    type="checkbox"
                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500 focus:ring-offset-0 transition-colors cursor-pointer"
                    name="remember"
                >
                <span class="ms-2 text-sm text-gray-700 group-hover:text-gray-900 transition-colors">Recuérdame</span>
            </label>

            @if (Route::has('password.request'))
                <a
                    class="text-sm font-medium text-purple-600 hover:text-purple-700 transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 rounded px-1"
                    href="{{ route('password.request') }}"
                    wire:navigate
                >
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-primary-button class="w-full justify-center text-lg py-3.5">
                Iniciar Sesión
            </x-primary-button>
        </div>
    </form>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="flex items-center">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="mx-3 mt-4 text-xs text-gray-400 font-normal">¿Primera vez aquí?</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>
    </div>

    <!-- Register Link -->
    <div class="text-center">
        <p class="text-gray-600">
            Crea una cuenta para acceder a nuestras clases de baile
        </p>
        <a
            href="{{ route('register') }}"
            wire:navigate
            class="inline-block mt-4 px-8 py-3 text-base font-semibold text-purple-600 bg-purple-50 hover:bg-purple-100 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 hover:shadow-md"
        >
            Registrarse Ahora
        </a>
    </div>
</div>
