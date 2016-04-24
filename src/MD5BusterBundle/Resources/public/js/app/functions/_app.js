
md5buster.AppFunctions = Marionette.extend({
    constructor: function ()
    {
        this.fetchComponents();
    },
    fetchComponents: function ()
    {
        this.addExtraAppRegions();
        $.ajax({
            context: this,
            type: 'GET',
            url: md5buster.apiRoutes.COMPONENTS_URL,
            dataType: 'json',
            success: function(response)
            {
                md5buster.templates = response.templates;
                md5buster.translations = response.translations;
                md5buster.app.user = new md5buster.User();
                this.renderAppHeader();
                this.renderAppFooter();
                this.startRouter();
                this.showCookieFootNote();
                this.showHashCount();
            }
        });
    },
    addExtraAppRegions: function ()
    {
        $( 'body' ).append(
            '<div class="footnote-container" id="footnote"></div>' +
            '<div class="modal-container" id="modal"></div>' +
            '<div class="hash-count-container" id="hash-count"></div>'
        );
        md5buster.app.addRegions({
            footnote : '#footnote',
            modal    : '#modal',
            hashCount: '#hash-count'
        });
    },
    renderAppHeader: function ()
    {
        /** @namespace md5buster.app.header */
        md5buster.app.header.show( new md5buster.HeaderView({ model: new md5buster.AppHeaderModel }) );
    },
    renderAppFooter: function ()
    {
        /** @namespace md5buster.app.footer */
        md5buster.app.footer.show( new md5buster.FooterView() );
    },
    showHashCount: function()
    {
        $.ajax({
            context: this,
            type: 'GET',
            url: md5buster.apiRoutes.HASH_COUNT_URL,
            dataType: 'json',
            success: function( response )
            {
                if ( response.payload != undefined && response.payload.count != undefined ) {
                    md5buster.app.hashCount.show(
                        new md5buster.HashCountView({
                            model: new md5buster.HashCountModel({ count: response.payload.count })
                        })
                    );
                }
            }
        });
    },
    startRouter: function()
    {
        /** @namespace md5buster.app.body */
        md5buster.app.body.$el.html('');
        md5buster.app.router = new md5buster.Router();
        md5buster.app.router.on( 'route', function (){
            md5buster.app.generalFunctions.sendGooglePageView( Backbone.history.fragment );
        });
        Backbone.history.start({ pushState: true, root: root });
        this.removeInitialLoader();
    },
    removeInitialLoader: function ()
    {
        $( '.initial-site-loader' ).fadeOut( 'slow', function (){
            $( this ).remove();
        });
    },
    setPageTitle: function( title )
    {
        $( document ).prop( 'title', title );
    },
    showCookieFootNote: function()
    {
        if( Cookies.get( 'cookie-notice' ) !== 'true' ){
            md5buster.app.footnote.show(
                new md5buster.FootNoteView({
                    model: new md5buster.FootNoteModel({
                        type: 'cookie',
                        message: '' // will be filled by translator for type cookie
                    })
                })
            );
        }
    }
});