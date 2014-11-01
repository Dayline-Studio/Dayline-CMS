function MainModule(id){

    var that = {};

    that.id = id;
    that.jqID = '#dMod_' + id;

    that.ajax_handler = '/ajax_handler.php';

    that.init = function () {
        that.clickBar();
        that.loadSettings();

        if (that.additionalInit != undefined) {
            that.additionalInit();
        }

        if (that.loadPreview != undefined) {
            that.loadPreview();
        }
    };

    that.loadPreview = function() {};

    that.additionalInit = function() {};

    that.initTooltip = function () {
        $(that.jqID).find('.tooltip-init').hover(
            function () {
                $(this).tooltip('show');
            }, function () {
                $(this).tooltip('destroy');
            });
        $(that.jqID).find('.tooltip-init').css('cursor', 'pointer');
    };

    that.clickBar = function () {

        that.initTooltip();

        that.getModulePart('edit').on('click', function () {
            that.toggleEdit();
        });

        that.getModulePart('delete').on('click', function () {
            if (confirm('Willst Du wirklich löschen?')) {
                that.delete();
            }
        });

        that.getModulePart('move_up').on('click', function () {
            that.move('up');
        });

        that.getModulePart('move_down').on('click', function () {
            that.move('down');
        });

        that.getModulePart('reload').on('click', function () {
            that.reloadPreSet('reload', function(result) {
                console.log(result);
                $(that.jqID).html(result.full);
            });
        });
    };

    that.move = function (direction) {
        $.ajax({
            url: that.ajax_handler,
            type: 'POST',
            data: [
                {name: 'action', value: 'move_' + direction},
                {name: "id", value: that.id}
            ],
            success: function (data) {
                var result = JSON.parse(data);
                if (result.error != undefined) {
                    alert(result.error);
                } else {
                    $(that.jqID).remove();
                    var replace = '';
                    if (direction == 'up') {
                        replace = result.this_content + result.target_content;
                    } else if (direction == 'down') {
                        replace = result.target_content + result.this_content;
                    }
                    var targetModule = myModuleManager.getModuleById(result.target_id);
                    $(targetModule.jqID).replaceWith(replace);
                    targetModule.init();
                    that.init();
                }
            }
        });
    };

    that.getModulePart = function (name, first) {
        if (first == undefined) first = true;
        if (first) {
            return $(that.jqID).find('.module_' + name + ':first');
        } else {
            return $(that.jqID).find('.module_' + name);
        }
    };

    that.openSettings = function () {
        that.getModulePart('settings').slideDown('fast');
        that.getModulePart('preview').slideUp('fast');
    };

    that.openPreview = function () {
        that.getModulePart('settings').slideUp('fast');
        that.getModulePart('preview').slideDown('fast');
    };

    that.toggleEdit = function () {
        if (that.getModulePart('settings').is(':visible')) {
            that.openPreview();
        } else {
            that.openSettings();
        }
    };

    that.loadSettings = function () {
        $(that.jqID).find('.module_task').on('submit', function (e) {
            if ($(this).data('id') == that.id) {
                var send = $(this).serializeArray();
                that.reloadPreSet('get_post_result_render', function (result) {
                    console.log(result);
                    if (result.isNote != undefined) {
                        $.bootstrapGrowl(result.NoteContent, {
                            type: result.NoteKind
                        });
                    } else {
                        that.getModulePart('preview').html(result.prev);
                        that.loadSettings();
                        that.clickBar();
                    }
                }, send);
            }
            e.preventDefault();
        });

        that.getModulePart('apply').on('submit' ,function (e) {
            var action = 'update';
            var requestData = $(this).serializeArray();
            requestData.push({name: "action", value: action});
            requestData.push({name: "id", value: that.id});

            $.ajax({
                url: that.ajax_handler,
                type: "POST",
                data: requestData,
                success: function (data) {
                    $.bootstrapGrowl("Change Successful", {
                        type: 'success'
                    });
                    that.reloadPreview();
                }
            });
            e.preventDefault();
        });
    };

    that.reloadPreSet = function (action, func, add_post) {
        var send = [
            {name: 'action', value: action},
            {name: "id", value: that.id}
        ];
        if (add_post != undefined) {
            send = $.merge(send, add_post);
        }

        $.ajax({
            url: that.ajax_handler,
            type: 'POST',
            data: send,
            success: function (data) {
                console.log(data);
                try {
                    var result = JSON.parse(data);
                    func(result);
                } catch(e) {
                    console.log('Parse Error:');
                    console.log(data);
                }
            }
        });
    };

    that.reloadPreview = function () {
        that.reloadPreSet('reload_module', function (result) {
            that.getModulePart('preview').html(result.prev);
            that.loadPreview();
        });
    };

    that.reloadSettings = function () {
        that.reloadPreSet('reload_module', function (result) {
            that.getModulePart('settings').html(result.set);
        });
    };

    that.delete = function () {
        $(that.jqID).slideUp('slow', function () {
            $(this).remove();
            console.log(that.id);
            $.ajax({
                url: '/ajax_handler',
                type: 'POST',
                data: [
                    {name: 'action', value: 'delete'},
                    {name: "id", value: that.id}
                ],
                success: function (data) {
                    $.bootstrapGrowl("Modul deleted", {
                        type: 'danger'
                    });
                }
            });
        });
    };

    return that;
};