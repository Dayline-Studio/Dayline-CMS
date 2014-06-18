function toggle_content (id, save) {
    var mainId = id;
    id = '.'+id;
    var zustand = $(id).css('display');
    console.log('toggle: '+id);
    $( id ).slideToggle( "fast" );

    if (save) {
        if (zustand == 'block') {
            document.cookie=mainId+"=1";
        } else {
            document.cookie=mainId+"=0";
        }
    }
}

function toggle_content_instand (id) {
    id = '.'+id;
    $( id ).toggle();
}

function admin_toggler() {
    var divs = $('div[id^="slide_admin_"]');
    for (var i = 0; i<divs.length; i++) {
        $(divs[i]).toggle();
    }
}

function toggle_admin_slide(div, id) {
    $('#slide_admin_'+div+'_'+id ).slideToggle( "slow" );
    $('#slide_user_'+div+'_'+id ).slideToggle( "slow" );
}

function set_modul_loading(div,id) {
    $('#slide_admin_'+div+'_'+id ).slideUp( "slow" );
    $('#slide_user_'+div+'_'+id ).slideDown( "slow" );
    $('#slide_user_'+div+'_'+id).html('Loading ...');
}

function reload_module(div,id,modul) {
    $('#slide_user_'+div+'_'+id).html(modul);
    $('#slide_admin_'+div+'_'+id).toggle();
}

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
        external_filemanager_path:"../content/plugins/filemanager/",
        filemanager_title:"Responsive Filemanager" ,
        external_plugins: { "filemanager" : "../filemanager/plugin.min.js"}
    });
}

function load_form_function() {
    $('form[id^="mod_"]').submit(function()
    {
        var module_id = $(this).data('id');
        var module_name = $(this).data('module');
        set_modul_loading(module_name,module_id);
        var postData = $(this).serializeArray();
        postData[1] = {name:"id", value:module_id};
        postData[2] = {name:"module", value:module_name};
        postData[0] = {name:"content", value:tinyMCE.get(postData[0]["name"])['bodyElement']['innerHTML']};
        var formURL = $(this).attr("action");
        $.ajax(
            {
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data, textStatus)
                {
                    reload_module(module_name,module_id,data);
                    init_tinymce();
                }
            });
        return false;
    });
}

$(document).ready(function() {
    load_form_function();
    init_tinymce();
    admin_toggler();
    set_menu_from_cookie();
});

function set_menu_from_cookie() {
    var divs = get_toggle_cookie('open_');
    for(var i= 0; i<divs.length;i++){
        var split = divs[i].split('=')
        var value = split[1];
        var tag = split[0];
        console.log('found '+tag+' with '+value);
        if (value == 1) {
            console.log('close '+tag);
            toggle_content_instand(tag);
        }
    }
}

function get_toggle_cookie(tag) {
    var ca = document.cookie.split(';');
    var tagLngth = tag.length;
    var arr = [];
    var count = 0;
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.substr(0,tagLngth) == tag){
           arr[count] = c;
           count++;
        }
    }
    return arr;
}

$(function() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});

(function(i,s,o,g,r,a,m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function(){ 
        (i[r].q = i[r].q || []).push(arguments)
    },
    i[r].l = 1*new Date();
    a = s.createElement(o), m = s.getElementsByTagName(o)[0];
    a.async=1;
    a.src=g;
    m.parentNode.insertBefore(a,m)
})
(window,document,'script','//www.google-analytics.com/analytics.js','ga');

