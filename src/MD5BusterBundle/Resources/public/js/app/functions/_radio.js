
md5buster.RadioFunctions = Marionette.extend({
    constructor: function()
    {
        this.initializeRadioChannels();
    },
    initializeRadioChannels: function()
    {
        md5buster.app.globalChannel   = Backbone.Wreqr.radio.channel('global');
    },
    broadcast: function(channel, message, data)
    {
        data = data || null;
        Backbone.Wreqr.radio.vent.trigger(channel, message, data);
    }
});