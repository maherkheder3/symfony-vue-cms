const Encore = require('@symfony/webpack-encore');
const ImageminPlugin = require('imagemin-webpack-plugin').default
const WorkboxPlugin = require('workbox-webpack-plugin');
const WebpackPwaManifest = require('webpack-pwa-manifest')
const htmlPlugin = require('htmlPlugin')

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
    .addPlugin(
        new htmlPlugin({
            filename: 'index.html',
            title: 'Get Started With Workbox For Webpack'
        }),
    )
    .addPlugin(
        new WorkboxPlugin.GenerateSW({
            // Exclude images from the precache
            exclude: [/\.(?:png|jpg|jpeg|svg)$/],

            // Define runtime caching rules.
            runtimeCaching: [{
                // Match any request ends with .png, .jpg, .jpeg or .svg.
                urlPattern: '/',

                // Apply a cache-first strategy.
                handler: 'CacheFirst',  // StaleWhileRevalidate

                options: {
                    // Use a custom cache name.
                    cacheName: 'images',

                    // Only cache 10 images.
                    expiration: {
                        maxEntries: 10,
                    },
                }
            },
                {
                    // Match any request ends with .png, .jpg, .jpeg or .svg.
                    urlPattern: '/home',

                    clientsClaim: true,
                    skipWaiting: true,

                    // Apply a cache-first strategy.
                    handler: 'CacheFirst',  // StaleWhileRevalidate

                    options: {
                        // Use a custom cache name.
                        cacheName: 'homepage',
                    }
                }
            ],
        })
    )
    .addPlugin(
        new WebpackPwaManifest({
            name: 'My Progressive Web App',
            short_name: 'MyPWA',
            description: 'My awesome Progressive Web App!',
            start_url: "/",
            background_color: '#ffffff',
            crossorigin: 'use-credentials', //can be null, use-credentials or anonymous
            icons: [
                // {
                //     src: path.resolve('src/assets/icon.png'),
                //     sizes: [96, 128, 192, 256, 384, 512] // multiple sizes
                // },
                // {
                //     src: '/uploads/apple-touch-icon-57x57.png',
                //     size: '57x57' // you can also use the specifications pattern
                // }
            ]
        })

    )

    // .addEntry('js/login', './assets/js/login.js')
    // .addEntry('js/admin', './assets/js/admin.js')
    // .addEntry('js/search', './assets/js/search.js')
    // .addStyleEntry('css/admin', ['./assets/scss/admin.scss'])
    .enableVueLoader()
;

module.exports = Encore.getWebpackConfig();
