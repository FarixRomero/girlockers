import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/landing-animations.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        // Production optimizations
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.log in production
                drop_debugger: true,
            },
        },
        // Asset optimization
        assetsInlineLimit: 4096, // Inline assets < 4kb as base64
        cssCodeSplit: true,
        sourcemap: false, // Disable source maps in production for smaller bundle
    },
});
