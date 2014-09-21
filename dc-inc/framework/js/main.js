var myModuleManager;
$(document).ready(function () {
    init_tinymce();
    set_menu_from_cookie();
    cookie_mail();
    init_tooltip();
    myModuleManager = ModuleManager();
});
