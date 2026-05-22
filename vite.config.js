import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/login.blade.css',
                'resources/css/dashboard.blade.css',
                'resources/css/home.blade.css',
                'resources/css/sidebar.blade.css',
                'resources/css/department.blade.css',
                'resources/css/programs.blade.css',
                'resources/css/yearlevels.blade.css',
                'resources/css/sections.blade.css',
                'resources/css/student.blade.css',
                'resources/css/school-year.blade.css',
                'resources/css/edit-account.blade.css',
                'resources/css/confirm-password.blade.css',
                'resources/css/confirm-otp-blade.css',
                'resources/css/change-password.blade.css',
                'resources/js/delete-modal.js',
                'resources/js/delete-multiple-modal.js',
                'resources/js/edit-modal.js',
                'resources/js/edit-multiple-modal.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
