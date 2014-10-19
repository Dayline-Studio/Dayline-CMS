var myModuleManager;

var lowRecources = false;

$(document).ready(function () {
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

        if ($('#socialshareprivacy').length > 0) {
            $(this).socialSharePrivacy({
                "css_path": config.path.fw_js+"socialshareprivacy.css",
                "lang_path": config.path.fw_js+"lang/",
                "language": "de"
            });
        }

        ga('create', config.settings.google_analytics, config.settings.domain);
        ga('send', 'pageview');

    });
});