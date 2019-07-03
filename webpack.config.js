const path = require('path');

module.exports = {
    module: {
        rules: [
            {
                test: /\.css$/,
                loaders: ['style-loader', 'css-loader']
            },
            {
                test: /\.scss$/,
                use: [
                    'vue-style-loader',
                    'css-loader',
                    'sass-loader'
                ],
                exclude: /resources\/assets\/sass/
            }
        ]
    },
    resolve: {
        extensions: ['.js', '.json', '.vue'],
        alias: {
            '@': path.resolve(__dirname, './resources/assets/js'),
            'sass': path.resolve(__dirname, './resources/assets/sass')
        }
    }
};
