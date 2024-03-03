import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
            // proxy: {
            //     "/holidays-jp.github.io/api/": {
            //       target: "http://127.0.0.1:8000",
            //     }
            // }
        }),
    ],
    // server: {
    //     proxy: {
    //         '/api/': {
    //             target: 'https://holidays-jp.github.io/api/v1/2017/date.json', // LaravelのURLに置き換える
    //             changeOrigin: true,
    //             rewrite: (path) => path.replace(/^\/api/, ''),
    //           },
    //     }
    // }
});

