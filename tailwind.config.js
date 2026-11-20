import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                // Token semantik — lihat context.md. Jangan pakai teal/amber/red mentah di view.
                primary: colors.teal,
                warning: colors.amber,
                danger: colors.red,
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            keyframes: {
                rise: {
                    from: { opacity: '0', transform: 'translateY(12px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                pop: {
                    from: { opacity: '0', transform: 'scale(0.95)' },
                    to: { opacity: '1', transform: 'scale(1)' },
                },
            },
            animation: {
                rise: 'rise 0.5s cubic-bezier(0.22, 1, 0.36, 1) both',
                pop: 'pop 0.2s cubic-bezier(0.22, 1, 0.36, 1) both',
            },
        },
    },
    plugins: [],
};
