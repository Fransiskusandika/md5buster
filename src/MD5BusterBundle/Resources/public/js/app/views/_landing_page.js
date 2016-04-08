
md5buster.LandingPage = Marionette.ItemView.extend({
    className: 'landing-page',
    model: new md5buster.LandingPageModel,
    getTemplate: function ()
    {
        return _.template( md5buster.templates.landingPage );
    }
});