
md5buster.FootNoteView = Marionette.ItemView.extend({
    tagName: 'div',
    className: 'footnote',
    model: new md5buster.FootNoteModel,
    ui: {
        dismiss: '#dismiss'
    },
    events: {
        'click @ui.dismiss': 'dismissFootNote'
    },
    getTemplate: function()
    {
        /** @namespace md5buster.templates.footNote */
        return _.template( md5buster.templates.footNote );
    },
    dismissFootNote: function()
    {
        Cookies.set( this.model.get( 'type' ) + '-notice', true, { expires: 365 } );
        this.$el.animate({
            opacity: 0
        }, 200, $.proxy(function (){
            this.destroy();
        }, this ) );
    }
});