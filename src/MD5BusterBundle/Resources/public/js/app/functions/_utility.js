
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
    /**
     * @param view
     */
    enableViewTranslationSupport: function ( view )
    {
        var _this = this;
        view.listenTo( md5buster.app.globalChannel.vent, 'change:language', function (){
            _this.translateViewElements( view );
        }).on( 'show', function () {
            _this.translateViewElements( view );
        });
    },
    /**
     * @param view
     */
    translateViewElements: function ( view )
    {
        view.$el.find('*[translatable]').each( function ( k, el ) {
           el.innerHTML = md5buster.translations[ el.getAttribute('translatable') ][ Cookies.get('selectedLanguage')||'us_uk' ];
        });
    },
    /**
     * @param term
     * @returns {*}
     */
    translateTerm: function ( term )
    {
        return md5buster.translations[ term ][ Cookies.get('selectedLanguage')||'us_uk' ];
    },
    /**
     * @param language
     */
    changeLanguage: function ( language )
    {
        Cookies.set( 'selectedLanguage', language );
        md5buster.app.radio.broadcast( 'global', 'change:language' );
    },
    /**
     * @param jqXHR
     * @returns {string}
     */
    composeAjaxErrorMessages: function( jqXHR )
    {
        return JSON.parse(jqXHR.responseText)['text'] = JSON.parse(jqXHR.responseText)['text'] ||
            'Something went wrong! Error <strong>' + jqXHR.status + '</strong> ( ' + jqXHR.statusText + ' )';
    },
    /**
     * @param uiElement
     * @returns {*}
     */
    renderRecaptchaWidget: function( uiElement )
    {
        /** @namespace grecaptcha */
        /** @namespace grecaptcha.getResponse */
        if( uiElement.attr('id') != undefined && uiElement.attr('data-sitekey') != undefined ){
            return grecaptcha.render( uiElement.attr('id'), {
                sitekey: uiElement.attr( 'data-sitekey' )
            });
        } else {
            return false;
        }
    },
    /**
     * @param recaptchaWidgetId
     * @returns {boolean}
     */
    isRecaptchaTestCompleted: function( recaptchaWidgetId )
    {
        return grecaptcha.getResponse( recaptchaWidgetId ) != '';
    },
    /**
     * @param recaptchaWidgetId
     * @returns {*}
     */
    getRecaptchaTestResponse: function( recaptchaWidgetId )
    {
        return grecaptcha.getResponse( recaptchaWidgetId );
    },
    /**
     * @param text
     * @returns {*}
     */
    copyTextToClipboard: function ( text )
    {
        var target = document.createElement("textarea");
        target.style.position = "absolute";
        target.style.left = "-9999px";
        //noinspection JSValidateTypes
        target.style.top = $(window).scrollTop();
        target.id = 'temp_id';
        document.body.appendChild(target);
        target.textContent = text;
        target.focus();
        target.setSelectionRange(0, target.value.length);
        var succeed;
        try {
            succeed = document.execCommand("copy");
        } catch(e) {
            succeed = false;
        }
        $( '#' + target.id ).remove();

        return succeed;
    },
    /**
     * @param string
     * @returns {boolean}
     */
    isValidEmailString: function ( string )
    {
        return ( /^([a-zA-Z0-9_.+-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( string ) ) == true;
    }
});