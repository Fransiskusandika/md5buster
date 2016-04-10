/** define the app router that will handle relations between app URL and actions */
md5buster.Router = Marionette.AppRouter.extend({
    routes:
    {
        '': 'decryptAction',
        ':placeholder': 'undefinedAction',
        ':placeholder/:placeholder': 'undefinedAction',
        ':placeholder/:placeholder/:placeholder': 'undefinedAction',
        ':placeholder/:placeholder/:placeholder/:placeholder': 'undefinedAction',
        ':placeholder/:placeholder/:placeholder/:placeholder/:placeholder': 'undefinedAction',
        ':placeholder/:placeholder/:placeholder/:placeholder/:placeholder/:placeholder': 'undefinedAction',
        ':placeholder/:placeholder/:placeholder/:placeholder/:placeholder/:placeholder/:placeholder': 'undefinedAction',
        ':placeholder/:placeholder/:placeholder/:placeholder/:placeholder/:placeholder/:placeholder/:placeholder': 'undefinedAction'
    },
    undefinedAction: function()
    {
        Backbone.history.navigate( '', { trigger: true } );
    },
    scrollPageToTop: function ()
    {
        md5buster.app.utilityFunctions.windowScrollToTop();
    },
    decryptAction: function()
    {
        this.showDecryptPage();
    },
    showDecryptPage: function ()
    {
        md5buster.app.body.show( new md5buster.DecryptPage({
            model: new md5buster.DecryptPageModel()
        }));
        this.scrollPageToTop();
    }
});
