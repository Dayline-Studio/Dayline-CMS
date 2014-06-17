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
    if ($('#admin_room').length) {
        $( '#admin_room').toggle();
    }
}

function toggle_admin_room() {
    $( '#admin_room' ).slideToggle( "slow" );
    $( '#user_room' ).slideToggle( "slow" );
}

$(document).ready(function() {
    admin_toggler();
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
});

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

tinymce.init({
    selector: "h1.edit",
    inline: true,
    toolbar: "undo redo",
    menubar: false
});

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