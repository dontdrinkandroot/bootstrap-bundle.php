var $ = require('jquery');

$(function () {
    var $nav = $(".navbar.fixed-top");
    $nav.toggleClass('scrolled', $(this).scrollTop() > 0);
    $(document).scroll(function () {
        $nav.toggleClass('scrolled', $(this).scrollTop() > 0);
    });
});
