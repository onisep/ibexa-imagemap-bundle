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

    .addEntry('imagemap_edit', './assets/js/imagemap_edit.contenttype.js')
    .addEntry('imagemap', './assets/js/imagemap.contenttype.js')
    .addEntry('imagemap_styles', './assets/js/imagemap.styles.js')
;

module.exports = Encore.getWebpackConfig();
