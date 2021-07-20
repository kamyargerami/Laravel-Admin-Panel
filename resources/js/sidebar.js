$(".sidebar-dropdown > a").click(function () {
    $(".sidebar-submenu").slideUp(200);
    if ($(this).parent().hasClass("active")) {
        $(".sidebar-dropdown").removeClass("active");
        $(this).parent().removeClass("active");
    } else {
        $(".sidebar-dropdown").removeClass("active");
        $(this).next(".sidebar-submenu").slideDown(200);
        $(this).parent().addClass("active");
    }
});

$("#close-sidebar").click(function () {
    $(".page-wrapper").removeClass("toggled");
});
$("#show-sidebar").click(function () {
    $(".page-wrapper").addClass("toggled");
});

if ($(window).width() < 960) {
    $(".page-wrapper").removeClass("toggled");
}

$(window).resize(function () {
    if ($(window).width() > 960) {
        $(".page-wrapper").addClass("toggled");
    } else {
        $(".page-wrapper").removeClass("toggled");
    }
});


var url = window.location.href.split('?')[0].split('#')[0];
$('.sidebar-menu ul li').each(function () {
    link = $(this).children('a').attr('href');
    if (link != 'undefind' && link != '' && link != '#' && link != '/') {
        if (link == url || link == window.location.href) {
            $(this).closest('.sidebar-dropdown').addClass('active');
            $(this).children('a').addClass('text-white');
            $(this).parents('.sidebar-submenu').each(function () {
                $(this).show();
            });
        }
    }
});
