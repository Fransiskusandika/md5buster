
/** view and actions for main header view */
md5buster.HeaderView = Marionette.LayoutView.extend({
    className: 'app-header container',
    model: new md5buster.AppHeaderModel,
    ui: {
        decryptLink: '#decrypt-link',
        encryptLink: '#encryptLink',
        USUKFlag: '#us_uk_flag',
        ROFlag: '#ro_flag'
    },
    events: {
        'click @ui.USUKFlag': 'changeLanguageToUSUK',
        'click @ui.ROFlag': 'changeLanguageToRO'
    },
    initialize: function ()
    {
        this.listenTo( md5buster.app.globalChannel.vent, 'page:change', this.updateCurrentPage );
        md5buster.app.utilityFunctions.enableViewTranslationSupport( this );
    },
    getTemplate: function ()
    {
        /** @namespace md5buster.templates.appHeader */
        return _.template( md5buster.templates.appHeader );
    },
    changeLanguageToUSUK: function ()
    {
        this.ui.USUKFlag.addClass('shaking');
        this.ui.ROFlag.removeClass('shaking');
        md5buster.app.utilityFunctions.changeLanguage( 'us_uk' );
    },
    changeLanguageToRO: function ()
    {
        this.ui.USUKFlag.removeClass('shaking');
        this.ui.ROFlag.addClass('shaking');
        md5buster.app.utilityFunctions.changeLanguage( 'ro' );
    },
    updateCurrentPage: function ( page )
    {
        if ( page == 'decrypt' ) {
            this.ui.encryptLink.removeClass( 'active' );
            this.ui.decryptLink.addClass( 'active' );
        } else if ( page == 'encrypt' ) {
            this.ui.encryptLink.addClass( 'active' );
            this.ui.decryptLink.removeClass( 'active' );
        }
    }
});