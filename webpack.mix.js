const mix = require('laravel-mix');
const path = require('path'); // Add this line

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('public')
    .setResourceRoot('../') // Turns assets paths in css relative to css file
    .vue({
        version: 3,
        template: {
            compilerOptions: {
              compatConfig: {
                MODE: 2
              }
            }
          }
    })
    .sass('resources/sass/frontend/main/main.scss', 'css/styles.css')
    .sass('resources/sass/frontend/app.scss', 'css/frontend.css')
    // .sass('resources/sass/backend/app.scss', 'css/backend.css')
    // .copy('node_modules/@coreui/coreui/dist/js/coreui.bundle.js', 'public/js')
    .js('resources/js/frontend/app.js', 'js/frontend.js')
    // .js('resources/js/backend/app.js', 'js/backend.js')
    .extract([
        'alpinejs',
        'jquery',
        'bootstrap',
        'axios',
        'sweetalert2',
        'lodash'
    ])
    .sourceMaps()
    .alias({
        '@frontend': path.resolve('resources/js/frontend'),
        '@' :  path.resolve('resources/js')
    });

if (mix.inProduction()) {
    mix.version();
} else {
    // Uses inline source-maps on development
    mix.webpackConfig({
        devtool: 'inline-source-map',
    });
}
