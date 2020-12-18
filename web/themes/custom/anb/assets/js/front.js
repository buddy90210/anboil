(function ($, Drupal) {

  Drupal.behaviors.front = {
    attach: function (context, settings) {
      'use strict';

      let frontHeroTab = $('.front-hero__tab');
      frontHeroTab.click(function () {
        let tabId = $(this).attr('data-tab');

        $('.front-hero__slide').removeClass('is-active');
        $('.front-hero__slide[data-tab="' + tabId + '"]').addClass('is-active');

        frontHeroTab.removeClass('is-active');
        $(this).addClass('is-active');
      });

      $('.front-calc-results').append($('.service__actions'));

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
