var $imageCta = $('.es-image-cta');
var $imageSpread = $('.es-image-spread');
var $logoBlocks = $('.logo-blocks');
var $tables = $(".es-table");


if ($imageCta) {
  $imageCta.toArray().forEach(imageCtaItem => {
    if ($(imageCtaItem).next('section').hasClass('es-image-cta')) {
      $(imageCtaItem).addClass('relative spacer-after');
      $(imageCtaItem).next('section').addClass('relative spacer-before');
    } else if ($(imageCtaItem).next('section').hasClass('es-image-spread')) {
      $(imageCtaItem).addClass('relative spacer-after');
      $(imageCtaItem).next('section').addClass('spacer-before');
    } else if ($(imageCtaItem).next('section').hasClass('logo-blocks')) {
      $(imageCtaItem).addClass('relative spacer-after');
      $(imageCtaItem).next('section').addClass('relative spacer-before');
    } else if ($(imageCtaItem).next('section').hasClass('es-table')) {
      $(imageCtaItem).addClass('relative spacer-after');
      $(imageCtaItem).next('section').addClass('relative spacer-before');
    }
  });
}

if ($imageSpread) {
  $imageSpread.toArray().forEach(imageSpreadItem => {
    if ($(imageSpreadItem).next('section').hasClass('es-image-cta')) {
      $(imageSpreadItem).addClass('relative spacer-after');
      $(imageSpreadItem).next('section').addClass('relative spacer-before');
    } else if ($(imageSpreadItem).next('section').hasClass('es-image-spread')) {
      $(imageSpreadItem).addClass('relative spacer-after');
      $(imageSpreadItem).next('section').addClass('relative spacer-before');
    } else if ($(imageSpreadItem).next('section').hasClass('logo-blocks')) {
      $(imageSpreadItem).addClass('relative spacer-after');
      $(imageSpreadItem).next('section').addClass('relative spacer-before');
    } else if ($(imageSpreadItem).next('section').hasClass('es-table')) {
      $(imageSpreadItem).addClass('relative spacer-after');
      $(imageSpreadItem).next('section').addClass('relative spacer-before');
    }
  });
}

if ($logoBlocks) {
  $logoBlocks.toArray().forEach(logoBlockItem => {
    if ($(logoBlockItem).next('section').hasClass('es-image-cta')) {
      $(logoBlockItem).addClass('relative spacer-after');
      $(logoBlockItem).next('section').addClass('relative spacer-before');
    } else if ($(logoBlockItem).next('section').hasClass('es-image-spread')) {
      $(logoBlockItem).addClass('relative spacer-after');
      $(logoBlockItem).next('section').addClass('relative spacer-before');
    } else if ($(logoBlockItem).next('section').hasClass('logo-blocks')) {
      $(logoBlockItem).addClass('relative spacer-after');
      $(logoBlockItem).next('section').addClass('relative spacer-before');
    } else if ($(logoBlockItem).next('section').hasClass('es-table')) {
      $(logoBlockItem).addClass('relative spacer-after');
      $(logoBlockItem).next('section').addClass('relative spacer-before');
    }
  });
}

if ($tables) {
  $tables.toArray().forEach(tableItem => {
    if ($(tableItem).next('section').hasClass('es-image-cta')) {
      $(tableItem).addClass('relative spacer-after');
      $(tableItem).next('section').addClass('relative spacer-before');
    } else if ($(tableItem).next('section').hasClass('es-image-spread')) {
      $(tableItem).addClass('relative spacer-after');
      $(tableItem).next('section').addClass('relative spacer-before');
    } else if ($(tableItem).next('section').hasClass('logo-blocks')) {
      $(tableItem).addClass('relative spacer-after');
      $(tableItem).next('section').addClass('relative spacer-before');
    } else if ($(tableItem).next('section').hasClass('es-table')) {
      $(tableItem).addClass('relative spacer-after');
      $(tableItem).next('section').addClass('relative spacer-before');
    }
  });
}
