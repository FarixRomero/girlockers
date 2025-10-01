import './bootstrap';

// Video Player Alpine Component - Global Registration
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
