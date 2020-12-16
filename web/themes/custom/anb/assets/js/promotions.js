(function ($, Drupal) {
  Drupal.behaviors.promotions = {
    attach: function (context, settings) {

      'use strict';

      $('.promos__items', context).slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        dots: true,
        responsive: [
          {breakpoint: 960, settings: {slidesToShow: 2, slidesToScroll: 2}},
        ]
      });
    }
  };
})(jQuery, Drupal);
