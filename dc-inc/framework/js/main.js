var myModuleManager;

var lowRecources = false;

$(document).ready(function () {

    setInterval(function() {
        $.ajax('/ajax_handler.php?refresh=true');
    }, 200000);

    $.getJSON('/getJson.php?r=pageInformations', function(response) {
        var config = response;

        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            lowRecources = true;
        }

        init_tinymce();
        set_menu_from_cookie();
        cookie_mail();
        init_tooltip();
        myModuleManager = ModuleManager();

        ga('create', config.settings.google_analytics, config.settings.domain);
        ga('send', 'pageview');

    });
});