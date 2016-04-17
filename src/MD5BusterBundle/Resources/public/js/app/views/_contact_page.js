
/**
 * contact page
 */
md5buster.ContactPage = Marionette.ItemView.extend({
    className: 'contact-page',
    model: new md5buster.ContactPageModel,
    ui: {
        recaptcha: '#recaptcha',
        name: '#name',
        email: '#email',
        feedback: '#feedback',
        submitButton: '#submit-button',
        tryAgainButton: '#try-again-button',
        ajaxErrorMessage: '#ajax-error',
        mainFieldset: '#main-fieldset',
        loadingFieldset: '#loading-fieldset',
        successFieldset: '#success-fieldset',
        errorFieldset: '#error-fieldset'
    },
    events: {
        'blur @ui.email': 'showEmailValidity',
        'keyup @ui.email': 'showEmailValidity',
        'blur @ui.name': 'showNameValidity',
        'keyup @ui.name': 'showNameValidity',
        'blur @ui.feedback': 'showFeedbackValidity',
        'keyup @ui.feedback': 'showFeedbackValidity',
        'click @ui.submitButton': 'sendFeedback',
        'submit': 'sendFeedback',
        'click @ui.tryAgainButton': 'resetContactForm'
    },
    initialize: function ()
    {
        md5buster.app.utilityFunctions.enableViewTranslationSupport( this );
    },
    getTemplate: function ()
    {
        /** @namespace md5buster.templates.contactPage */
        return _.template( md5buster.templates.contactPage );
    },
    onShow: function ()
    {
        md5buster.app.radio.broadcast( 'global', 'page:change', 'contact' );
        this.enableGoogleRecaptcha();
    },
    onDestroy: function ()
    {
        grecaptcha.reset();
    },
    enableGoogleRecaptcha: function(){
        this.model.set({ recaptchaWidgetId: md5buster.app.utilityFunctions.renderRecaptchaWidget( this.ui.recaptcha ) });
    },
    showEmailValidity: function ()
    {
        if(  this.ui.email.val().length > 0 ){
            if( md5buster.app.utilityFunctions.isValidEmailString( this.ui.email.val() ) ) {
                this.ui.email.closest('.form-group').removeClass('error');
            } else {
                this.ui.email.closest('.form-group').addClass('error');
            }
        } else {
            this.ui.email.closest('.form-group').addClass('error');
        }
    },
    showNameValidity: function ()
    {
        if(  this.ui.name.val().length > 0 ){
            this.ui.name.closest('.form-group').removeClass('error');
        } else {
            this.ui.name.closest('.form-group').addClass('error');
        }
    },
    showFeedbackValidity: function ()
    {
        if(  this.ui.feedback.val().length > 0 ){
            this.ui.feedback.closest('.form-group').removeClass('error');
        } else {
            this.ui.feedback.closest('.form-group').addClass('error');
        }
    },
    sendFeedback: function ( e )
    {
        e.preventDefault();
        var valid = true;
        if( this.ui.email.val().length == 0 ) {
            valid = false;
            this.ui.name.closest('.form-group').addClass('error');
        }
        if( !md5buster.app.utilityFunctions.isValidEmailString( this.ui.email.val() ) ) {
            valid = false;
            this.ui.email.closest('.form-group').addClass('error');
        }
        if( this.ui.feedback.val().length == 0 ) {
            valid = false;
            this.ui.feedback.closest('.form-group').addClass('error');
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
                url: md5buster.apiRoutes.FEEDBACK_URL,
                data: {
                    name: this.ui.name.val(),
                    email: this.ui.email.val(),
                    feedback: this.ui.feedback.val(),
                    securityCode: md5buster.app.utilityFunctions.getRecaptchaTestResponse( this.model.get( 'recaptchaWidgetId' ) )
                },
                dataType: 'json'
            }).done( function () {
                grecaptcha.reset();
                this.ui.loadingFieldset.css({ display: 'none' });
                this.ui.successFieldset.css({ display: 'block' });

            }).fail( function ( jqXHR ) {
                grecaptcha.reset();
                this.ui.ajaxErrorMessage.html( md5buster.app.utilityFunctions.composeAjaxErrorMessages( jqXHR ) );
                this.ui.loadingFieldset.css({ display: 'none' });
                this.ui.errorFieldset.css({ display: 'block' });
            });
        }
    },
    resetContactForm: function ( e )
    {
        e.preventDefault();
        this.ui.name.val('').closest('.form-group').removeClass('error');
        this.ui.email.val('').closest('.form-group').removeClass('error');
        this.ui.feedback.val('').closest('.form-group').removeClass('error');
        this.ui.ajaxErrorMessage.html('');
        this.ui.errorFieldset.css({ display: 'none' });
        this.ui.successFieldset.css({ display: 'none' });
        this.ui.loadingFieldset.css({ display: 'none' });
        this.ui.mainFieldset.css({ display: 'block' });
    }
});