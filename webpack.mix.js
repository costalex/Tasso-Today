const { mix } = require('laravel-mix');
const argv = require('yargs').argv;
const config = require('./webpack.config');
const configDev = require('./webpack.config.dev');

module.exports = {
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js'
        }
    }
};

if (process.env.NODE_ENV === 'development') {
    mix.webpackConfig(configDev);

    mix.options({
        hmrOptions: {
            host: 'tasso.today.local',
            port: '8080'
        }
    });
}

mix.webpackConfig(config);

if (argv.env !== undefined && argv.env.maps) {
    mix.js(['resources/assets/js/app.js'], 'public/js/app.js')
        .sourceMaps();
} else {
    mix.js(['resources/assets/js/app.js'], 'public/js/app.js');
}
