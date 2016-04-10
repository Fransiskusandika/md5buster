
md5buster.DecryptPage = Marionette.ItemView.extend({
    className: 'decrypt-page',
    model: new md5buster.DecryptPageModel,
    getTemplate: function ()
    {
        return _.template( md5buster.templates.decryptPage );
    }
});