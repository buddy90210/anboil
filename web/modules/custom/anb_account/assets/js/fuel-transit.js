(function ($, Drupal) {

  let target = $('.fuel-transit');

  $.ajax({
    url: '/api/v1/get-fuel-transit?format=json',
    type: 'GET',
    dataType: 'json',
    success: response => {
      target
        .html(formatResults(response))
        .removeClass('is-loading');

      initTemplates();
    },
    error: response => {
      target
        .html('Отправления топлива не найдены')
        .removeClass('is-loading');
    },
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
          '<div class="transit__col fuel-transit__doc"><span class="label">Номер документа</span></span>' + item['ДокументНомер'] + '</div>' +
          '<div class="transit__col fuel-transit__date"><span class="label">Дата документа</span></span>' + dateCreatedFormatted + '</div>' +
          '<div class="transit__col fuel-transit__car"><span class="label">Автомобиль</span></span>' + item['Автомобиль'] + '</div>' +
          '<div class="transit__col fuel-transit__place"><span class="label">Место разгрузки</span></span>' + item['МестоРазгрузки'] + '</div>' +
          '<div class="transit__col fuel-transit__type"><span class="label">Вид топлива</span></span>' + good['Номенклатура'] + '</div>' +
          '<div class="transit__col fuel-transit__quantity"><span class="label">Количество (т.)</span></span>' + good['Количество'] + '</div>' +
          '<div class="transit__col fuel-transit__price"><span class="label">Цена</span>' + good['Цена'] + '&nbsp;₽</div>' +
          '<div class="transit__col fuel-transit__sum"><span class="label">Сумма</span>' + good['Сумма'] + '&nbsp;₽</div>' +
          '<div class="transit__col fuel-transit__more">подробнее<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L5 5L9 1" stroke="#878787" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>' +
          '<div class="transit__col fuel-transit__map" data-driver="' + item['Водитель'] + '" data-phone="' + item['НомерТелефона'] + '" data-lat="' + item['lat'] + '" data-long="' + item['lon'] + '"></div>';

        template += '</div>';
      }

      template += '</div>';
    }

    return template;
  }

  function initTemplates() {
    let mapToggler = $('.fuel-transit__more');
    mapToggler.click(function () {
      let target = $(this).parents('.transit');

      target.toggleClass('is-expanded');

      if (target.hasClass('map-loaded') === false) {
        let map = target.find('.fuel-transit__map');
        let lat = map.attr('data-lat');
        let long = map.attr('data-long');
        let driverName = map.attr('data-driver');
        let driverPhone = map.attr('data-phone');
        let mapIframe = '';
        if (lat && long) {
          mapIframe = '<iframe src="https://maps.google.com/maps?q=' + lat + ', ' + long + '&z=15&output=embed" width="360" height="300" frameborder="0" style="border:0"></iframe>';
        } else {
          mapIframe = '<div class="empty-map"><p>Отсутствуют координаты автомобиля</p></div>';
        }
        map.html('<p class="label">Местоположение автомобиля / <span>Водитель: <strong>' + driverName + '</strong></span> <span>Номер телефона: <strong>' + driverPhone + '</strong></span></p>' + mapIframe);
        target.addClass('map-loaded');
      }
    })

  }

})(jQuery, Drupal);
