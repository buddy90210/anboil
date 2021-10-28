(function ($, Drupal) {

  Drupal.behaviors.jobs = {
    attach: function (context, settings) {
      'use strict';

      let jobFormTrigger = $('.modal-trigger[data-modal="jobform"]', context);

      jobFormTrigger.click(function () {
        let job = $(this).parents('.job');
        $('#jobform textarea[name="message[0][value]"]').val('Здравствуйте! Интересует вакансия ' + job.find('.job-title').text().trim() + '.');
      })
    }
  };
})(jQuery, Drupal);
