function EditableText(id) {

    var that = MainModule(id);

    that.loadSettings = function () {
        init_tinymce();
        that.getModulePart('apply').submit(function (e) {
            var action = 'update';
            var requestData = $(this).serializeArray();
            requestData.push({name: "action", value: action});
            requestData.push({name: "id", value: that.id});
            requestData[requestData.length] =
            {
                name: "content",
                value: tinyMCE.get(requestData[0]["name"])['bodyElement']['innerHTML'].replace(/(data-mce-(.+?)="(.+?)")|(mce-(.[a-z]*))|(id="mce(.+?"))/g, '')
            };

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

    that.init();

    return that;
}