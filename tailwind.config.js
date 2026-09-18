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
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                /* ---- Palette ChezMoi Beige/Gold ---- */
                'white-soft': '#F8F8F8',
                'beige': {
                    DEFAULT: '#FAEBCD',
                    50:  '#FFFDF7',
                    100: '#FAEBCD',
                    200: '#F5D9A0',
                    300: '#F0C873',
                },
                'gold': {
                    DEFAULT: '#F7C873',
                    logo:    '#F7C873',
                    dark:    '#E5A835',
                    light:   '#FDE8A8',
                },
                'charcoal': {
                    DEFAULT: '#434343',
                    dark:    '#2C2C2C',
                    light:   '#636363',
                },
                /* Aliases utilisés dans les vues existantes */
                'cream': {
                    50:  '#FFFDF7',
                    100: '#FAEBCD',
                    200: '#F5D9A0',
                },
                'paper': {
                    50: '#FFFDF7',
                },
                'link-chez': '#E5A835',
            },
            boxShadow: {
                'beige': '0 4px 20px rgba(247, 200, 115, 0.25)',
                'beige-lg': '0 10px 40px rgba(247, 200, 115, 0.35)',
                'card': '0 2px 16px rgba(67, 67, 67, 0.08)',
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },
        },
    },

    plugins: [forms],
};
