$(document).ready(function() {
    load_create_function();
    load_form_function();
    init_tinymce();
    admin_toggler();
    set_menu_from_cookie();
});

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
    var divs = $('div[class^="slide_set"]');
    for (var i = 0; i<divs.length; i++) {
        $(divs[i]).toggle();
    }
}

function toggle_admin_slide(id) {
    sGet(id,'set').slideToggle('slow');
    sGet(id,'prev').slideToggle('slow');
}

function sGet(id, type) {
    return $('#'+id).find('.slide_'+type);
}

function setModulLoading(id) {
    sGet(id,'set').slideUp( "slow" );
    sGet(id,'prev').slideDown( "slow" );
    sGet(id,'prev').html('Loading ...');
}

function renderModule(id,modul) {
    sGet(id,'prev').html(modul);
    sGet(id,'set').toggle();
}

function cMN(id) {
    return 'mod_'+id;
}

function load_form_function() {
    $('form[id^="mod_"]').submit(function()
    {
        var module_id = $(this).data('id');
        var module_name = $(this).data('module');
        var action = 'update';
        var div = cMN(module_id);
        setModulLoading(div);

        var postData = $(this).serializeArray();
        postData[5] = {name:"action", value:action};
        postData[6] = {name:"id", value:module_id};
        postData[7] = {name:"module", value:module_name};
        postData[8] = {name:"content", value:tinyMCE.get(postData[0]["name"])['bodyElement']['innerHTML']};
        var formURL = $(this).attr("action");
        $.ajax(
            {
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data)
                {
                    renderModule(div,data);
                    init_tinymce();
                }
            });
        return false;
    });
}

function load_create_function() {
    $('#create_mod').submit(function()
    {
        var action = 'create';
        var postData = $(this).serializeArray();
        postData[10] = {name:"action", value:'create'};
        var formURL = $(this).attr("action");
        $.ajax(
            {
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data)
                {
                    $('#modules').append(data);
                    load_form_function();
                    init_tinymce();
                }
            });
        return false;
    });
}


function delete_module(div_id) {
    if (confirm('Willst Du wirklich löschen?'))
    {
        var id = splModName(div_id)[2];
        var name = splModName(div_id)[1];
        $.ajax({
            url: 'ajax_handler.php',
            type: 'POST',
            data: [{name:'action', value:'delete'},{name:"id", value:id}, {name:'module', value:name}],
            success:function(data)
            {
                init_tinymce();
                if (data == '1') $('#'+div_id).remove();
            }
        })
    }
}

function splModName(id) {
    var ex = id.split('_');
    return ex;
}

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
        external_filemanager_path:"../content/plugins/filemanager/",
        filemanager_title:"Responsive Filemanager" ,
        external_plugins: { "filemanager" : "../filemanager/plugin.min.js"}
    });
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

