
md5buster.GeneralFunctions = Marionette.extend({
    constructor: function ()
    {
        this.enableAjaxPrefilter();
        this.noHashTagsPlease();
        this.enableGoogleAnalytics();
    },
    enableGoogleAnalytics: function()
    {
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)};i[r].l=1*new Date();a=s.createElement(o);
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        /** @namespace ga */
        ga('create', 'UA-56243816-4', 'auto');
    },
    sendGooglePageView: function ( uri )
    {
        typeof ga == 'function' ? ga('send', 'pageview', uri ) : null;
    },
    /**
     * Here we provide support for symfony app_dev in ajax calls
     */
    enableAjaxPrefilter: function()
    {
        $.ajaxPrefilter(function(options)
        {
            options.url = root + options.url;
        });
    },
    /**
     *  We define a route filter that handles all application links and passes it
     *  through our router, this is done to eliminate the use of "#/" in our links and URL's
     */
    noHashTagsPlease: function()
    {
        /**
         * Use absolute URLs to navigate to anything not in your Router.
         * Note: this version works with IE. Backbone.history.navigate will automatically route the IE user to the appropriate hash URL
         * Use delegation to avoid initial DOM selection and allow all matching elements to bubble
         */
        $(document).delegate("a", "click", function( e )
        {
            /** Get the anchor href and protocol */
            var href = $(this).attr("href");
            var protocol = this.protocol + "//";
            if (href != undefined)
            {
                href = href.replace(root, '/');
                /**
                 * Ensure the protocol is not part of URL, meaning its relative.
                 * Stop the event bubbling to ensure the link will not cause a page refresh.
                 */
                if (href.slice(protocol.length) !== protocol && protocol !== 'javascript://' && href.substring(0, 1) !== '#' &&
                    href.substring(0, 7) !== 'http://' && href.substring(0, 8) !== 'https://')
                {
                    e.preventDefault();

                    md5buster.app.router.navigate(href, { trigger: true });
                }
            }
        });
    }
});