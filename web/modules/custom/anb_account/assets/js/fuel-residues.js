(function ($, Drupal) {

  let target = $('.fuel-residues');

  $.ajax({
    url: '/api/v1/get-fuel-residues?format=json',
    type: 'GET',
    dataType: 'json',
    success: response => {
      target
        .html(formatResults(response))
        .removeClass('is-loading');
    },
    error: response => {
      target
        .html('Остатки топлива на хранении не найдены')
        .removeClass('is-loading');
    },
  });

  function formatResults(items) {
    let template = '';

    for (let i = 0; i < items.length; i++) {
      let item = items[i];

      template += '<div class="residue">';
      template += '<p class="residue__title">Наименование<span class="value">' + item['ТопливоНаименование'] + '</span></p>';
      template += '<p class="residue__value">Остаток<span class="value">Остаток: ' + item['КоличествоОстаток'] + '&nbsp;тонн</span></p>'
      template += '</div>';
    }

    return template;
  }

})(jQuery, Drupal);
