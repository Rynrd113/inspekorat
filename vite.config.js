import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/admin.css',
                'resources/js/app.js', 
                'resources/js/admin.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        // Optimization untuk production
        rollupOptions: {
            output: {
                manualChunks: {
                    // Vendor libraries chunk
                    vendor: ['lodash', 'axios'],
                    // Admin-specific code chunk
                    admin: ['resources/js/admin.js']
                },
                // Optimize asset file names dengan hash untuk cache busting
                entryFileNames: (chunkInfo) => {
                    const facadeModuleId = chunkInfo.facadeModuleId ? chunkInfo.facadeModuleId.split('/').pop().replace(/\.\w+$/, '') : 'index';
                    return `assets/js/[name].[hash].js`;
                },
                chunkFileNames: (chunkInfo) => {
                    const facadeModuleId = chunkInfo.facadeModuleId ? chunkInfo.facadeModuleId.split('/').pop().replace(/\.\w+$/, '') : 'index';
                    return `assets/js/[name].[hash].js`;
                },
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    
                    // CSS files
                    if (/\.(css)$/.test(assetInfo.name)) {
                        return `assets/css/[name].[hash].${ext}`;
                    }
                    
                    // Image files
                    if (/\.(png|jpe?g|gif|svg|ico|webp|avif)$/.test(assetInfo.name)) {
                        return `assets/images/[name].[hash].${ext}`;
                    }
                    
                    // Font files
                    if (/\.(woff2?|eot|ttf|otf)$/.test(assetInfo.name)) {
                        return `assets/fonts/[name].[hash].${ext}`;
                    }
                    
                    // Video files
                    if (/\.(mp4|webm|ogg|mp3|wav|flac|aac)$/.test(assetInfo.name)) {
                        return `assets/media/[name].[hash].${ext}`;
                    }
                    
                    // Default
                    return `assets/misc/[name].[hash].${ext}`;
                }
            },
            // External dependencies yang tidak perlu di-bundle
            external: [
                // CDN dependencies
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
                'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'
            ]
        },
        // Increase chunk size warning limit
        chunkSizeWarningLimit: 2000,
        // Enable source maps hanya untuk development
        sourcemap: process.env.NODE_ENV === 'development',
        // Advanced minification
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: process.env.NODE_ENV === 'production',
                drop_debugger: process.env.NODE_ENV === 'production',
                pure_funcs: ['console.log', 'console.info', 'console.warn'],
                passes: 2,
            },
            mangle: {
                safari10: true,
            },
            format: {
                comments: false,
            },
        },
        // CSS code splitting
        cssCodeSplit: true,
        // Asset inlining threshold
        assetsInlineLimit: 4096,
        // Target modern browsers
        target: ['es2020', 'chrome80', 'firefox78', 'safari13'],
    },
    optimizeDeps: {
        // Include dependencies yang harus di-pre-bundle
        include: [
            'lodash',
            'axios'
        ],
        // Exclude dependencies yang tidak perlu pre-bundle
        exclude: [
            '@vite/client', 
            '@vite/env',
            'fsevents'
        ],
        // Force optimization untuk beberapa dependencies
        force: process.env.NODE_ENV === 'development',
    },
    // Server configuration untuk development
    server: {
        host: 'localhost',
        port: 5173,
        hmr: {
            host: 'localhost',
            port: 5173,
        },
        watch: {
            usePolling: true,
            interval: 100,
        },
        // Proxy untuk API calls
        proxy: {
            '/api': {
                target: 'http://localhost:8000',
                changeOrigin: true,
                secure: false,
            },
        },
    },
    // CSS optimization
    css: {
        devSourcemap: process.env.NODE_ENV === 'development',
        preprocessorOptions: {
            scss: {
                additionalData: `@import "resources/css/variables.scss";`,
            },
        },
    },
    // Resolve aliases untuk path yang lebih bersih
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
            '@css': resolve(__dirname, 'resources/css'),
            '@images': resolve(__dirname, 'resources/images'),
            '@components': resolve(__dirname, 'resources/js/components'),
            '@utils': resolve(__dirname, 'resources/js/utils'),
            '@admin': resolve(__dirname, 'resources/js/admin'),
        },
    },
    // Experimental features
    experimental: {
        renderBuiltUrl(filename, { hostId, hostType, type }) {
            if (type === 'asset') {
                return `${process.env.VITE_APP_URL || 'http://localhost:8000'}/${filename}`;
            }
            return filename;
        },
    },
    // Define global constants
    define: {
        __APP_VERSION__: JSON.stringify(process.env.npm_package_version),
        __BUILD_DATE__: JSON.stringify(new Date().toISOString()),
        __DEV__: JSON.stringify(process.env.NODE_ENV === 'development'),
    },
});
