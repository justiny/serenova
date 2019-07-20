const $dropdownTrigger = $(".es-nav-mobile-dropdown");
const $open = $(".es-nav-mobile-menu");
const $close = $(".es-nav-mobile-close");
const $mobileNav = $(".es-nav-mobile");
const $subNavs = $(".es-nav-mobile-sub");
const $body = $("body");
const $html = $("html");
const $search = $(".es-nav-search");

// Mobile nav toggles
$open.on("click", function() {
  if (!$mobileNav.hasClass("-open")) {
    $mobileNav.addClass("-open");
    $open.removeClass("-show");
    $open.addClass("-hide");
    $close.addClass("-show");
    $close.removeClass("-hide");
    $close.focus();
    $body.addClass("-nav-open");
    $html.addClass("-nav-open");

    if ($subNavs.hasClass("-expanded")) {
      // Close any open dropdowns
      $subNavs.removeClass("-expanded");
      $subNavs.css("display", "none");
      $dropdownTrigger.removeClass("-flip");
    }
  }

  if ($search.hasClass("-open")) {
    $search.removeClass("-open");
  }
});

// Handle tab & enter navigation
$open.on("keyup", function(e) {
  if (e.keyCode == 13) {
    if (!$mobileNav.hasClass("-open")) {
      $mobileNav.addClass("-open");
      $open.removeClass("-show");
      $open.addClass("-hide");
      $close.addClass("-show");
      $close.removeClass("-hide");
      $close.focus();
      $body.addClass("-nav-open");
      $html.addClass("-nav-open");

      if ($subNavs.hasClass("-expanded")) {
        // Close any open dropdowns
        $subNavs.removeClass("-expanded");
        $subNavs.css("display", "none");
        $dropdownTrigger.removeClass("-flip");
      }
    }

    if ($search.hasClass("-open")) {
      $search.removeClass("-open");
    }
  }
});

$close.on("click", function() {
  if ($mobileNav.hasClass("-open")) {
    $mobileNav.removeClass("-open");
    $open.addClass("-show");
    $open.removeClass("-hide");
    $close.removeClass("-show");
    $close.addClass("-hide");
    $body.removeClass("-nav-open");
    $html.removeClass("-nav-open");

    // Close any open dropdowns
    $subNavs.removeClass("-expanded");
    $subNavs.css("display", "none");
    $dropdownTrigger.removeClass("-flip");
  }
});

// Handle tab & enter navigation
$close.on("keyup", function(e) {
  if (e.keyCode == 13) {
    if ($mobileNav.hasClass("-open")) {
      $mobileNav.removeClass("-open");
      $open.addClass("-show");
      $open.removeClass("-hide");
      $close.removeClass("-show");
      $close.addClass("-hide");
      $body.removeClass("-nav-open");
      $html.removeClass("-nav-open");

      // Close any open dropdowns
      $subNavs.removeClass("-expanded");
      $subNavs.css("display", "none");
      $dropdownTrigger.removeClass("-flip");
    }
  }
})

// Dropdowns in mobile nav
$dropdownTrigger.on("click", function() {
  let $list = $(this).next("ul");
  let $arrow = $(this).find("img");

  toggleNav($list, $arrow);
});

// Handle tab & enter navigation
$dropdownTrigger.on("keyup", function(e) {
  if (e.keyCode == 13) {
    let $list = $(this).next("ul");
    let $arrow = $(this).find("img");

    toggleNav($list, $arrow);
  }
});

$(window).on("resize", function() {
  if ($(window).innerWidth() > 768) {
    // Remove all mobile nav open classes
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

function toggleNav($activeItem, $arrow) {
  // Rotate the arrow
  $arrow.toggleClass("-flip");

  // check for the class if the list is displayed and toggle the display
  // doing this before adding or removing the -expanded class to provide
  // a smoother animation
  if ($activeItem.hasClass("-expanded")) {
    $activeItem.toggleClass("-expanded");

    setTimeout(function() {
      $activeItem.css("display", "none");
    }, 50);
  } else {
    $activeItem.css("display", "block");

    setTimeout(function() {
      $activeItem.toggleClass("-expanded");
    }, 50);
  }
}
