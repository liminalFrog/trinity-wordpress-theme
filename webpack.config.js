const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';
    
    return {
        entry: {
            theme: './assets/js/theme.js',
            customizer: './assets/js/customizer.js',
            blocks: './assets/js/blocks/blocks.js'
        },
        output: {
            path: path.resolve(__dirname, 'assets/dist'),
            filename: '[name].min.js',
            clean: true
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env']
                        }
                    }
                },
                {
                    test: /\.scss$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        'sass-loader'
                    ]
                }
            ]
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: '[name].min.css'
            })
        ],
        devtool: isProduction ? 'source-map' : 'eval-source-map',
        optimization: {
            minimize: isProduction
        },
        resolve: {
            extensions: ['.js', '.scss', '.css']
        }
    };
};
