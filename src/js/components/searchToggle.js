const $searchOpen = $(".search-open");
const $searchClose = $("#searchClose");
const $searchInput = $(".es-nav-search-input");
const $search = $(".es-nav-search");
const $open = $(".es-nav-mobile-menu");
const $close = $(".es-nav-mobile-close");
const $mobileNav = $(".es-nav-mobile");
const $subNavs = $(".es-nav-mobile-sub");
const $dropdownTrigger = $(".es-nav-mobile-dropdown");
const $body = $("body");
const $html = $("html");

$searchOpen.on("click", function() {
  console.log("search click");
  $search.addClass("-open");
  $searchInput.focus();

  if ($search.hasClass("-open") && $(window).innerWidth() < 768) {
    $mobileNav.removeClass("-open");
    $subNavs.removeClass("-expanded");
    $subNavs.css("display", "none");
    $dropdownTrigger.removeClass("-flip");
    $open.addClass("-show");
    $open.removeClass("-hide");
    $close.removeClass("-show");
    $close.addClass("-hide");
    $body.removeClass("-nav-open");
    $html.removeClass("-nav-open");
  }
});

$searchClose.on("click", function() {
  $search.removeClass("-open");
});

$searchClose.on("keyup", function(e) {
  if (e.keyCode == 13) {
    $search.removeClass("-open");
  }
});

$(document).keyup(function(e) {
  if (e.keyCode === 27) {
    if ($search.hasClass("-open")) {
      $search.removeClass("-open");
    }
  }
});
