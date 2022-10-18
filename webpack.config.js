var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('./src/Resources/public/build/')
    .setPublicPath('')
    .setManifestKeyPrefix('')

    .cleanupOutputBeforeBuild()
    .enableSassLoader()
    .enableSourceMaps(false)
    .enableVersioning(true)
    .disableSingleRuntimeChunk()

    .addEntry('imagemap_edit', './assets/js/imagemap_edit.js')
    .addEntry('imagemap_styles', './assets/js/imagemap_styles.js')
;

module.exports = Encore.getWebpackConfig();
