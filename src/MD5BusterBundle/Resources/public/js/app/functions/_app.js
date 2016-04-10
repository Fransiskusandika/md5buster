
md5buster.AppFunctions = Marionette.extend({
    constructor: function ()
    {
        this.fetchTemplates();
    },
    fetchTemplates: function ()
    {
        this.addExtraAppRegions();
        $.ajax({
            context: this,
            type: 'GET',
            url: md5buster.apiRoutes.TEMPLATES_URL,
            dataType: 'json',
            success: function(response)
            {
                md5buster.templates = response;
                this.renderAppHeader();
                this.fetchUserInfo();
            }
        });
    },
    addExtraAppRegions: function ()
    {
        $( 'body' ).append(
            '<div class="footnote-container" id="footnote"></div>' +
            '<div class="modal-container" id="modal"></div>'
        );
        md5buster.app.addRegions({
            footnote      : '#footnote',
            modal         : '#modal'
        });
    },
    renderAppHeader: function ()
    {
        /** @namespace md5buster.app.header */
        md5buster.app.header.show( new md5buster.HeaderView({ model: new md5buster.AppHeaderModel }) );
    },
    fetchUserInfo: function()
    {
        this.startRouter();
        this.showCookieFootNote();
        md5buster.app.user = new md5buster.User();
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
                        message: 'This site uses cookies in order to improve your experience. By continuing to browse the site ' +
                        'you are agreeing to our use of cookies. <a href="' + '/help' + '">More Info</a>'
                    })
                })
            );
        }
    }
});