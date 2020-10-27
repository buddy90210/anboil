(function ($, Drupal) {

  Drupal.behaviors.services = {
    attach: function (context, settings) {
      'use strict';

      let results_items = $('.results-items');

      results_items.each(function () {
        if ($(this).text() !== '') {
          $('.service__actions').removeClass('visually-hidden');
          $('.service__results__data').html($(this).html());

          if ($(this).parents('.tabcontent.is-visible').length > 0) {
            $('.service__results__data').html($(this).html());
          }

          $(this).html('');

          $('input[name="field_sent_from[0][value]"]').val($(this).text());
        };
      })

      $('.calculators .tab').click(function () {
        $('.service__actions').addClass('visually-hidden');
        $('.service__results__data').html('');
      })
    }
  };
})(jQuery, Drupal);
