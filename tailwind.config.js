import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Jost', 'Inter', ...defaultTheme.fontFamily.sans],
                display: ['Cormorant Garamond', 'Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // Classic brass / gold — muted, not bright amber
                brass: {
                    50: '#faf7f0',
                    100: '#f3ecdc',
                    200: '#e6d6b3',
                    300: '#d5bd85',
                    400: '#c4a35f',
                    500: '#b08d46',
                    600: '#96742f',
                    700: '#7a5c28',
                    800: '#5f4724',
                    900: '#46331b',
                },
                ink: {
                    950: '#14110d',
                    900: '#1c1813',
                    800: '#26211a',
                    700: '#353023',
                },
                cream: {
                    50: '#fdfcf9',
                    100: '#faf8f3',
                    200: '#f3eee3',
                    300: '#e8e0cf',
                },
                // keep amber alias mapped to brass for legacy classes
                amber: {
                    50: '#faf7f0',
                    100: '#f3ecdc',
                    200: '#e6d6b3',
                    300: '#d5bd85',
                    400: '#c4a35f',
                    500: '#b08d46',
                    600: '#96742f',
                    700: '#7a5c28',
                    800: '#5f4724',
                    900: '#46331b',
                },
            },
            letterSpacing: {
                widest2: '0.28em',
            },
        },
    },
    plugins: [],
};
