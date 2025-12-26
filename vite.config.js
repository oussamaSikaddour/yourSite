import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/default/app.css',
                'resources/js/default/app.js',
                'resources/css/custom/app.css',
                'resources/js/custom/app.js',
            ],
            refresh: true,
        }),
    ],
});
