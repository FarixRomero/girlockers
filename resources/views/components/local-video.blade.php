@props(['lesson', 'title' => 'Video'])

<div class="relative w-full h-screen bg-black group flex items-center justify-center"
     x-data="videoPlayer('{{ route('lessons.stream', $lesson) }}')"
     @mousemove="showControls"
     @mouseleave="hideControlsDelayed">
    <!-- Video Element -->
    <video
        x-ref="video"
        class="h-full w-auto max-w-full"
        :class="{ 'scale-x-[-1]': mirrored }"
        preload="metadata"
        :src="videoSrc"
        @click="togglePlay"
        @play="isPlaying = true"
        @pause="isPlaying = false"
        @timeupdate="updateProgress"
        @loadedmetadata="onMetadataLoaded"
        @ended="onEnded">
        <p class="text-cream/70 p-4">Tu navegador no soporta la reproducción de video HTML5.</p>
    </video>

    <!-- Custom Controls Overlay -->
    <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 pointer-events-none"
         :class="controlsVisible ? 'opacity-100' : 'opacity-0'">
        <!-- Play/Pause Button -->
        <button
            @click="togglePlay"
            class="pointer-events-auto bg-pink-vibrant/80 hover:bg-pink-vibrant text-cream rounded-full p-4 transform transition hover:scale-110"
            x-show="!isPlaying">
            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"/>
            </svg>
        </button>
    </div>

    <!-- Controls Bar -->
    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 to-transparent p-4 transform transition-transform duration-300"
         :class="controlsVisible ? 'translate-y-0' : 'translate-y-full'">
        <!-- Progress Bar -->
        <div class="mb-3">
            <div class="relative h-1 bg-cream/20 rounded-full cursor-pointer" @click="seek($event)">
                <div class="absolute h-full bg-pink-vibrant rounded-full transition-all" :style="`width: ${progress}%`"></div>
            </div>
            <div class="flex justify-between text-xs text-cream/70 mt-1">
                <span x-text="formatTime(currentTime)">0:00</span>
                <span x-text="formatTime(duration)">0:00</span>
            </div>
        </div>

        <!-- Control Buttons -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- Play/Pause -->
                <button @click="togglePlay" class="text-cream hover:text-pink-vibrant transition">
                    <svg x-show="!isPlaying" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                    <svg x-show="isPlaying" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                    </svg>
                </button>

                <!-- Volume -->
                <div class="flex items-center gap-2">
                    <button @click="toggleMute" class="text-cream hover:text-pink-vibrant transition">
                        <svg x-show="!isMuted && volume > 0" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
                        </svg>
                        <svg x-show="isMuted || volume === 0" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
                        </svg>
                    </button>
                    <input
                        type="range"
                        min="0"
                        max="100"
                        x-model="volume"
                        @input="changeVolume"
                        class="w-20 h-1 bg-cream/20 rounded-full appearance-none cursor-pointer accent-pink-vibrant">
                </div>

                <!-- Speed -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-cream hover:text-pink-vibrant transition text-sm font-medium px-2 py-1 bg-cream/10 rounded">
                        <span x-text="playbackRate + 'x'">1x</span>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute bottom-full mb-2 bg-purple-darkest border border-pink-vibrant/20 rounded-lg p-2 space-y-1">
                        <template x-for="rate in [0.25, 0.5, 0.75, 1, 1.25, 1.5, 1.75, 2]" :key="rate">
                            <button
                                @click="changeSpeed(rate); open = false"
                                class="block w-full text-left px-3 py-1 text-sm hover:bg-pink-vibrant/20 rounded"
                                :class="{ 'text-pink-vibrant': playbackRate === rate, 'text-cream': playbackRate !== rate }"
                                x-text="rate + 'x'">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Mirror Button -->
                <button
                    @click="toggleMirror"
                    class="text-cream hover:text-pink-vibrant transition"
                    :class="{ 'text-pink-vibrant': mirrored }">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </button>

                <!-- Fullscreen -->
                <button @click="toggleFullscreen" class="text-cream hover:text-pink-vibrant transition">
                    <svg x-show="!isFullscreen" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
                    </svg>
                    <svg x-show="isFullscreen" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('videoPlayer', (src) => ({
        videoSrc: src,
        isPlaying: false,
        isMuted: false,
        isFullscreen: false,
        mirrored: false,
        volume: 100,
        currentTime: 0,
        duration: 0,
        progress: 0,
        playbackRate: 1,
        controlsVisible: true,
        controlsTimeout: null,

        init() {
            this.$refs.video.volume = this.volume / 100;

            // Listen to fullscreen changes
            document.addEventListener('fullscreenchange', () => {
                this.isFullscreen = !!document.fullscreenElement;
            });
        },

        showControls() {
            this.controlsVisible = true;
            clearTimeout(this.controlsTimeout);
            if (this.isPlaying) {
                this.controlsTimeout = setTimeout(() => {
                    this.controlsVisible = false;
                }, 3000);
            }
        },

        hideControlsDelayed() {
            if (!this.isFullscreen && this.isPlaying) {
                clearTimeout(this.controlsTimeout);
                this.controlsTimeout = setTimeout(() => {
                    this.controlsVisible = false;
                }, 1000);
            }
        },

        togglePlay() {
            if (this.$refs.video.paused) {
                this.$refs.video.play().catch(err => {
                    console.log('Play prevented:', err);
                    this.isPlaying = false;
                });
                this.isPlaying = true;
            } else {
                this.$refs.video.pause();
                this.isPlaying = false;
            }
        },

        toggleMute() {
            this.$refs.video.muted = !this.$refs.video.muted;
            this.isMuted = this.$refs.video.muted;
        },

        changeVolume() {
            this.$refs.video.volume = this.volume / 100;
            this.isMuted = this.volume === 0;
        },

        changeSpeed(rate) {
            this.$refs.video.playbackRate = rate;
            this.playbackRate = rate;
        },

        toggleMirror() {
            this.mirrored = !this.mirrored;
        },

        toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log('Fullscreen error:', err);
                });
            } else {
                document.exitFullscreen();
            }
        },

        seek(event) {
            const rect = event.currentTarget.getBoundingClientRect();
            const pos = (event.clientX - rect.left) / rect.width;
            this.$refs.video.currentTime = pos * this.duration;
        },

        updateProgress() {
            this.currentTime = this.$refs.video.currentTime;
            this.progress = (this.currentTime / this.duration) * 100;
        },

        onMetadataLoaded() {
            this.duration = this.$refs.video.duration;
        },

        onEnded() {
            this.isPlaying = false;
        },

        formatTime(seconds) {
            if (isNaN(seconds)) return '0:00';
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        }
    }));
});
</script>
