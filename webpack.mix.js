let mix = require('laravel-mix');

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

mix.sass('resources/assets/sass/app.scss', 'public/css');

mix
  .scripts('node_modules/highlightjs/highlight.pack.js', 'public/js/temp.js')
  .js('resources/assets/js/app.js', 'public/js')
  .scripts([
    'node_modules/highlightjs/highlight.pack.js',
    'public/js/app.js',
    'node_modules/select2/dist/js/select2.js',
    'node_modules/dropzone/dist/dropzone.js',
    'node_modules/marked/lib/marked.js',
    'node_modules/jquery-tabby/jquery.textarea.js',
    'node_modules/autosize/dist/autosize.js',
    'resources/assets/js/forum.js'
  ], 'public/js/app.js');

mix.copy('node_modules/font-awesome/fonts', 'public/fonts');
