
/**
 * encrypt page
 */
md5buster.EncryptPage = Marionette.LayoutView.extend({
    className: 'encrypt-page',
    model: new md5buster.EncryptPageModel,
    regions: {
        resultsArea: '#results-area'
    },
    ui: {
        recaptcha: '#recaptcha',
        encryptForm: '#encrypt-form',
        text: '#text',
        textToEncrypt: '#text-to-encrypt',
        submitButton: '#submit-button',
        newEncryptionButton: '#new-encryption-button',
        tryAgainButton: '#try-again-button',
        ajaxErrorMessage: '#ajax-error',
        mainFieldset: '#main-fieldset',
        loadingFieldset: '#loading-fieldset',
        resultFieldset: '#result-fieldset',
        errorFieldset: '#error-fieldset'
    },
    events: {
        'blur @ui.text': 'showTextValidity',
        'keyup @ui.text': 'showTextValidity',
        'click @ui.submitButton': 'encryptText',
        'submit': 'encryptText',
        'click @ui.newEncryptionButton': 'resetEncryptForm',
        'click @ui.tryAgainButton': 'resetEncryptForm'
    },
    initialize: function ()
    {
        md5buster.app.utilityFunctions.enableViewTranslationSupport( this );
    },
    getTemplate: function ()
    {
        /** @namespace md5buster.templates.encryptPage */
        return _.template( md5buster.templates.encryptPage );
    },
    onShow: function ()
    {
        md5buster.app.radio.broadcast( 'global', 'page:change', 'encrypt' );
        this.enableGoogleRecaptcha();
    },
    onDestroy: function ()
    {
        grecaptcha.reset();
    },
    showTextValidity: function ()
    {
        if(  this.ui.text.val().length > 0 ){
            this.ui.text.closest('.form-group').removeClass('error');
        } else {
            this.ui.text.closest('.form-group').addClass('error');
        }
    },
    enableGoogleRecaptcha: function(){
        this.model.set({ recaptchaWidgetId: md5buster.app.utilityFunctions.renderRecaptchaWidget( this.ui.recaptcha ) });
    },
    encryptText: function ( e )
    {
        e.preventDefault();
        var valid = true;
        if( this.ui.text.val().length == 0 ) {
            valid = false;
            this.ui.text.closest('.form-group').addClass('error');
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
            grecaptcha.reset();
            $.ajax({
                context: this,
                method: 'POST',
                url: md5buster.apiRoutes.ENCRYPT_URL,
                data: {
                    text: this.ui.text.val(),
                    securityCode: md5buster.app.utilityFunctions.getRecaptchaTestResponse( this.model.get( 'recaptchaWidgetId' ) )
                },
                dataType: 'json'
            }).done( function ( response ) {
                grecaptcha.reset();
                this.resultsArea.show( new md5buster.DecryptionResultsCollectionView({
                    /** @namespace response.payload */
                    collection: new md5buster.DecryptionResultsCollection( response.payload )
                }));
                this.ui.textToEncrypt.text( this.ui.text.val() );
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
    resetEncryptForm: function ( e )
    {
        e.preventDefault();
        grecaptcha.reset();
        this.ui.text.val('').closest('.form-group').removeClass('error');
        this.ui.textToEncrypt.text( '' );
        this.ui.ajaxErrorMessage.html('');
        this.ui.errorFieldset.css({ display: 'none' });
        this.ui.resultFieldset.css({ display: 'none' });
        this.resultsArea.reset();
        this.ui.loadingFieldset.css({ display: 'none' });
        this.ui.mainFieldset.css({ display: 'block' });
    }
});