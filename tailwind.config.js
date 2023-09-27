import colors from 'tailwindcss/colors';
const { withAnimations } = require('animated-tailwindcss')


module.exports = withAnimations({

    content: ['node_modules/preline/dist/*.js', './resources/**/*.blade.php', './modules/**/*.blade.php', './vendor/filament/**/*.blade.php', './vendor/filament/**/*.blade.php', 'vendor/awcodes/shout/resources/views/**/*.blade.php', './vendor/koalafacade/filament-alertbox/**/*.blade.php'],
    darkMode: 'class',
    theme: {

        extend: {

            fontFamily: {
                'nunito': ['nunito', 'sans-serif']
            },
            colors: {
                danger: colors.red,
                primary: colors.amber,
                success: colors.green,
                warning: colors.yellow,
                transparent: 'transparent',
                current: 'currentColor',
                black: colors.black,
                white: colors.white,
                gray: colors.gray,
                emerald: colors.emerald,
                "indigo": {
                    50: "#F1F1FE",
                    100: "#DEDFFC",
                    200: "#C2C3FA",
                    300: "#A1A3F7",
                    400: "#8183F4",
                    500: "#6366F1",
                    600: "#2326EB",
                    700: "#1114BB",
                    800: "#0B0D7E",
                    900: "#05063D",
                    950: "#030321"
                },
                yellow: colors.yellow,
            },
        },
    },
    plugins: [

        require('preline/plugin'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio')
    ],
})
