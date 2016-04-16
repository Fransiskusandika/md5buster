
md5buster.UtilityFunctions = Marionette.extend({
    constructor: function ()
    {

    },
    windowScrollToTop: function ()
    {
        window.scrollTo( 0, 0 );
    },
    hideHTMLOverflow: function ()
    {
        $( 'html' ).addClass( 'locked' );
    },
    showHTMLOverflow: function ()
    {
        $( 'html' ).removeClass( 'locked' );
    },
    enableViewTranslationSupport: function ( view )
    {
        var _this = this;
        view.listenTo( md5buster.app.globalChannel.vent, 'change:language', function (){
            _this.translateViewElements( view );
        }).on( 'show', function () {
            _this.translateViewElements( view );
        });
    },
    translateViewElements: function ( view )
    {
        var selected_language = Cookies.get('selectedLanguage')||'us_uk';
        view.$el.find('*[translatable]').each( function ( k, el ) {
           el.innerHTML = md5buster.translations[ el.getAttribute('translatable') ][ selected_language ];
        });
    },
    changeLanguage: function ( language )
    {
        Cookies.set( 'selectedLanguage', language );
        md5buster.app.radio.broadcast( 'global', 'change:language' );
    }
});