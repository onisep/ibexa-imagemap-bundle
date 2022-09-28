const path = require('path');

module.exports = (eZConfig, eZConfigManager) => {
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-content-edit-parts-js',
        newItems: [
            path.resolve(__dirname, '../public/js/imagemap_edit.contenttype.js'),
        ],
    });
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-layout-js',
        newItems: [
            path.resolve(__dirname, './assets/ezplatform/js/imagemap.contenttype.js'),
        ],
    });
    eZConfigManager.add({
        eZConfig,
        entryName: 'ezplatform-admin-ui-layout-css',
        newItems: [
            path.resolve(__dirname, './assets/ezplatform/css/imagemap.contenttype.scss'),
        ],
    });
};
