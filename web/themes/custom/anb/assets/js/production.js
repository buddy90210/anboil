(function ($, Drupal) {
  Drupal.behaviors.production = {
    attach: function (context, settings) {
      'use strict';

      let filters = $('.filters');
      let tab = filters.find('.tabs input');

      if (filters.length === 0) {
        return;
      }

      // Filters form auto-submit.
      tab.change(function () {
        filters.find('.form-actions .button').once().click();
      })

      // Results count.
      let products = $('.products-items .product');
      $('.filters-info__title .count-circle').text(products.length);
    }
  };
})(jQuery, Drupal);
