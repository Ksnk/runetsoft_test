(function(){
    var state = 0;

    function handle(reason, _data) {
        switch (reason) {
            case "cleardata":
                $.post(document.location,
                    {'handler': '::cleardata'},
                    function (data) {
                         document.location.reload();
                    }
                );
                break;
            case "page":
                document.cookie = "page=" + $(this).data('data') + "; expires=Thu, 18 Dec 2022 12:00:00 UTC; path=/";
                document.location.reload();
                break;
            case "perpage":
                document.cookie = "perpage=" + $(this).data('data') + "; expires=Thu, 18 Dec 2022 12:00:00 UTC; path=/";
                document.location.reload();
                break;
            case 'scroll':
                if (state === 0) { // pannel not fixed awhile
                    var pos = $('#buttons').position();
                    if ($(window).scrollTop() > pos.top) {
                        $('#buttons').addClass('fixed');
                        state = pos.top;
                    }
                } else {
                    if ($(window).scrollTop() < state) {
                        $('#buttons').removeClass('fixed');
                        state = 0;
                    }
                }

                break;
            default:
                console.log(reason);
        }
        return false;
    }

    $(document).on("click", "[data-handle]", function () {
        if ($(this).is("span, button, a")) {
            return handle.call(this, $(this).data("handle"));
        }
    }).on("change", "[data-handle]", function () {
        if ($(this).is("select")) {
            var val = $(":selected", this).val();
            return handle.call(this, $(this).data("handle"), val);
        }
    });
    /*var to=false;
    $(window).scroll(function() {
        if(to) clearTimeout(to);
        to=setTimeout(function(){handle('scroll')},100);
    }); */


})();
DropPlus = {
    maxpicture: 300000,
    maxfile: 50000,
    hugefile: 2000000,
    maxwidth: 1024,
    maxheight: 1024,
    debug: false,
    ajax_handle: function (data) {

        if ("debug" in data) {
            DropPlus.log(data.debug);
        }
        if ("analize" in data) {
            DropPlus.log(data.analize);
        }
        if ("table" in data) {
            $('#table').html(data.table);
        }
        if (!data.data) {
            DropPlus.log(data);
            return;
        }
        var u = data.data[0] || {name: "xxx"};
        if ("progress" in u) {
            if ($("#progress").length == 0) {
                $("#uploads ul").html('<li><progress id="progress" max="1" value="' + u.progress + '">' + u.name + "</progress></li>");
            }
            if (u.progress < 1) {
                $("#progress").attr("value", u.progress);
            } else {
                var progress = $("#progress"), txt = progress.html(), parent = progress.parent();
                progress.remove();
                parent.html(txt);
                if ("analize" in data) {
                    var report = [];
                    if (data.analize.itemfound) report.push('товаров: ' + data.analize.itemfound);
                    report.push('записано в базу: ' + (data.analize.itemstored || 0));
                    if (data.analize.error) {
                        report.push('ошибок: ' + data.analize.error.length);
                        DropPlus.log(data.analize.error);
                    }
                    $('<span>').appendTo(parent).html(' ' + report.join(', '))
                }
            }
        } else {
            $("#uploads ul").html("<li>" + u.name + "</li>");
        }
        DropPlus.log(u);
    }
}