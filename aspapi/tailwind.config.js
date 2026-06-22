const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    50:      '#F0F7FF',
                    100:     '#EEF4FB',
                    200:     '#D6E8F7',
                    300:     '#A8D4F5',
                    400:     '#6AAFE6',
                    DEFAULT: '#2A7FC1',
                    600:     '#1A5F9A',
                    700:     '#154D80',
                    800:     '#0F3A61',
                    900:     '#0A2540',
                },
                navy: {
                    DEFAULT: '#1A2A3A',
                    light:   '#253646',
                    dark:    '#111E2A',
                },
                accent: {
                    red:         '#C0392B',
                    'red-dark':  '#A93226',
                    yellow:      '#E8B84B',
                    'yellow-dark': '#D4A73A',
                },
                neutral: {
                    50:  '#F8FAFC',
                    100: '#EEF4FB',
                    200: '#D6E8F7',
                    300: '#B0CCDF',
                    400: '#7A9CB8',
                    500: '#4A6580',
                    600: '#374F63',
                    700: '#2A3C4D',
                    800: '#1E2D3D',
                    900: '#131E2A',
                },
            },

            fontFamily: {
                display: ['"DM Serif Display"', 'Georgia', ...defaultTheme.fontFamily.serif],
                sans:    ['"DM Sans"', ...defaultTheme.fontFamily.sans],
            },

            fontSize: {
                '2xs': ['0.65rem', { lineHeight: '1rem', letterSpacing: '0.08em' }],
                'xs':  ['0.75rem', { lineHeight: '1.1rem' }],
            },

            borderRadius: {
                'sm':  '3px',
                DEFAULT: '4px',
                'md':  '6px',
                'lg':  '10px',
                'xl':  '16px',
            },

            boxShadow: {
                'card':       '0 2px 12px rgba(42, 127, 193, 0.08)',
                'card-hover': '0 8px 32px rgba(42, 127, 193, 0.14)',
                'navbar':     '0 2px 16px rgba(26, 42, 58, 0.08)',
            },

            spacing: {
                '18':  '4.5rem',
                '22':  '5.5rem',
                '88':  '22rem',
                '120': '30rem',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms')],
};