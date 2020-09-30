/**
 * @file
 */
(function ($, Drupal) {

  'use strict';

  /**
   * Mobile menu.
   */
  $('.header-menu__trigger, .header-menu__close').click(function () {
    $('.header-menu').toggleClass('is-visible');
  })

  $(document).ready(function() {
    /**
     * Fancybox.
     */
    $('[data-fancybox]').fancybox({
      buttons : ['close'],
    });
  });

})(jQuery, Drupal);

(function ($, Drupal) {
  Drupal.behaviors.base = {
    attach: function (context, settings) {
      /**
       * Sliders init.
       */
      $('.element', context).slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        dots: false,
        autoplay: true,
        autoplaySpeed: 3500,
        responsive: [
          {breakpoint: 960, settings: {slidesToShow: 2, slidesToScroll: 2}},
        ]
      });

      /**
       * Sliders customization.
       */
      /**
       * Sliders customization.
       */
      let sliders = $('.slick-slider');
      let arrow = '<svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 11L6 6L1 1" stroke="#323232" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

      sliders.on('reInit afterChange', function(event, slick, currentSlide, nextSlide){
        let i = (currentSlide ? currentSlide : 0) + 1;
        let current = i;
        let total = slick.slideCount;

        if (current < 10) {
          current = '0' + current;
        }
        if (total < 10) {
          total = '0' + total;
        }

        $(this).find('.current').text(current);
        $(this).find('.total').text(total);
      });

      sliders.each(function() {
        let total = $(this).find('.slick-slide').not('.slick-cloned').length;

        if (total < 10) {
          total = '0' + total;
        }

        if ($(this).find('.slider-nav').length === 0) {
          let navTemplate = '<div class="slider-nav"><div class="slider-nav__inner container"><p class="slider-nav__pager"><span class="current">01</span>&nbsp;/&nbsp;<span class="total">' + total + '</span></p></div></div>';
          $(this).append(navTemplate);

          $(this).find('.slider-nav__inner').prepend($(this).find('.slick-prev').html(arrow));
          $(this).find('.slick-dots').insertAfter($(this).find('.slider-nav__pager'));
          $(this).find('.slider-nav__inner').append($(this).find('.slick-next').html(arrow));
        }
      })

      /**
       * Phone input mask.
       */
      let phone_fields = $('input[name*="phone"]');
      phone_fields.attr('type','tel').mask('+7 (999) 999-99-99');
    }
  };
})(jQuery, Drupal);
