import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
    server: {
        // Configuração para funcionar dentro do Docker
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        // Permite que qualquer origem (localhost:8000) carregue assets do Vite (localhost:5173)
        cors: {
            origin: '*',
            methods: ['GET', 'HEAD', 'OPTIONS'],
            allowedHeaders: ['*'],
        },
        // origin informa ao plugin Laravel qual URL pública usar nos <script> gerados
        origin: 'http://localhost:5173',
        hmr: {
            host: 'localhost',
            port: 5173,
        },
        watch: {
            // usePolling é necessário quando o código está montado via volume Docker
            usePolling: true,
            interval: 500,
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
