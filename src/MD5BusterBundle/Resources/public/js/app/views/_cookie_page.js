/**
 * Created by MiKoRiza-OnE on 4/18/2016.
 */

md5buster.CookiePage = Marionette.ItemView.extend({
    className: 'cookie-page',
    getTemplate: function ()
    {
        /** @namespace md5buster.templates.cookiePage */
        return _.template( md5buster.templates.cookiePage );
    },
    onShow: function ()
    {
        md5buster.app.radio.broadcast( 'global', 'page:change', 'cookies' );
    }
});