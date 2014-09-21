function Gallery(id) {

    var that = MainModule(id);

    that.loadPreview = function() {
        that.initColorbox();
    };

    that.initColorbox = function() {
        $('a[class^="imagebox_"]').colorbox({rel: 'imagebox', transition: "fade", width: "75%", height: "75%"});
    };

    that.init();

    return that;
}