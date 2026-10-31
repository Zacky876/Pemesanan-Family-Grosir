import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/login.css',
                'resources/css/dashboard.css',
                'resources/css/admin.css',
                'resources/css/user.css',
                'resources/css/kasir.css',
                'resources/css/cashier.css',
                'resources/css/receipt.css',
                'resources/js/login.js',
                'resources/js/dashboard.js',
                'resources/js/cashier.js',
            ],
            refresh: true,
        }),
    ],
});