import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { homeCriticalCss } from './resources/js/critical-css.js';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/home.css',
                'resources/css/projects.css',
                'resources/css/shop.css',
                'resources/css/contact.css',
                'resources/css/login.css',
                'resources/css/cart.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
        homeCriticalCss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
