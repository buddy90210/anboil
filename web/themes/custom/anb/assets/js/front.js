(function ($, Drupal) {

  Drupal.behaviors.front = {
    attach: function (context, settings) {
      'use strict';

      $('.hero-slides__items', context).slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
      });

        $('.news-front-items', context).slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 4,
        dots: true,
      });
    }
  };
})(jQuery, Drupal);
