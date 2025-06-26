const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// Compile main app assets
// mix.ts('resources/js/app.js', 'public/js') // Use TypeScript with React support
mix.postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
])
.version(); // Add versioning for cache busting
