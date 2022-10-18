const path = require('path');

module.exports = (eZConfig, eZConfigManager) => {
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-content-edit-parts-js',
        newItems: [
            path.resolve(__dirname, '../public/build/imagemap_edit.9ad14f36.js'),
        ],
    });
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-layout-js',
        newItems: [
            path.resolve(__dirname, '../public/build/imagemap.776ed25a.js'),
        ],
    });
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-layout-css',
        newItems: [
            path.resolve(__dirname, '../public/build/imagemap_styles.9a6339fb.css'),
        ],
    });
};
