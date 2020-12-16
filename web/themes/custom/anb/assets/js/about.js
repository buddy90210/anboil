(function ($, Drupal) {

  Drupal.behaviors.about = {
    attach: function (context, settings) {
      'use strict';

      $('.bottom-content').addClass('no-margin');

      $('.certificates__items', context).slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 4,
        dots: true,
        responsive: [
          {breakpoint: 960, settings: {slidesToShow: 2, slidesToScroll: 2}},
        ]
      });
    }
  };
})(jQuery, Drupal);
