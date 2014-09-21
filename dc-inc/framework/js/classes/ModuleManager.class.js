function ModuleManager() {

    var that = {};

    that.modules = [];

    that.init = function () {
        that.readModules();
    };

    that.readModules = function () {
        $('.dMod').each(function () {
            that.modules.push(window[$(this).data('class')]($(this).data('id')));
        });
    };

    $('.create_mod').submit(function (e) {
        var action = 'create';
        var postRequest = $(this).serializeArray();
        var position = $(this).closest("[id^='position_']").attr('id');
        postRequest.push({name: "action", value: 'create'});
        postRequest.push({name: "position", value: position});
        $.ajax({
            url: '/ajax_handler.php',
            type: "POST",
            data: postRequest,
            success: function (data) {
                data = JSON.parse(data);
                $('#' + position).children('a').before(data.full);
                $.bootstrapGrowl("Modul added");
                that.modules.push(window[data.javaClass](data.id));
            }
        });
        e.preventDefault();
    });

    that.getModuleById = function (queryID) {
        for (var i = 0; i < that.modules.length; i++) {
            if (that.modules[i].id == queryID) {
                return that.modules[i];
            }
        }
    };

    that.init();

    return that;
}