(function ($, Drupal) {

  function isElementInViewport (el) {
    if (typeof jQuery === "function" && el instanceof jQuery) {
      el = el[0];
    }
    var rect = el.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /* or $(window).height() */
      rect.right <= (window.innerWidth || document.documentElement.clientWidth) /* or $(window).width() */
    );
  }

  $(window).scroll(function () {
    let mapBlock = $('.bottom-map');
    let mapPlaceholder = mapBlock.find('.map-placeholder');
    let mapSrc = mapBlock.find('.map-placeholder').attr('data-src');

    if (isElementInViewport(mapBlock) && mapBlock.hasClass('map-loaded') === false) {
      mapBlock
        .html('<iframe frameborder="0" vspace="0" hspace="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" allowtransparency="true" src="' + mapSrc + '" scrolling="no"></iframe>')
        .addClass('map-loaded');
    }
  })

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
          responsive: [
            {breakpoint: 1160, settings: {slidesToShow: 3, slidesToScroll: 3}},
            {breakpoint: 960, settings: {slidesToShow: 2, slidesToScroll: 2}},
            {breakpoint: 720, settings: {slidesToShow: 1, slidesToScroll: 1, arrows: false}},
          ]
      });
    }
  };
})(jQuery, Drupal);
