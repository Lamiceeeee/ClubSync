const mix = require('laravel-mix');

/*--------------------------------------------------------------------------
| Mix Asset Management
|--------------------------------------------------------------------------
|
| Mix provides a clean, fluent API for defining some Webpack build steps
| for your Laravel application. By default, we are compiling the Sass
| file for the application as well as bundling up all the JS files.
|
*/

// JavaScript
mix.js('resources/js/app.js', 'public/js')
   .vue() // Only include if using Vue
   .extract(); // Extract vendor libraries

// CSS
mix.postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'), // Remove if not using Tailwind
    require('autoprefixer'),
]);

// Bootstrap Configuration
mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'],
    popper: ['Popper', 'window.Popper']
});

// Versioning (for production)
if (mix.inProduction()) {
    mix.version();
}

// BrowserSync (optional)
// mix.browserSync('localhost:8000');

// Source maps
mix.sourceMaps();