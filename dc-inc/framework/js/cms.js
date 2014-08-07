$(document).ready(function () {
    load_create_function();
    init_tinymce();
    set_menu_from_cookie();
    cookie_mail();
    init_module(1, 1, 1, 1);
});

function init_tooltip() {
    $('.tooltip-init').hover(
        function () {
            $(this).tooltip('show');
        }, function () {
            $(this).tooltip('destroy');
        });
    $('.tooltip-init').css('cursor', 'pointer');
}

function toggle_content(id, save) {
    var mainId = id;
    id = '.' + id;
    var zustand = $(id).css('display');
    console.log('toggle: ' + id);
    $(id).slideToggle("fast");

    if (save) {
        if (zustand == 'block') {
            document.cookie = mainId + "=1";
        } else {
            document.cookie = mainId + "=0";
        }
    }
}
var url_edit = $(location).attr('href') + '&do=swap_prev_mode';
document.onkeydown = function (event) {
    if (event.keyCode == 117) {
        window.location.assign(url_edit);
    }
}

function toggle_content_instand(id) {
    id = '.' + id;
    $(id).toggle();
}

function admin_toggler() {
    var divs = $('div[class^="slide_set"]');
    for (var i = 0; i < divs.length; i++) {
        $(divs[i]).slideUp(0);
    }
    var divs = $('div[class^="slide_prev"]');
    for (var i = 0; i < divs.length; i++) {
        $(divs[i]).slideDown(0);
    }
}

function toggle_admin_slide(id) {
    sGet(id, 'set').slideToggle('slow');
    sGet(id, 'prev').slideToggle('slow');
}

function sGet(id, type) {
    return $('#' + id).find('.slide_' + type);
}

function setModulLoading(id) {
    sGet(id, 'set').slideUp(0);
    sGet(id, 'prev').slideDown(0);
    $.get('../../dc-inc/images/ajax/ajax.html')
        .success(function (data) {
            sGet(id, 'prev').html(data);
        });
}

function renderModule(id, modul) {
    sGet(id, 'prev').html(modul);
}

function cMN(id) {
    return 'mod_' + id;
}

function reload_filemanager(id) {
    reload_module(id, 'reload_module', function (id, result) {
        sGet('mod_' + id, 'set').html(result.set);
        init_module(0, 0, 1, 1);
    });
    toggle_admin_slide(id);
}

function s_module_task(id) {
    var task = $('#' + cMN(id)).data('task');
    reload_module(id, task, function (id, result) {
        $.bootstrapGrowl(result.msg, {
            type: 'info'
        });
    });
}

function s_reload_module(id) {
    reload_module(id, 'reload_module', function (id, result) {
        sGet('mod_' + id, 'prev').html(result.prev);
        init_module(0, 0, 1, 1);
    });
}

function reload_module(id, action, func, add_post) {
    var send = [
        {name: 'action', value: action},
        {name: "id", value: id}
    ];
    if (add_post != undefined) {
        send = $.merge(send, add_post);
    }
    $.ajax({
        url: '/ajax_handler',
        type: 'POST',
        data: send,
        success: function (data) {
            console.log(data);
            var result = JSON.parse(data);
            if (result.error != undefined) {
                alert(result.error);
            } else {
                func(id, result);
            }
        }
    });
    return false;
}


/*   var ModuleCall = function (id) {

 this.id = id;
 this.url = 'ajax_handler.php';
 this.method = 'POST'
 this.params = {};

 this.SendData = function () {
 $.ajax(
 {
 url: this.url,
 type: this.method,
 data: this.params,
 success: function (data) {
 this.onSuccess();
 }
 });
 return false;
 }

 this.onSuccess = function () {
 };

 this.getPostParams = function () {
 $(this).serializeArray();
 }
 }

 var Module = function (id) {

 this.id = id;

 }*/


function load_form_function() {
    $('form[class^="s_call"]').submit(function () {
        var send = $(this).serializeArray();
        reload_module(id, 'send_task', function (id, result) {
            alert(result.msg);
        })
        return false;
    });

    $('form[class^="task"]').submit(function () {
        // setModulLoading(cMN($(this).data('id')));
        var send = $(this).serializeArray();
        reload_module($(this).data('id'), 'get_post_result_render', function (id, result) {
            sGet('mod_' + id, 'prev').html(result.prev);
            init_module(0, 0, 1, 1);
        }, send);
        return false;
    });

    $('form[id^="mod_"]').submit(function () {
        var module_id = splModName($(this).attr('id'))[1];
        var action = 'update';
        var div = cMN(module_id);
        // setModulLoading(div);
        var postData = $(this).serializeArray();
        postData[5] = {name: "action", value: action};
        postData[6] = {name: "id", value: module_id};
        if ($(this).data('tiny')) {
            postData[8] = {name: "content", value: tinyMCE.get(postData[0]["name"])['bodyElement']['innerHTML']};
        }
        var formURL = $(this).attr("action");
        $.ajax(
            {
                url: formURL,
                type: "POST",
                data: postData,
                success: function (data) {
                    console.log(data);
                    $.bootstrapGrowl("Change Successful", {
                        type: 'success'
                    });
                    renderModule(div, data);
                    init_module(0, 1, 1, 0);
                }
            });
        return false;
    })
}

