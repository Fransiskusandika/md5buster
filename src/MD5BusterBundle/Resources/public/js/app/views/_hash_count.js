
md5buster.HashCountView = Marionette.ItemView.extend({
    tagName: 'div',
    className: 'hash-count',
    model: new md5buster.HashCountModel,
    ui: {
        counter: '#counter'
    },
    initialize: function ()
    {
        this.listenTo( md5buster.app.globalChannel.vent, 'hash:count:update', this.showCountChange );
    },
    getTemplate: function()
    {
        return _.template( '<span id="counter"></span>' );
    },
    onShow: function ()
    {
        this.startAnimation()
    },
    startAnimation: function ( newValue )
    {
        newValue = newValue || false;
        var _element = this.ui.counter,
            _start = newValue == false ? 0 : this.model.get( 'count' ),
            _end = newValue == false ? this.model.get( 'count' ) : newValue,
            _this = this;

        _element.text( _this.numberFormat( _start ) );
        var delay = 20, count = 0;
        function delayed () {
            count += ( _end / 100 );
            _element.text( _this.numberFormat( Math.round( count ).toFixed( 0 ) ) );
            if ( count > ( _end * 80 / 100 ) ) { delay += 10; }
            if ( count > ( _end * 95 / 100 ) ) { _element.animate( { 'color':'#f0f0f0' }, 1500 ); delay += 50; }
            if ( count < ( _end * 100 / 100 ) ) { setTimeout( delayed, delay ); }
            if ( count >= ( _end * 100 / 100 ) ) { _element.text( _this.numberFormat( _end ) ); }
        }
        delayed();
    },
    numberFormat: function ( count )
    {
        count += '';
        count = count.replace(new RegExp("^(\\d{" + (count.length%3? count.length%3:0) + "})(\\d{3})", "g"), "$1 $2").replace(/(\d{3})+?/gi, "$1 ").trim();
        count = count.replace(/\s/g, ',');

        return count;
    },
    showCountChange: function ( count )
    {
        this.startAnimation( count );
    }
});