import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/ckeditor.css',
                'resources/js/editor/ckeditor.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
