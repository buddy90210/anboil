(function ($, Drupal) {

  Drupal.behaviors.production = {
    attach: function (context, settings) {
      'use strict';

      let categoryDescription = $('.category__description');
      $('.bottom-content__content').prepend(categoryDescription);

      let filters = $('.filters');
      let tab = filters.find('.tabs input');

      // Filters form auto-submit.
      tab.change(function () {
        filters.find('.form-actions .button').once().click();
      });

      // Consult trigger.
      let productionConsultTrigger = $('.category__button[data-modal="consultmodal"]', context);
      productionConsultTrigger.click(function () {
        $('#consultmodal textarea[name="message[0][value]"]').val('Здравствуйте! Меня интересует ' + $('h1').text().trim() + '.');
      })

      // Add to cart button.
      let addToCart = $('.modal-trigger[data-modal="orderform"]', context);
      addToCart.click(function () {
        let product = $(this).parents('.product');
        $('#orderform textarea[name="message[0][value]"]').val(product.find('.product__title').text().trim());
      })

      // Results count.
      let products = $('.products-items .product');
      $('.filters-info__title .count-circle').text(products.length);

      // Cities toggler.
      let cities_toggler = $('.cities-block__toggler');

      cities_toggler.click(function () {
        let toggler_text = $(this).find('i');

        $(this).siblings('.cities-block-item__cities').toggleClass('is-expanded');
        $(this).toggleClass('is-active');

        if ($(this).hasClass('is-active')) {
          toggler_text.text('свернуть');
        } else {
          toggler_text.text('ещё');
        }
      })
    }
  };
})(jQuery, Drupal);
