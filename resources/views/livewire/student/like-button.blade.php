<div>
    <button
        wire:click="toggleLike"
        wire:loading.attr="disabled"
        class="flex items-center space-x-2 px-4 py-2 rounded-full transition-all duration-200 {{ $isLiked ? 'bg-pink-vibrant text-cream' : 'bg-purple-darker text-cream/70 hover:bg-purple-deep' }}"
        x-data="{ liked: @entangle('isLiked') }"
        @click="$el.classList.add('scale-110'); setTimeout(() => $el.classList.remove('scale-110'), 200)">

        <!-- Heart Icon -->
        <svg
            class="w-5 h-5 transition-all"
            :class="{ 'fill-current': liked, 'animate-pulse': liked }"
            fill="{{ $isLiked ? 'currentColor' : 'none' }}"
            stroke="currentColor"
            viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
            </path>
        </svg>

        <!-- Like Count -->
        <span class="font-medium text-sm" wire:loading.remove wire:target="toggleLike">
            {{ $likesCount }}
        </span>

        <!-- Loading Spinner -->
        <span wire:loading wire:target="toggleLike">
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </span>

        <!-- Like Text -->
        <span class="text-sm hidden sm:inline">
            {{ $isLiked ? 'Te gusta' : 'Me gusta' }}
        </span>
    </button>
</div>
