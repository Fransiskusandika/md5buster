
/** view and actions for main header view */
md5buster.HeaderView = Marionette.LayoutView.extend({
    className: 'app-header',
    model: new md5buster.AppHeaderModel,
    getTemplate: function ()
    {
        /** @namespace md5buster.templates.appHeader */
        return _.template( md5buster.templates.appHeader );
    }
});