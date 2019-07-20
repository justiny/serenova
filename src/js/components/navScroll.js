import $ from "jquery";

const $window = $(window);
const $nav = $(".es-nav");
const $body = $("body");
const $announcementBarHeight = $('.es-announcement').height();
let mt;

(function() {
  if ($nav.length) {
    $window.scroll(function() {
      let scroll = $window.scrollTop();
      if (scroll >= 80 && $(window).width() >= 992) {
        $nav.addClass("-collapse");

        if ($announcementBarHeight) {
          mt = 76 + $announcementBarHeight;
        }

        $body.css("margin-top", mt + "px");
      } else if (scroll >= 80 && $(window).width() <= 992) {

        if ($announcementBarHeight) {
          mt = 66 + $announcementBarHeight;
        }

        $nav.addClass("-collapse");
        $body.css("margin-top", mt + "px");
      } else {
        $nav.removeClass("-collapse");
        $body.css("margin-top", "0px");
      }
    });
  }
})();
