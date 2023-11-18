import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
const { withAnimations } = require('animated-tailwindcss')


module.exports = withAnimations({

    darkMode: 'class',
    content: [
        './vendor/awcodes/filament-badgeable-column/resources/**/*.blade.php',
        './app/Http/Livewire/**/*Table.php',
        './app/Livewire/**/*.php',
        './vendor/power-components/livewire-powergrid/resources/views/**/*.php',
        './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",

        "./vendor/robsontenorio/mary/src/View/Components/**/*.php"

    ],

    daisyui: {
        darkTheme: "dark",
        base: true,
        styled: true,
        utils: true,
        themes: [
            "light",
            "dark",
            "cupcake",
            "bumblebee",
            "emerald",
            "corporate",
            "synthwave",
            "retro",
            "cyberpunk",
            "valentine",
            "halloween",
            "garden",
            "forest",
            "aqua",
            "lofi",
            "pastel",
            "fantasy",
            "wireframe",
            "black",
            "luxury",
            "dracula",
            "cmyk",
            "autumn",
            "business",
            "acid",
            "lemonade",
            "night",
            "coffee",
            "winter",
            {
                teresa: {
                    "primary": "#f6a83c",

                    "secondary": "#995bd3",

                    "accent": "#ffd323",

                    "neutral": "#212028",

                    "base-100": "#f8fafc",

                    "info": "#7d94d4",

                    "success": "#1bc584",

                    "warning": "#f6a83c",

                    "error": "#e41b4a",
                }
                // teresa: {

                //     "primary": "#F5A357",

                //     "secondary": "#313A4A",

                //     "accent": "#806866",

                //     "neutral": "#313A4A",

                //     "base-100": "#FEFCFD",

                //     "info": "#4E676B",

                //     "success": "#8A9277",

                //     "warning": "#FDD797",

                //     "error": "#DC7149",
                // },
            },
        ],
    },
    // theme: {
    //     extend: {
    //         // colors: {

    //         //     custom: {
    //         //         50: 'rgba(var(--c-50), <alpha-value>)',
    //         //         100: 'rgba(var(--c-100), <alpha-value>)',
    //         //         200: 'rgba(var(--c-200), <alpha-value>)',
    //         //         300: 'rgba(var(--c-300), <alpha-value>)',
    //         //         400: 'rgba(var(--c-400), <alpha-value>)',
    //         //         500: 'rgba(var(--c-500), <alpha-value>)',
    //         //         600: 'rgba(var(--c-600), <alpha-value>)',
    //         //         700: 'rgba(var(--c-700), <alpha-value>)',
    //         //         800: 'rgba(var(--c-800), <alpha-value>)',
    //         //         900: 'rgba(var(--c-900), <alpha-value>)',
    //         //         950: 'rgba(var(--c-950), <alpha-value>)',
    //         //     },
    //         //     danger: {
    //         //         '50': '#fcf5f0',
    //         //         '100': '#f9e7db',
    //         //         '200': '#f2cbb6',
    //         //         '300': '#e9a988',
    //         //         '400': '#dc7149',
    //         //         '500': '#d85c37',
    //         //         '600': '#c9462d',
    //         //         '700': '#a73527',
    //         //         '800': '#862d26',
    //         //         '900': '#6c2822',
    //         //         '950': '#3a1210',
    //         //     },
    //         //     gray: {
    //         //         '50': '#f6f7f9',
    //         //         '100': '#ebeef3',
    //         //         '200': '#d3d9e4',
    //         //         '300': '#acb9cd',
    //         //         '400': '#8094b0',
    //         //         '500': '#607797',
    //         //         '600': '#4c5f7d',
    //         //         '700': '#3e4d66',
    //         //         '800': '#364256',
    //         //         '900': '#313a4a',
    //         //         '950': '#202531',
    //         //     },
    //         //     info: {
    //         //         '50': '#f7f5ef',
    //         //         '100': '#eae7d7',
    //         //         '200': '#d7cfb1',
    //         //         '300': '#c0b184',
    //         //         '400': '#b4a170',
    //         //         '500': '#9e8654',
    //         //         '600': '#876d47',
    //         //         '700': '#6d553b',
    //         //         '800': '#5d4836',
    //         //         '900': '#513f32',
    //         //         '950': '#2e211a',
    //         //     },
    //         //     primary: {
    //         //         '50': '#fef7ee',
    //         //         '100': '#fdeed7',
    //         //         '200': '#fbd9ad',
    //         //         '300': '#f8be79',
    //         //         '400': '#f5a357',
    //         //         '500': '#f07b1f',
    //         //         '600': '#e26114',
    //         //         '700': '#bb4913',
    //         //         '800': '#953b17',
    //         //         '900': '#783216',
    //         //         '950': '#411709',
    //         //     },
    //         //     success: {
    //         //         '50': '#f3f6f3',
    //         //         '100': '#e4e8e3',
    //         //         '200': '#cad2c8',
    //         //         '300': '#a4b2a3',
    //         //         '400': '#7a8d79',
    //         //         '500': '#5a6f5a',
    //         //         '600': '#445744',
    //         //         '700': '#374537',
    //         //         '800': '#2d382d',
    //         //         '900': '#252f25',
    //         //         '950': '#141a14',
    //         //     },
    //         //     warning: {
    //         //         '50': '#fff9ed',
    //         //         '100': '#fff1d5',
    //         //         '200': '#fdd797',
    //         //         '300': '#fcc675',
    //         //         '400': '#faa33d',
    //         //         '500': '#f78718',
    //         //         '600': '#e86d0e',
    //         //         '700': '#c1530d',
    //         //         '800': '#994113',
    //         //         '900': '#7b3713',
    //         //         '950': '#431a07',
    //         //     },
    //         // },
    //         fontFamily: {
    //             sans: ['Figtree', ...defaultTheme.fontFamily.sans],
    //         },
    //     },
    // },

    plugins: [require("daisyui"), forms, typography,],
});
