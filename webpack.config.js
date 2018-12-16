/*
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

// webpack.config.js
var Encore = require('@symfony/webpack-encore');

var CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
// the project directory where all compiled assets will be stored
    .setOutputPath('src/XRayLP/LearningCenterBundle/Resources/public/js/')

    // the public path used by the web server to access the previous directory
    .setPublicPath('/public')

    // will create public/build/app.js and public/build/app.css
    .addEntry('app', './assets/learningcenter/js/app.js')

    // allow sass/scss files to be processed
    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: false
    })

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // show OS notifications when builds finish/fail
    .enableBuildNotifications()

// create hashed filenames (e.g. app.abc123.css)
// .enableVersioning()
;

// export the final configuration
module.exports = Encore.getWebpackConfig();