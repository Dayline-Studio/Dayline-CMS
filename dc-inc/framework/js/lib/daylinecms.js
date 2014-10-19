function init_tooltip() {
    $('.add-module.tooltip-init').hover(
        function () {
            $(this).tooltip('show');
        }, function () {
            $(this).tooltip('destroy');
        });
    $('.add-module.tooltip-init').css('cursor', 'pointer');
}

function toggle_content(id, save) {
    var mainId = id;
    id = '.' + id;
    var zustand = $(id).css('display');
    $(id).slideToggle("fast");

    if (save) {
        if (zustand == 'block') {
            document.cookie = mainId + "=1";
        } else {
            document.cookie = mainId + "=0";
        }
    }
}

function toggle_content_instand(id) {
    id = '.' + id;
    $(id).toggle();
}

function set_menu_from_cookie() {
    var divs = get_toggle_cookie('open_');
    for (var i = 0; i < divs.length; i++) {
        var split = divs[i].split('=');
        var value = split[1];
        var tag = split[0];
        if (value == 1) {
            toggle_content_instand(tag);
        }
    }
}

function get_toggle_cookie(tag) {
    var ca = document.cookie.split(';');
    var tagLngth = tag.length;
    var arr = [];
    var count = 0;
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i].trim();
        if (c.substr(0, tagLngth) == tag) {
            arr[count] = c;
            count++;
        }
    }
    return arr;
}

function get_cookie(tag) {
    var ca = document.cookie.split(';');
    var tagLngth = tag.length;
    var str = false;
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i].trim();
        if (c.substr(0, tagLngth) == tag) {
            str = c;
            break;
        }
    }
    var arr = false;
    if (str) {
        arr = str.split('=');
        arr = decodeURIComponent(arr[1]);
    }
    return arr;

}

var ca = '#mail_case';
var save = '';

function cookie_mail() {
    var email = get_cookie('email');

    if (email) {
        save = $(ca).html();
        $(ca).find('#InputEmail1').remove();
        $(ca).append('<table class="table table-hover"><tr class="info"><td><img src="https://www.gravatar.com/avatar/' + $.md5(email) + '?s=20&d=wavatar&r=g" /></td><td>' + email + '</td><td><span onclick="email_field_reset()" class="glyphicon glyphicon glyphicon-remove-circle"></span></td></tr></table>');
        $('<input>').attr({
            type: 'hidden',
            id: 'InputEmail1',
            name: 'username',
            value: email
        }).appendTo(ca);
    }
}

function email_field_reset() {
    delete_cookie('email');
    $(ca).html(save);
}

function add_cookie(name, value) {
    document.cookie = name + '=' + value;
}

function delete_cookie(name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function init_tinymce() {
    tinymce.init({
        selector: "div.edit",
        inline: true,
        skin: "dayline",

        plugins: [
            "advlist textcolor autolink youtube lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table responsivefilemanager contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | youtube | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        external_filemanager_path: "/dc-content/plugins/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: {"filemanager": "../filemanager/plugin.min.js"},
        style_formats_merge: true,
        style_formats: [
            {
                title: 'ImageBox',
                selector: 'a',
                classes: 'imagebox'
            }
        ]
    });
}


(function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function () {
        (i[r].q = i[r].q || []).push(arguments)
    },
        i[r].l = 1 * new Date();
    a = s.createElement(o), m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
})
(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

