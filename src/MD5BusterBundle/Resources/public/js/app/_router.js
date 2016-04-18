/** define the app router that will handle relations between app URL and actions */
md5buster.Router = Marionette.AppRouter.extend({
    routes:
    {
        '': 'decryptAction',
        'encrypt': 'encryptAction',
        'contact': 'contactAction',
        'cookies': 'cookiesAction',
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
        md5buster.app.body.show( new md5buster.DecryptPage({
            model: new md5buster.DecryptPageModel()
        }));
        this.scrollPageToTop();
    },
    encryptAction: function()
    {
        md5buster.app.body.show( new md5buster.EncryptPage({
            model: new md5buster.EncryptPageModel()
        }));
        this.scrollPageToTop();
    },
    contactAction: function()
    {
        md5buster.app.body.show( new md5buster.ContactPage({
            model: new md5buster.ContactPageModel()
        }));
        this.scrollPageToTop();
    },
    cookiesAction: function()
    {
        md5buster.app.body.show( new md5buster.CookiePage() );
        this.scrollPageToTop();
    }
});
