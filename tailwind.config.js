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
                'pink-vibrant': '#FF7BA9',
                'pink-light': '#FFB3D1',
                'pink-dark': '#F06292',
                'cream': '#FFFBF0',
                'cream-light': '#FFFFFF',
                'purple-deep': '#3D4464',
                'purple-darker': '#2A2E47',
                'purple-darkest': '#1A1D2E',
                'black-shadow': '#0F1118',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Montserrat', 'sans-serif'],
                body: ['Inter', 'sans-serif'],
                accent: ['Pacifico', 'cursive'],
            },
            animation: {
                'fade-up': 'fadeSlideUp 0.4s ease-out',
                'pulse-glow': 'pulseGlow 2s infinite',
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
                        boxShadow: '0 0 20px rgba(255, 123, 169, 0.4)',
                    },
                    '50%': {
                        boxShadow: '0 0 40px rgba(255, 123, 169, 0.6)',
                    },
                },
            },
            boxShadow: {
                'glow': '0 0 40px rgba(255, 123, 169, 0.4)',
                'glow-lg': '0 0 60px rgba(255, 123, 169, 0.6)',
            },
        },
    },

    plugins: [forms],
};
