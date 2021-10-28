(function ($, Drupal) {
  Drupal.behaviors.clients_partners = {
    attach: function (context, settings) {

      'use strict';

      $('.bottom-content').addClass('no-margin');

      $('.logo-blocks__items', context).slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 4,
        dots: true,
        responsive: [
          {breakpoint: 960, settings: {slidesToShow: 2, slidesToScroll: 2}},
          {breakpoint: 720, settings: {slidesToShow: 2, slidesToScroll: 2, arrows: false}},
        ]
      });
    }
  };
})(jQuery, Drupal);
