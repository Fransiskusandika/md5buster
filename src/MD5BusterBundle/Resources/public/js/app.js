/*!
 * md5buster.com app start
 */

/** initialize app variable */
var md5buster = {};

/** define bootstrapped data */
var root = root || '/';

/** define api routes */
md5buster.apiRoutes = {
    COMPONENTS_URL  : '_api/components',
    DECRYPT_URL     : '_api/decrypt',
    ENCRYPT_URL     : '_api/encrypt',
    FEEDBACK_URL    : '_api/feedback'
};

/** define app constants */
md5buster.appConstants = {

};

/** define translations variable */
md5buster.translations = {};

/** define the templates variable */
md5buster.templates = {};

md5buster.preLoadAssets = function ( assetsArray )
{
    var assets = [];
    for( var i = 0; i < assetsArray.length; i++ ){
        assets[ i ] = new Image();
        assets[ i ].src = assetsArray[ i ];
    }
};
md5buster.preLoadAssets([

]);

/** message for users that open the console */
console.info( 'What are you looking for?! Just kidding, you can take a peek! :)');