(function ($, Drupal) {

  // Set default values for date range fields.
  let date = new Date();
  document.getElementById('fuel-from').valueAsDate = new Date(Date.UTC(date.getFullYear(), date.getMonth(), 1));
  document.getElementById('fuel-to').valueAsDate = new Date(Date.UTC(date.getFullYear(), date.getMonth() + 1, 0));

  // обработчик сабмита
  $('.fuel-story__form form').submit(function(e){
    e.preventDefault();

    var $parent = $('.fuel-story');
    var $result = $parent.find('.fuel-story__result');

    $parent.addClass('is-loading');

    // data
    var formdata = $(".fuel-story__form form").serializeArray();
    var data = {};
    $(formdata).each(function(index, obj){
      data[obj.name] = obj.value;
    });

    // ajax
    $.ajax({
      url: '/api/v1/get-fuel-story?format=json',
      type: 'GET',
      data: data,
      dataType: 'json',
      success: responce => {
        console.log('succes resp', responce);
        $result.html(formatResults(responce));
        $parent.removeClass('is-loading');
      },
      error: responce => {
        console.log('succes resp', responce);
        $result.html('За этот период отгрузок не найдено')
        $parent.removeClass('is-loading');
      }
    });
  });

  function formatResults(items) {
    let template = '';

    for (let i = 0; i < items.length; i++) {
      let item = items[i];
      let goods = item['Товары'];

      for (let g = 0; g < goods.length; g++) {
        let good = goods[g];

        let dateCreated = new Date(item['ДокументДата'].replace(
          /^(\d{4})(\d\d)(\d\d)(\d\d)(\d\d)(\d\d)$/,
          '$4:$5:$6 $2/$3/$1'
        ));
        let dateCreatedFormatted = dateCreated.toLocaleString().replace(',', ' ');

        template += '<div class="transit">' +
          '<div class="transit__col fuel-story__doc"><span class="label">Номер документа</span></span>' + item['ДокументНомер'] + '</div>' +
          '<div class="transit__col fuel-story__date"><span class="label">Дата документа</span></span>' + dateCreatedFormatted + '</div>' +
          '<div class="transit__col fuel-story__car"><span class="label">Автомобиль</span></span>' + item['Автомобиль'] + '</div>' +
          '<div class="transit__col fuel-story__place"><span class="label">Место разгрузки</span></span>' + item['МестоРазгрузки'] + '</div>' +
          '<div class="transit__col fuel-story__type"><span class="label">Вид топлива</span></span>' + good['Номенклатура'] + '</div>' +
          '<div class="transit__col fuel-story__quantity"><span class="label">Количество (т.)</span></span>' + good['Количество'] + '</div>' +
          '<div class="transit__col fuel-story__price"><span class="label">Цена</span>' + good['Цена'] + '&nbsp;₽</div>' +
          '<div class="transit__col fuel-story__sum"><span class="label">Сумма</span>' + good['Сумма'] + '&nbsp;₽</div>';

        template += '</div>';
      }

      template += '</div>';
    }

    return template;
  }

})(jQuery, Drupal);
