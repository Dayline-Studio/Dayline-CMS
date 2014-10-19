jQuery.QapTcha = {build: function (e) {
    var t = {txtLock: "Locked : form can't be submited", txtUnlock: "Unlocked : form can be submited", disabledSubmit: true, autoRevert: true, PHPfile: "../../../dc-inc/captcha_handler.php", autoSubmit: false};
    if (this.length > 0)return jQuery(this).each(function (n) {
        function c(e) {
            var t = "azertyupqsdfghjkmwxcvbn23456789AZERTYUPQSDFGHJKMWXCVBN_-#@";
            var r = "";
            for (n = 0; n < e; n++) {
                var i = Math.round(Math.random() * t.length);
                r += t.substring(i, i + 1)
            }
            return r
        }

        var r = $.extend(t, e), i = $(this), s = $("form").has(i), o = jQuery("<div>", {"class": "clr"}), u = jQuery("<div>", {"class": "bgSlider"}), a = jQuery("<div>", {"class": "Slider"}), f = jQuery("<div>", {"class": " TxtStatus dropError", text: r.txtLock}), l = jQuery("<input>", {name: c(32), value: c(7), type: "hidden"});
        if (r.disabledSubmit)s.find("input[type='submit']").attr("disabled", "disabled");
        u.appendTo(i);
        o.insertAfter(u);
        f.insertAfter(o);
        l.appendTo(i);
        a.appendTo(u);
        i.show();
        a.draggable({revert: function () {
            if (r.autoRevert) {
                if (parseInt(a.css("left")) > u.width() - a.width() - 10)return false; else return true
            }
        }, containment: u, axis: "x", stop: function (e, t) {
            if (t.position.left > u.width() - a.width() - 10) {
                $.post(r.PHPfile, {captcha: "qaptcha", qaptcha_key: l.attr("name")}, function (e) {
                    if (!e.error) {
                        a.draggable("disable").css("cursor", "default");
                        l.val("");
                        f.text(r.txtUnlock).addClass("dropSuccess").removeClass("dropError");
                        s.find("input[type='submit']").removeAttr("disabled");
                        if (r.autoSubmit)s.find("input[type='submit']").trigger("click")
                    }
                }, "json")
            }
        }})
    })
}};
jQuery.fn.QapTcha = jQuery.QapTcha.build;