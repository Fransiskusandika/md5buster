
md5buster.DecryptPage = Marionette.ItemView.extend({
    className: 'decrypt-page',
    model: new md5buster.DecryptPageModel,
    initialize: function ()
    {
        md5buster.app.utilityFunctions.enableViewTranslationSupport( this );
    },
    onShow: function ()
    {
        md5buster.app.radio.broadcast( 'global', 'page:change', 'decrypt' );
    },
    getTemplate: function ()
    {
        /** @namespace md5buster.templates.decryptPage */
        return _.template( md5buster.templates.decryptPage );
    }
});