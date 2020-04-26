const mix = require('laravel-mix');

mix.setPublicPath('themes/app2share/assets/');

mix.config.fileLoaderDirs.fonts = 'themes/app2share/assets/fonts';
mix.config.fileLoaderDirs.images = 'themes/app2share/assets/image';

mix.sass('themes/app2share/assets/scss/style.scss', 'css');
