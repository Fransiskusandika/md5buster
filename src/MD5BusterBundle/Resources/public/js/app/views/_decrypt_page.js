/**
 *
 */
md5buster.DecryptionResultsItemView = Marionette.ItemView.extend({
    tagName: 'li',
    className: 'decryption-item',
    ui: {
        copyButton: '#copy-button'
    },
    events: {
        'click @ui.copyButton': 'copyDecryption'
    },
    getTemplate: function ()
    {
        /** @namespace md5buster.templates.decryptionResultItemView */
        return _.template( md5buster.templates.decryptionResultItemView );
    },
    onShow: function ()
    {
        /** did the whole textarea thing to parse the html entities ( i.e. &abreve; which is ? */
        this.ui.copyButton.attr( 'title', $('<textarea />').html( md5buster.app.utilityFunctions.translateTerm( 'dp.ctc' ) ).text() );
    },
    copyDecryption: function ( e )
    {
        e.preventDefault();
        md5buster.app.utilityFunctions.copyTextToClipboard( this.model.get( 'decryption' ) );
    }
});

/**
 *
 */
md5buster.DecryptionResultsEmptyView = Marionette.ItemView.extend({
    tagName: 'h3',
    className: 'text-center',
    initialize: function ()
    {
        md5buster.app.utilityFunctions.enableViewTranslationSupport( this );
    },
    getTemplate: function ()
    {
        return _.template('<span translatable="dp.nrf"></span>');
    }
});

/**
 *
 */
md5buster.DecryptionResultsCollectionView = Marionette.CollectionView.extend({
    tagName: 'ul',
    className: 'decryption-results',
    childView: md5buster.DecryptionResultsItemView,
    emptyView: md5buster.DecryptionResultsEmptyView,
    getTemplate: function ()
    {
        return _.template('<ul id="decryptions" class="decryptions"></ul>');
    }
});

/**
 * decrypt page
 */
md5buster.DecryptPage = Marionette.LayoutView.extend({
    className: 'decrypt-page',
    model: new md5buster.DecryptPageModel,
    regions: {
        resultsArea: '#results-area'
    },
    ui: {
        recaptcha: '#recaptcha',
        decryptForm: '#decrypt-form',
        hash: '#hash',
        submitButton: '#submit-button',
        newSearchButton: '#new-search-button',
        tryAgainButton: '#try-again-button',
        ajaxErrorMessage: '#ajax-error',
        mainFieldset: '#main-fieldset',
        loadingFieldset: '#loading-fieldset',
        resultFieldset: '#result-fieldset',
        errorFieldset: '#error-fieldset'
    },
    events: {
        'blur @ui.hash': 'showHashValidity',
        'keyup @ui.hash': 'showHashValidity',
        'click @ui.submitButton': 'decryptHash',
        'submit': 'decryptHash',
        'click @ui.newSearchButton': 'resetDecryptForm',
        'click @ui.tryAgainButton': 'resetDecryptForm'
    },
    initialize: function ()
    {
        md5buster.app.utilityFunctions.enableViewTranslationSupport( this );
    },
    getTemplate: function ()
    {
        /** @namespace md5buster.templates.decryptPage */
        return _.template( md5buster.templates.decryptPage );
    },
    onShow: function ()
    {
        md5buster.app.radio.broadcast( 'global', 'page:change', 'decrypt' );
        this.startPlaceholderAnimationLoop();
        this.enableGoogleRecaptcha();
    },
    onDestroy: function ()
    {
        grecaptcha.reset();
    },
    enableGoogleRecaptcha: function(){
        this.model.set({ recaptchaWidgetId: md5buster.app.utilityFunctions.renderRecaptchaWidget( this.ui.recaptcha ) });
    },
    startPlaceholderAnimationLoop: function ()
    {
        var placeholderText = 'ex: 0cc175b9c0f1b6a831c399e269772661',
            length = placeholderText.length,
            _this = this;
        function startTyping( count ){
            setTimeout(function (){
                var typingLine = count != length ? '|' : '';
                if( !_this.isDestroyed ){
                    _this.ui.hash.attr('placeholder', placeholderText.slice( 0, count ) + typingLine );
                    if( count != length ){
                        startTyping( count +1 );
                    } else {
                        loop( 10000 );
                    }
                }
            }, 50 );
        }
        function loop( delay ){
            setTimeout(function (){
                startTyping( 1 );
            }, delay );
        }
        loop( 50 );
    },
    isValidMD5Hash: function ()
    {
        return (/^[a-z0-9]+$/i.test( this.ui.hash.val() ) && this.ui.hash.val().length == 32);
    },
    showHashValidity: function ()
    {
        if(  this.ui.hash.val().length > 0 ){
            if( this.isValidMD5Hash() ) {
                this.ui.hash.addClass('valid').closest('.form-group').removeClass('error');
            } else {
                this.ui.hash.removeClass('valid').closest('.form-group').addClass('error');
            }
        } else {
            this.ui.hash.removeClass('valid');
        }
    },
    decryptHash: function ( e )
    {
        e.preventDefault();
        var valid = true;
        if( !this.isValidMD5Hash() ) {
            valid = false;
            this.ui.hash.closest('.form-group').addClass('error');
        }
        if( !md5buster.app.utilityFunctions.isRecaptchaTestCompleted( this.model.get( 'recaptchaWidgetId' ) ) ){
            valid = false;
            this.ui.recaptcha.closest('.form-group').addClass('error');
        } else {
            this.ui.recaptcha.closest('.form-group').removeClass('error');
        }
        if( valid ){
            this.ui.mainFieldset.css({ display: 'none' });
            this.ui.loadingFieldset.css({ display: 'block' });
            $.ajax({
                context: this,
                method: 'POST',
                url: md5buster.apiRoutes.DECRYPT_UTL,
                data: {
                    hash: this.ui.hash.val(),
                    securityCode: md5buster.app.utilityFunctions.getRecaptchaTestResponse( this.model.get( 'recaptchaWidgetId' ) )
                },
                dataType: 'json'
            }).done( function ( response ) {
                grecaptcha.reset();
                this.resultsArea.show( new md5buster.DecryptionResultsCollectionView({
                    /** @namespace response.payload */
                    collection: new md5buster.DecryptionResultsCollection( response.payload )
                }));
                this.ui.loadingFieldset.css({ display: 'none' });
                this.ui.resultFieldset.css({ display: 'block' });

            }).fail( function ( jqXHR ) {
                grecaptcha.reset();
                this.ui.ajaxErrorMessage.html( md5buster.app.utilityFunctions.composeAjaxErrorMessages( jqXHR ) );
                this.ui.loadingFieldset.css({ display: 'none' });
                this.ui.errorFieldset.css({ display: 'block' });
            });
        }
    },
    resetDecryptForm: function ( e )
    {
        e.preventDefault();
        this.ui.hash.val('').removeClass('valid').closest('.form-group').removeClass('error');
        this.ui.ajaxErrorMessage.html('');
        this.ui.errorFieldset.css({ display: 'none' });
        this.ui.resultFieldset.css({ display: 'none' });
        this.resultsArea.reset();
        this.ui.loadingFieldset.css({ display: 'none' });
        this.ui.mainFieldset.css({ display: 'block' });
    }
});