(function( $ ) {
    'use strict';

    // Create the defaults once
    var pluginName = "wordpress_reactro",
        defaults = {
            messagingSenderId : "",
            projectId:"",
            apiKey:"",
            appId:"",
            loggedIn:0,
            nonce:"",
        };

    // The actual plugin constructor
    function Plugin ( element, options ) {
        this.element = element;
        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        if(!this.settings.messagingSenderId || this.settings.messagingSenderId == "") {
            return false;
        }
        this.init();
        this.popup();
    }

    // Avoid Plugin.prototype conflicts
    $.extend( Plugin.prototype, {
        init: function() {
            var that = this;
            this.window = $(window);
            this.documentHeight = $( document ).height();
            this.windowHeight = this.window.height();

            if ('serviceWorker' in navigator) {
                try {
                    this.initFirebase();
                } catch (e) {
                    console.log('Unable to Instantiate Firebase Messaging. Browser not supported.', e);
                }
            }
        },
        initFirebase : function() {

            var config = {
                messagingSenderId: this.settings.messagingSenderId,
                projectId: this.settings.projectId,
                apiKey: this.settings.apiKey,
                appId:this.settings.appId,
            };
            this.firebase = firebase.initializeApp(config);            
        },
        popup : function() {
            var that = this;
            if ('Notification' in window) {
                var popupAction = that.getCookie('wordpress_reactro_popup');
                if((Notification.permission == 'default' || Notification.permission == 'denied') && (popupAction !== "declined")) {
                    var popupContainer = $('.wordpress-reactro-popup-container');
                    if(popupContainer.length > 0) {
                        popupContainer.show();
                        $('.wordpress-reactro-popup-agree').on('click', function(e) {
                            e.preventDefault();
                            Notification.requestPermission(function (p) {
                                if (p !== 'denied') {
                                    that.createCookie('wordpress_reactro_popup', 'agreed', 999999999);
                                    popupContainer.fadeOut();
                                    that.initMessaging();
                                } else {
                                    console.log(that.settings.deniedText);
                                }
                            })
                        });
                        $('.wordpress-reactro-popup-decline, .wordpress-reactro-popup-close').on('click', function(e) {
                            e.preventDefault();
                            that.createCookie('wordpress_reactro_popup', 'declined', 999999999);
                            popupContainer.fadeOut();
                        });

                    } else {
                        Notification.requestPermission(function (p) {
                            if (p !== 'denied') {
                                that.createCookie('wordpress_reactro_popup', 'agreed', 999999999);
                                popupContainer.fadeOut();
                                that.initMessaging();
                            } else {
                                console.log(that.settings.deniedText);
                            }
                        })
                    }
                } else if (Notification.permission == 'granted') {
                    that.initMessaging();
                }
            }
        },
       
        initMessaging : function() {
            var that = this;
            const messaging = this.firebase.messaging();
            navigator.serviceWorker.register(this.settings.swURL)
            .then((registration) => {
                messaging.useServiceWorker(registration);
                messaging.requestPermission()
                // Notification permission granted.
                .then(function() {
                    return messaging.getToken();
                })
                // Send Token to Save
                .then(function(token) {
                    var response = {};
                    var reactroStore = JSON.parse(window.localStorage.getItem('reactroStore'));
                    if(token && reactroStore &&  reactroStore.token == token){
                        var isLoggedIn=that.settings.loggedIn ;
                        if((reactroStore.status =='guest' || reactroStore.status =='none') && isLoggedIn=='1'){
                            response = that.updateToken(token,reactroStore.status);
                        }else if(reactroStore.status =='auth' && isLoggedIn=='0'){
                            response = that.updateToken(token,'guest');
                        }
                    }else{
                        response = that.updateToken(token,'none');
                    }
                    var ret=true;
                    if(Object.keys(response).length > 0){
                    response.then(function(resp){
                        if(resp.success){
                            ret =true;
                            window.localStorage.setItem('reactroStore', JSON.stringify(resp.data));
                        }else{
                            ret =false;
                            console.log(resp.data.message)
                        }
                    })
                    }
                    return ret;
                })
                // Permission denied
                .catch(function(err) { // Happen if user deney permission
                    console.log('Unable to get permission to notify.', err);
                });
            });
        },
        updateToken : function(token,status) {
            var that = this;
             return $.post(
                 that.settings.ajax_url,{'token':token,'action':'push_update','status':status,'nonce':that.settings.nonce});
        },
        //////////////////////
        ///Helper Functions///
        //////////////////////
        isEmpty: function(obj) {

            if (obj == null)        return true;
            if (obj.length > 0)     return false;
            if (obj.length === 0)   return true;

            for (var key in obj) {
                if (hasOwnProperty.call(obj, key)) return false;
            }

            return true;
        },
        sprintf: function parse(str) {
            var args = [].slice.call(arguments, 1),
                i = 0;

            return str.replace(/%s/g, function() {
                return args[i++];

            });
        },
        getCookie: function(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) === 0) return c.substring(name.length, c.length);
            }
            return "";
        },
        createCookie: function(name, value, minutes) {
            var expires = "";

            if (minutes) {
                var date = new Date();
                date.setTime(date.getTime()+(minutes * 60 * 1000));
                var expires = "; expires="+date.toGMTString();
            }

            document.cookie = name + "=" + value+expires + "; path=/";
        },
        deleteCookie: function(name) {
            this.createCookie(name, '', -10);
        }
    } );

    // Constructor wrapper
    $.fn[ pluginName ] = function( options ) {
        return this.each( function() {
            if ( !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" +
                    pluginName, new Plugin( this, options ) );
            }
        } );
    };

    $(document).ready(function() {

        $( "body" ).wordpress_reactro( 
            wordpress_reactro_options
        );

    } );

})( jQuery );