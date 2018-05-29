(function(){
    let state = 0;

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
            case "pager":
                document.cookie = "page=" + _data + "; expires=Thu, 18 Dec 2022 12:00:00 UTC; path=/";
                document.location.reload();
                break;
            case "perpage":
                document.cookie = "perpage=" + _data + "; expires=Thu, 18 Dec 2022 12:00:00 UTC; path=/";
                document.location.reload();
                break;
            case 'scroll':
                if (state === 0) { // pannel not fixed awhile
                    let pos = $('#buttons').position();
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
    }

    $(document).on("click", "[data-handle]", function () {
        if ($(this).is("span, button")) {
            return handle.call(this, $(this).data("handle"));
        }
    }).on("change", "[data-handle]", function () {
        if ($(this).is("select")) {
            let val = $(":selected", this).val();
            return handle.call(this, $(this).data("handle"), val);
        }
    });
    /*let to=false;
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
        let u = data.data[0] || {name: "xxx"};
        if ("progress" in u) {
            if ($("#progress").length == 0) {
                $("#uploads ul").append('<li><progress id="progress" max="1" value="' + u.progress + '">' + u.name + "</progress></li>");
            }
            if (u.progress < 1) {
                $("#progress").attr("value", u.progress);
            } else {
                let progress = $("#progress"), txt = progress.html(), parent = progress.parent();
                progress.remove();
                parent.html(txt);
                if ("analize" in data) {
                    let report = [];
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
            $("#uploads ul").append("<li>" + u.name + "</li>");
        }
        DropPlus.log(u);
    }
}