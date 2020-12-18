(function ($, Drupal) {
  Drupal.behaviors.promotions = {
    attach: function (context, settings) {

      'use strict';

      let promoTrigger = $('.modal-trigger[data-modal="discountform"]');
      promoTrigger.click(function () {
        let promo = $(this).parents('.promos__item');
        $('#discountform textarea[name="message[0][value]"]').val(promo.find('.promo__title').text().trim());
      })


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
