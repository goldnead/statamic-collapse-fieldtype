const mix = require("laravel-mix");

mix.js("resources/js/cp.js", "resources/dist/js/cp.js").vue({ version: 2 });
mix.sass("resources/scss/styles.scss", "resources/dist/css/styles.css");

// Disable mix-manifest.json
Mix.manifest.refresh = _ => void 0