import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // Ubah bagian ini agar mengarah ke .scss
            input: ['resources/sass/app.scss', 'resources/js/app.js'], 
            refresh: true,
        }),
    ],
});