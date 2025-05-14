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
module.exports = {
  plugins: [
    require('postcss-import'),
    require('@tailwindcss/nesting'),
    require('tailwindcss'),
    require('autoprefixer'),
  ]
}
module.exports = {
  plugins: [
    require('postcss-import'),
    require('@tailwindcss/nesting')(require('postcss-nesting')),
    require('tailwindcss'),
    require('autoprefixer'),
  ]
}
// JavaScript Configuration
mix.js('resources/js/app.js', 'public/js')
   .vue() // Only include if using Vue
   .extract() // Extract vendor libraries
   .sourceMaps(); // Enable source maps

// CSS Configuration
mix.postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('postcss-nesting'),  // Alternative to tailwindcss/nesting
    require('tailwindcss'),
    require('autoprefixer'),
    require('postcss-discard-duplicates')
]);

// Bootstrap/JQuery Configuration
mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'],
    popper: ['Popper', 'window.Popper', 'popper.js']
});

// Asset Versioning (Production Only)
if (mix.inProduction()) {
    mix.version()
       .options({
           terser: {
               terserOptions: {
                   compress: {
                       drop_console: true // Remove console logs in production
                   }
               }
           }
       });
}

// BrowserSync Configuration (Optional)
// mix.browserSync({
//     proxy: 'localhost:8000',
//     notify: false,
//     open: false
// });

// Webpack Config Overrides
mix.webpackConfig({
    stats: {
        children: true // Show more detailed build information
    },
    performance: {
        hints: false // Disable performance hints
    }
});