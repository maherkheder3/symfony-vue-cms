var Encore = require('@symfony/webpack-encore');
var ImageminPlugin = require('imagemin-webpack-plugin').default

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .autoProvidejQuery()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .autoProvideVariables({
        "window.Bloodhound": require.resolve('bloodhound-js'),
        "jQuery.tagsinput": "bootstrap-tagsinput"
    })
    .enableSassLoader()
    .enableVersioning(false)
    //.createSharedEntry('js/common', ['jquery'])

    // to test
    .disableSingleRuntimeChunk()

    .addEntry('js/app', './assets/js/app.js')
    .addStyleEntry('css/app', [
        './node_modules/vuetify/dist/vuetify.min.css',
        "vue-awesome-notifications/dist/styles/style.css",
        './assets/scss/app.scss'
    ])
    .addPlugin(
        new ImageminPlugin({
            disable: process.env.NODE_ENV === 'production', // Disable during development
            pngquant: {
                quality: '95-100'
            },
            optipng: {
                optimizationLevel: 9
            }
        })
    )

    // .addEntry('js/login', './assets/js/login.js')
    // .addEntry('js/admin', './assets/js/admin.js')
    // .addEntry('js/search', './assets/js/search.js')
    // .addStyleEntry('css/admin', ['./assets/scss/admin.scss'])
    .enableVueLoader()
;

module.exports = Encore.getWebpackConfig();
