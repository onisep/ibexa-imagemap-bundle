var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('./src/Resources/public/build/')
    .setPublicPath('')
    .setManifestKeyPrefix('')
    .addExternals({
        react: 'React',
        'react-dom': 'ReactDOM',
    })

    .cleanupOutputBeforeBuild()
    .enableSassLoader()
    .enableSourceMaps(false)
    .disableSingleRuntimeChunk()

    .addEntry('imagemap_edit', './assets/js/imagemap_edit.js')
    .addEntry('imagemap_edit_styles', './assets/js/imagemap_edit_styles.js')
    .addEntry('imagemap_styles', './assets/js/imagemap_styles.js')
    .addEntry('imagemap', './assets/js/imagemap.js')
;

module.exports = Encore.getWebpackConfig();
