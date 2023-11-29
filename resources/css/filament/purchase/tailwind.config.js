import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Purchase/**/*.php',
        './resources/views/filament/purchase/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
