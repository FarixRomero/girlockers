import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Paleta Girls Lockers - Light Theme
                'purple-primary': '#8B5CF6', // Morado principal - Color primario
                'purple-dark': '#6D28D9', // Morado oscuro - Hover states
                'purple-light': '#A78BFA', // Morado claro - Acentos suaves
                'purple-ultralight': '#EDE9FE', // Morado ultra claro - Fondos

                'black': '#000000', // Negro puro - Textos principales
                'black-soft': '#1F1F1F', // Negro suave - Textos secundarios
                'gray-dark': '#4B5563', // Gris oscuro - Textos terciarios
                'gray-medium': '#9CA3AF', // Gris medio - Textos deshabilitados
                'gray-light': '#E5E7EB', // Gris claro - Bordes
                'gray-ultralight': '#F9FAFB', // Gris ultra claro - Fondos alternativos

                'white': '#FFFFFF', // Blanco puro - Fondo principal
                'white-soft': '#FAFAFA', // Blanco suave - Fondos secundarios
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Montserrat', 'sans-serif'],
                body: ['Inter', 'sans-serif'],
                accent: ['Pacifico', 'cursive'],
                script: ['Dancing Script', 'Satisfy', 'Brush Script MT', 'cursive'],
            },
            animation: {
                'fade-up': 'fadeSlideUp 0.4s ease-out',
                'pulse-glow': 'pulseGlow 2s infinite',
                'blob': 'blob 7s infinite',
                'blob-slow': 'blob 10s infinite',
                'blob-slower': 'blob 15s infinite',
                'float': 'float 6s ease-in-out infinite',
                'float-slow': 'float 8s ease-in-out infinite',
                'wave': 'wave 3s ease-in-out infinite',
            },
            keyframes: {
                fadeSlideUp: {
                    'from': {
                        opacity: '0',
                        transform: 'translateY(20px)',
                    },
                    'to': {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                pulseGlow: {
                    '0%, 100%': {
                        boxShadow: '0 0 20px rgba(139, 92, 246, 0.4)',
                    },
                    '50%': {
                        boxShadow: '0 0 40px rgba(139, 92, 246, 0.6)',
                    },
                },
                blob: {
                    '0%, 100%': {
                        transform: 'translate(0px, 0px) scale(1)',
                    },
                    '33%': {
                        transform: 'translate(30px, -50px) scale(1.1)',
                    },
                    '66%': {
                        transform: 'translate(-20px, 20px) scale(0.9)',
                    },
                },
                float: {
                    '0%, 100%': {
                        transform: 'translateY(0px)',
                    },
                    '50%': {
                        transform: 'translateY(-20px)',
                    },
                },
                wave: {
                    '0%, 100%': {
                        transform: 'translateX(0)',
                    },
                    '50%': {
                        transform: 'translateX(10px)',
                    },
                },
            },
            boxShadow: {
                'glow': '0 0 40px rgba(139, 92, 246, 0.3)',
                'glow-lg': '0 0 60px rgba(139, 92, 246, 0.5)',
                'purple-glow': '0 4px 20px rgba(139, 92, 246, 0.25)',
                'purple-glow-lg': '0 8px 30px rgba(139, 92, 246, 0.35)',
            },
        },
    },

    plugins: [forms],
};
