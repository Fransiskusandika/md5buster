/** define the app router that will handle relations between app URL and actions */
md5buster.Router = Marionette.AppRouter.extend({
    routes:
    {
        '': 'homeAction',
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
    homeAction: function()
    {
        this.showLandingPage();
    },
    showLandingPage: function ()
    {
        md5buster.app.landingPage.show( new md5buster.LandingPage() );
        this.scrollPageToTop();
    }
});
