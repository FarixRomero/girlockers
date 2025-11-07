<div class="relative" x-data="{ open: @entangle('showDropdown') }">
    <!-- Bell Button -->
    <button
        @click="open = !open"
        class="relative p-2 text-cream/70 hover:text-pink-vibrant transition-colors rounded-lg hover:bg-purple-deep">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <!-- Badge con contador -->
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-pink-vibrant rounded-full">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-xl z-[9999]"
        style="display: none;">

        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-gray-900 font-bold text-sm">Notificaciones</h3>
            @if($unreadCount > 0)
                <button
                    wire:click="markAllAsRead"
                    class="text-purple-600 hover:text-purple-700 text-xs font-medium">
                    Marcar todas como leídas
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <div
                    wire:key="notification-{{ $notification->id }}"
                    class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer {{ $notification->read_at ? 'opacity-60' : '' }}"
                    wire:click="markAsRead({{ $notification->id }})">
                    <div class="flex items-start gap-2">
                        <div class="flex-shrink-0">
                            @if($notification->type === 'success')
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                            @elseif($notification->type === 'warning')
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mt-1.5"></div>
                            @elseif($notification->type === 'error')
                                <div class="w-2 h-2 bg-red-500 rounded-full mt-1.5"></div>
                            @else
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-900 text-sm font-medium">{{ $notification->title }}</p>
                            <p class="text-gray-600 text-xs mt-1">{{ $notification->message }}</p>
                            <p class="text-gray-400 text-xs mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">No tienes notificaciones</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
