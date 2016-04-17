/**
 *  footer view
 */
md5buster.FooterView = Marionette.ItemView.extend({
    tagName: 'div',
    className: 'footer',
    initialize: function ()
    {
        md5buster.app.utilityFunctions.enableViewTranslationSupport( this );
    },
    getTemplate: function()
    {
        /** @namespace md5buster.templates.footer */
        return _.template( md5buster.templates.footer );
    }
});