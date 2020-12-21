(function ($, Drupal) {

  Drupal.behaviors.services = {
    attach: function (context, settings) {
      'use strict';

      if ($('.service').length> 0 ) {
        $('.bottom-content').addClass('no-margin');
      }

      $('.calculator-form .form-submit').each(function () {
        if ($(this).siblings('.button-service-submit').length === 0) {
          $('<span class="button button-service-submit">Рассчитать</span>').insertAfter($(this));
        }
      });

      $('.button-service-submit').click(function () {
        let submit = $(this).siblings('.form-submit');
        if (submit.hasClass('form-disabled') === false) {
          submit.click();
        }
      })

      let results_items = $('.results-items');

      results_items.each(function () {
        if ($(this).text() !== '') {
          $('.service__actions').removeClass('visually-hidden');
          $('.service__results__data').html($(this).html());

          if ($(this).parents('.tabcontent.is-visible').length > 0) {
            $('.service__results__data').html($(this).html());
          }

          // Get user input values.
          let form = $(this).parents('form');
          let userInput = '';
          form.find('.calc-result-field').each(function () {
            userInput += $(this).attr('placeholder') + ': ' + $(this).val() + '\n';
          })
          $('.service__form textarea[name="message[0][value]"]').val(userInput);

          $(this).html('');
        };
      })

      $('.calculators .tab').click(function () {
        $('.service__actions').addClass('visually-hidden');
        $('.service__results__data').html('');
      })
    }
  };
})(jQuery, Drupal);
