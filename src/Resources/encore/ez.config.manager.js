const path = require('path');

module.exports = (eZConfig, eZConfigManager) => {
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-content-edit-parts-js',
        newItems: [
            path.resolve(__dirname, '../public/build/imagemap_edit.js'),
        ],
    });
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-layout-js',
        newItems: [
            path.resolve(__dirname, '../public/build/imagemap.js'),
        ],
    });
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-layout-css',
        newItems: [
            path.resolve(__dirname, '../public/build/imagemap_edit_styles.css'),
            path.resolve(__dirname, '../public/build/imagemap_styles.css'),
        ],
    });
};