function move(dir, div_id) {
    var id = splModName(div_id)[1];
    $.ajax({
        url: '/ajax_handler',
        type: 'POST',
        data: [
            {name: 'action', value: 'move_' + dir},
            {name: "id", value: id}
        ],
        success: function (data) {
            var result = JSON.parse(data);
            if (result.error != undefined) {
                alert(result.error);
            } else {
                $('#mod_' + result.this_id).remove();
                var replace = '';
                if (dir == 'up') {
                    replace = result.this_content + result.target_content;
                } else {
                    replace = result.target_content + result.this_content;
                }
                $('#mod_' + result.target_id).replaceWith(replace);
                init_tinymce();
                init_module(1, 1, 1, 1);
            }
        }
    })
    return false;
}

function init_module(admin_toggle, tooltip, colorbox, form) {
    if (admin_toggle) admin_toggler();
    if (tooltip) init_tooltip();
    if (colorbox) init_colorbox();
    if (form) load_form_function();
}

function init_colorbox() {
    $('a[class^="imagebox_"]').colorbox({rel: 'imagebox', transition: "fade", width: "75%", height: "75%"});
}

function load_create_function() {
    $('.create_mod').submit(function () {
        var action = 'create';
        var postData = $(this).serializeArray();
        var position = $(this).closest("[id^='position_']").attr('id');
        postData[10] = {name: "action", value: 'create'};
        postData[11] = {name: "position", value: position}
        $.ajax(
            {
                url: '/ajax_handler',
                type: "POST",
                data: postData,
                success: function (data) {
                    console.log(data);
                    $('#' + position).children('a').before(data);
                    init_tinymce();
                    $.bootstrapGrowl("Modul added");
                    init_module(1, 1, 1, 1);
                }
            });
        return false;
    });
}


function delete_module(div_id) {
    if (confirm('Willst Du wirklich löschen?')) {
        $('#' + div_id).slideUp('slow');
        var id = splModName(div_id)[2];
        var name = splModName(div_id)[1];
        $.ajax({
            url: '/ajax_handler',
            type: 'POST',
            data: [
                {name: 'action', value: 'delete'},
                {name: "id", value: id},
                {name: 'module', value: name}
            ],
            success: function (data) {
                init_tinymce();
                if (data == '1') $('#' + div_id).remove();
                $.bootstrapGrowl("Modul deleted", {
                    type: 'danger'
                });
            }
        })
        return false;
    }
}

function splModName(id) {
    var ex = id.split('_');
    return ex;
}

