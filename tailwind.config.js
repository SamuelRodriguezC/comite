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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
             keyframes: {
                    'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(50px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                    },
                    'fade-right': {
                    '0%': { opacity: '0', transform: 'translateX(50px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                    },
                    'fade-left': {
                    '0%': { opacity: '0', transform: 'translateX(-50px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                    },
                    'fade-in-scale': {
                    '0%': { opacity: 0, transform: 'scale(0.5)' },
                    '100%': { opacity: 1, transform: 'scale(1)' },
                    },
                    'grow-line': {
                    '0%': { transform: 'scaleX(0)' },
                    '100%': { transform: 'scaleX(1)' },
                    },
                },
                animation: {
                    'fade-up': 'fade-up 0.6s ease-out forwards',
                    'fade-right': 'fade-right 0.6s ease-out forwards',
                    'fade-left': 'fade-left 0.6s ease-out forwards',
                    'fade-in-scale': 'fade-in-scale 0.5s ease-out forwards',
                    'grow-line': 'grow-line 1s ease-in-out forwards',
                },
        },
    },

    plugins: [forms],
};
