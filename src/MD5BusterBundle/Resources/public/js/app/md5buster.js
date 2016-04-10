/**
 * Our Marionette app
 */
md5buster.app = new Marionette.Application();

/**
 * The main regions of our app
 */
md5buster.app.addRegions({
    header        : '#header',
    body   : '#body'
});

/** we initialize app functions */
md5buster.app.addInitializer(function()
{
    md5buster.app.generalFunctions = new md5buster.GeneralFunctions;
    md5buster.app.appFunctions     = new md5buster.AppFunctions;
    md5buster.app.radio            = new md5buster.RadioFunctions;
    md5buster.app.utilityFunctions = new md5buster.UtilityFunctions;
});

/**
 * Our app data variable
 */
md5buster.app.addInitializer(function()
{
    md5buster.app.data = {};
});

/**
 * Now we launch the app
 */
$( window ).on( 'load', function()
{
    md5buster.app.start();
});
