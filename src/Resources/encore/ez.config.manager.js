const path = require('path');

module.exports = (ibexaConfig, ibexaConfigManager) => {
    ibexaConfigManager.add({
        ibexaConfig,
        entryName: 'ibexa-admin-ui-content-edit-parts-js',
        newItems: [
            path.resolve(__dirname, '../public/build/imagemap_edit.js'),
        ],
    });
    ibexaConfigManager.add({
        ibexaConfig,
        entryName: 'ibexa-admin-ui-layout-js',
        newItems: [
            path.resolve(__dirname, '../public/build/imagemap.js'),
        ],
    });
    ibexaConfigManager.add({
        ibexaConfig,
        entryName: 'ibexa-admin-ui-layout-css',
        newItems: [
            path.resolve(__dirname, '../public/build/imagemap_edit_styles.css'),
            path.resolve(__dirname, '../public/build/imagemap_styles.css'),
        ],
    });
};
