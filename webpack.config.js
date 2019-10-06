/*
 * @copyright Copyright 2019 | Human Connection gemeinnützige GmbH - Alle Rechte vorbehalten.
 * @author    Matthias Böhm <mail@matthiasboehm.com>
 */

module.exports = {
    mode: 'production',
    entry: './coc/assets/js/coc.js',
    output: {
        path: __dirname + '/coc/assets/js', //__dirname + '/dist',
        publicPath: '/',
        filename: 'coc.min.js'
    },
    module: {
        rules: [
            {
                test: /\.(js)$/,
                exclude: /node_modules/,
                use: ['babel-loader']
            }
        ]
    },
    resolve: {
        extensions: ['*', '.js']
    },
};
