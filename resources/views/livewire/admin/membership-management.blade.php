<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-cream mb-2">Gestionar Suscripciones</h1>
        <p class="text-cream/60">Administra los planes de membresía y sus precios</p>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-500/20 border border-green-500 text-green-100 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Plans List by Currency -->
    @php
        $plansByCurrency = $plans->groupBy('currency');
    @endphp

    @foreach($plansByCurrency as $currency => $currencyPlans)
        <div class="mb-8">
            <!-- Currency Header -->
            <div class="mb-4 flex items-center gap-3">
                <h2 class="text-2xl font-bold text-cream">
                    @if($currency === 'PEN')
                        🇵🇪 Planes en Soles (PEN) - Perú
                    @elseif($currency === 'USD')
                        🌎 Planes en Dólares (USD) - Internacional
                    @else
                        Planes en {{ $currency }}
                    @endif
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($currencyPlans as $plan)
            <div class="bg-purple-darker border border-pink-vibrant/20 rounded-lg overflow-hidden">
                <!-- Plan Header -->
                <div class="bg-purple-deep p-6 border-b border-pink-vibrant/20">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-2xl font-bold text-cream capitalize">
                            {{ $plan->type === 'monthly' ? 'Mensual' : 'Trimestral' }}
                        </h3>
                        <button
                            wire:click="toggleStatus({{ $plan->id }})"
                            class="px-3 py-1 rounded-full text-xs font-bold {{ $plan->is_active ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                            {{ $plan->is_active ? 'Activo' : 'Inactivo' }}
                        </button>
                    </div>
                    <div class="flex items-baseline">
                        <span class="text-4xl font-bold text-pink-vibrant">{{ $plan->currency }} {{ number_format($plan->price, 2) }}</span>
                        <span class="ml-2 text-cream/60">/ {{ $plan->type === 'monthly' ? 'mes' : '3 meses' }}</span>
                    </div>
                </div>

                <!-- Plan Body -->
                @if ($editingPlan === $plan->id)
                    <!-- Edit Form -->
                    <form wire:submit.prevent="updatePlan" class="p-6 space-y-4">
                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-bold text-cream mb-2">Precio</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="price"
                                class="w-full px-4 py-2 bg-purple-deep border border-pink-vibrant/20 rounded-lg text-cream focus:ring-2 focus:ring-pink-vibrant focus:border-transparent"
                                placeholder="30.00">
                            @error('price') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Currency -->
                        <div>
                            <label class="block text-sm font-bold text-cream mb-2">Moneda</label>
                            <select
                                wire:model="currency"
                                class="w-full px-4 py-2 bg-purple-deep border border-pink-vibrant/20 rounded-lg text-cream focus:ring-2 focus:ring-pink-vibrant focus:border-transparent">
                                <option value="PEN">PEN - Soles</option>
                                <option value="USD">USD - Dólares</option>
                                <option value="EUR">EUR - Euros</option>
                            </select>
                            @error('currency') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-bold text-cream mb-2">Descripción</label>
                            <textarea
                                wire:model="description"
                                rows="3"
                                class="w-full px-4 py-2 bg-purple-deep border border-pink-vibrant/20 rounded-lg text-cream focus:ring-2 focus:ring-pink-vibrant focus:border-transparent"
                                placeholder="Descripción del plan..."></textarea>
                            @error('description') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                wire:model="is_active"
                                id="is_active_{{ $plan->id }}"
                                class="w-4 h-4 text-pink-vibrant bg-purple-deep border-pink-vibrant/20 rounded focus:ring-pink-vibrant">
                            <label for="is_active_{{ $plan->id }}" class="ml-2 text-sm text-cream">Plan activo</label>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 pt-4">
                            <button
                                type="submit"
                                class="flex-1 bg-pink-vibrant hover:bg-pink-light text-cream font-semibold py-2 px-4 rounded-lg transition-colors">
                                Guardar Cambios
                            </button>
                            <button
                                type="button"
                                wire:click="cancelEdit"
                                class="flex-1 bg-purple-deep hover:bg-purple-deep/80 text-cream/70 font-semibold py-2 px-4 rounded-lg transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </form>
                @else
                    <!-- View Mode -->
                    <div class="p-6">
                        <p class="text-cream/80 mb-6">{{ $plan->description }}</p>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-cream/60">Tipo:</span>
                                <span class="text-cream font-semibold">{{ ucfirst($plan->type) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-cream/60">Moneda:</span>
                                <span class="text-cream font-semibold">{{ $plan->currency }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-cream/60">Estado:</span>
                                <span class="text-cream font-semibold">{{ $plan->is_active ? 'Activo' : 'Inactivo' }}</span>
                            </div>
                        </div>

                        <button
                            wire:click="editPlan({{ $plan->id }})"
                            class="w-full mt-6 bg-purple-deep hover:bg-purple-deep/80 text-pink-vibrant font-semibold py-2 px-4 rounded-lg transition-colors border border-pink-vibrant/20">
                            Editar Plan
                        </button>
                    </div>
                @endif
            </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Info Box -->
    <div class="mt-8 bg-purple-deep/50 border border-pink-vibrant/20 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-pink-vibrant mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="text-cream font-semibold mb-2">Información Importante</h4>
                <ul class="text-cream/70 text-sm space-y-1">
                    <li>• Los usuarios ven planes según su país: Perú → PEN, otros países → USD</li>
                    <li>• Los cambios en los precios se aplican inmediatamente a las nuevas suscripciones</li>
                    <li>• Las suscripciones activas mantienen el precio con el que fueron adquiridas</li>
                    <li>• Desactivar un plan impide nuevas compras pero no afecta suscripciones existentes</li>
                </ul>
            </div>
        </div>
    </div>
</div>
