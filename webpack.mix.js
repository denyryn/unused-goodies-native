// webpack.mix.js

let mix = require("laravel-mix");

mix.postCss("src/app.css", "public/css", [require("tailwindcss")]);
