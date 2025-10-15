<div class="relative" x-data="{ open: @entangle('showDropdown') }">
    <!-- Notification Bell Button -->
    <button @click="open = !open" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg relative">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <!-- Notification Badge -->
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        @endif
    </button>

    <!-- Notifications Dropdown -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
        style="display: none;"
    >
        <!-- Header -->
        <div class="px-4 py-2 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Notificaciones</h3>
            @if($unreadCount > 0)
                <button
                    wire:click="markAllAsRead"
                    class="text-xs text-purple-600 hover:text-purple-700 font-medium"
                >
                    Marcar todas como leídas
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <a
                    href="{{ $notification->url }}"
                    wire:navigate
                    @click="open = false"
                    wire:click="markAsRead({{ $notification->id }})"
                    class="block px-4 py-3 hover:bg-gray-50 transition {{ $notification->isUnread() ? 'bg-purple-50' : '' }}"
                >
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                {{ $notification->type === 'new_course' ? 'bg-blue-100' : 'bg-purple-100' }}">
                                @if($notification->type === 'new_course')
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if($notification->isUnread())
                            <div class="w-2 h-2 bg-purple-600 rounded-full flex-shrink-0 mt-2"></div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p class="text-sm text-gray-600">No tienes notificaciones</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if($notifications->count() > 0)
            <div class="px-4 py-2 border-t border-gray-200">
                <a href="#" class="text-sm text-purple-600 hover:text-purple-700 font-medium">Ver todas las notificaciones</a>
            </div>
        @endif
    </div>
</div>
