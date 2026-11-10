import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/login.css',
                'resources/css/register.css',

                'resources/css/admin/dashboard.css',
                'resources/css/admin/layouts.css',
                'resources/css/admin/admin.css',
                'resources/css/admin/products.css',
                'resources/css/admin/transactions.css',
                'resources/css/admin/reports.css',
                'resources/css/admin/categories.css',

                'resources/js/admin/dashboard.js',
                'resources/js/admin/layouts.js',
                'resources/js/admin/stock.js',

                'resources/css/kasir/dashboard.css',
                'resources/css/kasir/kasir.css',
                'resources/css/kasir/cashier.css',
                'resources/css/kasir/receipt.css',
                'resources/css/kasir/transactions.css',

                'resources/js/kasir/dashboard.js',
                'resources/js/kasir/cashier.js',

                'resources/css/user/dashboard.css',
                'resources/css/user/user.css',
                'resources/css/user/transactions.css',
                
                'resources/js/user/dashboard.js',

                'resources/js/login.js',
            ],
            refresh: true,
        }),
    ],
});