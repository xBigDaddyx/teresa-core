import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Kanban/**/*.php',
        './resources/views/filament/kanban/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
