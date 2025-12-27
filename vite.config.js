import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app/app.css',
                'resources/css/core/core.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