function set_menu_from_cookie() {
    var divs = get_toggle_cookie('open_');
    for (var i = 0; i < divs.length; i++) {
        var split = divs[i].split('=')
        var value = split[1];
        var tag = split[0];
        console.log('found ' + tag + ' with ' + value);
        if (value == 1) {
            console.log('close ' + tag);
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

//----------------------------------------------------------------------------------------

function init_tinymce() {
    tinymce.init({
        selector: "div.edit",
        inline: true,

        plugins: [
            "advlist textcolor autolink youtube lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table responsivefilemanager contextmenu paste"
        ],
        toolbar: "insertfile undo redo | styleselect | youtube | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        external_filemanager_path: "/dc-content/plugins/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: { "filemanager": "../filemanager/plugin.min.js"},
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

$(function () {
    $('a[href*=#]:not([href=#])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });
});

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

(function ($) {

    var rotateLeft = function (lValue, iShiftBits) {
        return (lValue << iShiftBits) | (lValue >>> (32 - iShiftBits));
    }

    var addUnsigned = function (lX, lY) {
        var lX4, lY4, lX8, lY8, lResult;
        lX8 = (lX & 0x80000000);
        lY8 = (lY & 0x80000000);
        lX4 = (lX & 0x40000000);
        lY4 = (lY & 0x40000000);
        lResult = (lX & 0x3FFFFFFF) + (lY & 0x3FFFFFFF);
        if (lX4 & lY4) return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
        if (lX4 | lY4) {
            if (lResult & 0x40000000) return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
            else return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
        } else {
            return (lResult ^ lX8 ^ lY8);
        }
    }

    var F = function (x, y, z) {
        return (x & y) | ((~x) & z);
    }

    var G = function (x, y, z) {
        return (x & z) | (y & (~z));
    }

    var H = function (x, y, z) {
        return (x ^ y ^ z);
    }

    var I = function (x, y, z) {
        return (y ^ (x | (~z)));
    }

    var FF = function (a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(F(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var GG = function (a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(G(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var HH = function (a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(H(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var II = function (a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(I(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var convertToWordArray = function (string) {
        var lWordCount;
        var lMessageLength = string.length;
        var lNumberOfWordsTempOne = lMessageLength + 8;
        var lNumberOfWordsTempTwo = (lNumberOfWordsTempOne - (lNumberOfWordsTempOne % 64)) / 64;
        var lNumberOfWords = (lNumberOfWordsTempTwo + 1) * 16;
        var lWordArray = Array(lNumberOfWords - 1);
        var lBytePosition = 0;
        var lByteCount = 0;
        while (lByteCount < lMessageLength) {
            lWordCount = (lByteCount - (lByteCount % 4)) / 4;
            lBytePosition = (lByteCount % 4) * 8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount) << lBytePosition));
            lByteCount++;
        }
        lWordCount = (lByteCount - (lByteCount % 4)) / 4;
        lBytePosition = (lByteCount % 4) * 8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80 << lBytePosition);
        lWordArray[lNumberOfWords - 2] = lMessageLength << 3;
        lWordArray[lNumberOfWords - 1] = lMessageLength >>> 29;
        return lWordArray;
    };

    var wordToHex = function (lValue) {
        var WordToHexValue = "", WordToHexValueTemp = "", lByte, lCount;
        for (lCount = 0; lCount <= 3; lCount++) {
            lByte = (lValue >>> (lCount * 8)) & 255;
            WordToHexValueTemp = "0" + lByte.toString(16);
            WordToHexValue = WordToHexValue + WordToHexValueTemp.substr(WordToHexValueTemp.length - 2, 2);
        }
        return WordToHexValue;
    };

    var uTF8Encode = function (string) {
        string = string.replace(/\x0d\x0a/g, "\x0a");
        var output = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                output += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                output += String.fromCharCode((c >> 6) | 192);
                output += String.fromCharCode((c & 63) | 128);
            } else {
                output += String.fromCharCode((c >> 12) | 224);
                output += String.fromCharCode(((c >> 6) & 63) | 128);
                output += String.fromCharCode((c & 63) | 128);
            }
        }
        return output;
    };

    $.extend({
        md5: function (string) {
            var x = Array();
            var k, AA, BB, CC, DD, a, b, c, d;
            var S11 = 7, S12 = 12, S13 = 17, S14 = 22;
            var S21 = 5, S22 = 9 , S23 = 14, S24 = 20;
            var S31 = 4, S32 = 11, S33 = 16, S34 = 23;
            var S41 = 6, S42 = 10, S43 = 15, S44 = 21;
            string = uTF8Encode(string);
            x = convertToWordArray(string);
            a = 0x67452301;
            b = 0xEFCDAB89;
            c = 0x98BADCFE;
            d = 0x10325476;
            for (k = 0; k < x.length; k += 16) {
                AA = a;
                BB = b;
                CC = c;
                DD = d;
                a = FF(a, b, c, d, x[k + 0], S11, 0xD76AA478);
                d = FF(d, a, b, c, x[k + 1], S12, 0xE8C7B756);
                c = FF(c, d, a, b, x[k + 2], S13, 0x242070DB);
                b = FF(b, c, d, a, x[k + 3], S14, 0xC1BDCEEE);
                a = FF(a, b, c, d, x[k + 4], S11, 0xF57C0FAF);
                d = FF(d, a, b, c, x[k + 5], S12, 0x4787C62A);
                c = FF(c, d, a, b, x[k + 6], S13, 0xA8304613);
                b = FF(b, c, d, a, x[k + 7], S14, 0xFD469501);
                a = FF(a, b, c, d, x[k + 8], S11, 0x698098D8);
                d = FF(d, a, b, c, x[k + 9], S12, 0x8B44F7AF);
                c = FF(c, d, a, b, x[k + 10], S13, 0xFFFF5BB1);
                b = FF(b, c, d, a, x[k + 11], S14, 0x895CD7BE);
                a = FF(a, b, c, d, x[k + 12], S11, 0x6B901122);
                d = FF(d, a, b, c, x[k + 13], S12, 0xFD987193);
                c = FF(c, d, a, b, x[k + 14], S13, 0xA679438E);
                b = FF(b, c, d, a, x[k + 15], S14, 0x49B40821);
                a = GG(a, b, c, d, x[k + 1], S21, 0xF61E2562);
                d = GG(d, a, b, c, x[k + 6], S22, 0xC040B340);
                c = GG(c, d, a, b, x[k + 11], S23, 0x265E5A51);
                b = GG(b, c, d, a, x[k + 0], S24, 0xE9B6C7AA);
                a = GG(a, b, c, d, x[k + 5], S21, 0xD62F105D);
                d = GG(d, a, b, c, x[k + 10], S22, 0x2441453);
                c = GG(c, d, a, b, x[k + 15], S23, 0xD8A1E681);
                b = GG(b, c, d, a, x[k + 4], S24, 0xE7D3FBC8);
                a = GG(a, b, c, d, x[k + 9], S21, 0x21E1CDE6);
                d = GG(d, a, b, c, x[k + 14], S22, 0xC33707D6);
                c = GG(c, d, a, b, x[k + 3], S23, 0xF4D50D87);
                b = GG(b, c, d, a, x[k + 8], S24, 0x455A14ED);
                a = GG(a, b, c, d, x[k + 13], S21, 0xA9E3E905);
                d = GG(d, a, b, c, x[k + 2], S22, 0xFCEFA3F8);
                c = GG(c, d, a, b, x[k + 7], S23, 0x676F02D9);
                b = GG(b, c, d, a, x[k + 12], S24, 0x8D2A4C8A);
                a = HH(a, b, c, d, x[k + 5], S31, 0xFFFA3942);
                d = HH(d, a, b, c, x[k + 8], S32, 0x8771F681);
                c = HH(c, d, a, b, x[k + 11], S33, 0x6D9D6122);
                b = HH(b, c, d, a, x[k + 14], S34, 0xFDE5380C);
                a = HH(a, b, c, d, x[k + 1], S31, 0xA4BEEA44);
                d = HH(d, a, b, c, x[k + 4], S32, 0x4BDECFA9);
                c = HH(c, d, a, b, x[k + 7], S33, 0xF6BB4B60);
                b = HH(b, c, d, a, x[k + 10], S34, 0xBEBFBC70);
                a = HH(a, b, c, d, x[k + 13], S31, 0x289B7EC6);
                d = HH(d, a, b, c, x[k + 0], S32, 0xEAA127FA);
                c = HH(c, d, a, b, x[k + 3], S33, 0xD4EF3085);
                b = HH(b, c, d, a, x[k + 6], S34, 0x4881D05);
                a = HH(a, b, c, d, x[k + 9], S31, 0xD9D4D039);
                d = HH(d, a, b, c, x[k + 12], S32, 0xE6DB99E5);
                c = HH(c, d, a, b, x[k + 15], S33, 0x1FA27CF8);
                b = HH(b, c, d, a, x[k + 2], S34, 0xC4AC5665);
                a = II(a, b, c, d, x[k + 0], S41, 0xF4292244);
                d = II(d, a, b, c, x[k + 7], S42, 0x432AFF97);
                c = II(c, d, a, b, x[k + 14], S43, 0xAB9423A7);
                b = II(b, c, d, a, x[k + 5], S44, 0xFC93A039);
                a = II(a, b, c, d, x[k + 12], S41, 0x655B59C3);
                d = II(d, a, b, c, x[k + 3], S42, 0x8F0CCC92);
                c = II(c, d, a, b, x[k + 10], S43, 0xFFEFF47D);
                b = II(b, c, d, a, x[k + 1], S44, 0x85845DD1);
                a = II(a, b, c, d, x[k + 8], S41, 0x6FA87E4F);
                d = II(d, a, b, c, x[k + 15], S42, 0xFE2CE6E0);
                c = II(c, d, a, b, x[k + 6], S43, 0xA3014314);
                b = II(b, c, d, a, x[k + 13], S44, 0x4E0811A1);
                a = II(a, b, c, d, x[k + 4], S41, 0xF7537E82);
                d = II(d, a, b, c, x[k + 11], S42, 0xBD3AF235);
                c = II(c, d, a, b, x[k + 2], S43, 0x2AD7D2BB);
                b = II(b, c, d, a, x[k + 9], S44, 0xEB86D391);
                a = addUnsigned(a, AA);
                b = addUnsigned(b, BB);
                c = addUnsigned(c, CC);
                d = addUnsigned(d, DD);
            }
            var tempValue = wordToHex(a) + wordToHex(b) + wordToHex(c) + wordToHex(d);
            return tempValue.toLowerCase();
        }
    });
})(jQuery);