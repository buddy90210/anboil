(function ($, Drupal) {

    // Set default values for date range fields.
    let date = new Date();
    document.getElementById('payment-from').valueAsDate = new Date(Date.UTC(date.getFullYear(), date.getMonth(), 1));
    document.getElementById('payment-to').valueAsDate = new Date(Date.UTC(date.getFullYear(), date.getMonth() + 1, 0));

    // обработчик сабмита
    $('.payment-history__form form').submit(function (e) {
        e.preventDefault();

        var $parent = $('.payment-history');
        var $result = $parent.find('.payment-history__result');

        $parent.addClass('is-loading');

        // data
        var formdata = $(".payment-history__form form").serializeArray();
        var data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });

        // ajax
        $.ajax({
            url: '/api/v1/get-payment-history?format=json',
            type: 'GET',
            data: data,
            dataType: 'json',
            success: responce => {
                $result.html(formatResults(responce));
                $parent.removeClass('is-loading');
            },
            error: responce => {
                $result.html('За этот период взаиморасчетов не найдено')
                $parent.removeClass('is-loading');
            }
        });
    });

    function formatResults(items) {

        let dateStart = new Date(document.getElementById('payment-from').value).toLocaleDateString("ru-RU");
        let dateEnd = new Date(document.getElementById('payment-to').value).toLocaleDateString("ru-RU");

        let template = '';
        let subTemplate = '';

        template += '<div class="transit">';

        template += `
            <div class="table-header">
                <div class="table-col">
                    <span class="col-label">Покупатель</span>
                </div>
                <div class="table-col">
                    <span class="col-label">Расчеты на</span> ${dateStart}
                </div>
                <div class="table-col">
                    <span class="col-label">Продажа</span>
                </div>
                <div class="table-col">
                    <span class="col-label">Предоплата</span>
                </div>
                <div class="table-col">
                    <span class="col-label">Расчеты на</span> ${dateEnd}
                </div>
            </div>
            <div class="table-sub-header">
                <div class="table-col">
                    <span class="col-label">Договор</span>
                </div>
                <div class="table-col">
                    <span class="col-label">Долг</span>
                </div>
                <div class="table-col">
                    <span class="col-label">Аванс</span>
                </div>
                <div class="table-col">
                    <span class="col-label">Продано</span>
                </div>
                <div class="table-col">
                    <span class="col-label">Оплачено</span>
                </div>
                <div class="table-col">
                    <span class="col-label">Поступило</span>
                </div>
                <div class="table-col">
                    <span class="col-label">Зачтено</span>
                </div>
                <div class="table-col">
                    <span class="col-label">Долг</span>
                </div>
                <div class="table-col">
                    <span class="col-label">Аванс</span>
                </div>
            </div>
        `;

        let itemSums = {
            'ДолгНаНачало': 0,
            'АвансНаНачало': 0,
            'Продано': 0,
            'Оплачено': 0,
            'УвеличениеАванса': 0,
            'ПогашениеАванса': 0,
            'ДолгНаКонец': 0,
            'АвансНаКонец': 0,
        };

        let userName;
        let userINN;

        for (let i = 0; i < items.data.length; i++) {
            let item = items.data[i];
            userName = items.userName;
            userINN = items.userINN;

            itemSums.ДолгНаНачало += item['ДолгНаНачало'] ? item['ДолгНаНачало'] : 0;
            itemSums.АвансНаНачало += item['АвансНаНачало'] ? item['АвансНаНачало'] : 0;
            itemSums.Продано += item['Продано'] ? item['Продано'] : 0;
            itemSums.Оплачено += item['Оплачено'] ? item['Оплачено'] : 0;
            itemSums.УвеличениеАванса += item['УвеличениеАванса'] ? item['УвеличениеАванса'] : 0;
            itemSums.ПогашениеАванса += item['ПогашениеАванса'] ? item['ПогашениеАванса'] : 0;
            itemSums.ДолгНаКонец += item['ДолгНаКонец'] ? item['ДолгНаКонец'] : 0;
            itemSums.АвансНаКонец += item['АвансНаКонец'] ? item['АвансНаКонец'] : 0;

            subTemplate +=`
                <div class="table-body">
                    <div class="table-col">
                        ${item['Договор']}
                    </div>
                    <div class="table-col">
                        ${item['ДолгНаНачало'] ? item['ДолгНаНачало'] : ''}
                    </div>
                    <div class="table-col">
                        ${item['АвансНаНачало'] ? item['АвансНаНачало'] : ''}
                    </div>
                    <div class="table-col">
                        ${item['Продано'] ? item['Продано'] : ''}
                    </div>
                    <div class="table-col">
                        ${item['Оплачено'] ? item['Оплачено'] : ''}
                    </div>
                    <div class="table-col">
                        ${item['УвеличениеАванса'] ? item['УвеличениеАванса'] : ''}
                    </div>
                    <div class="table-col">
                        ${item['ПогашениеАванса'] ? item['ПогашениеАванса'] : ''}
                    </div>
                    <div class="table-col">
                        ${item['ДолгНаКонец'] ? item['ДолгНаКонец'] : ''}
                    </div>
                    <div class="table-col">
                        ${item['АвансНаКонец'] ? item['АвансНаКонец'] : ''}
                    </div>
                </div>
            `;

        }

        template += `
            <div class="table-sub-header-lighter">
                <div class="table-col">
                    <span class="col-label">${userName} ИНН: ${userINN}</span>
                </div>
                <div class="table-col">
                    ${itemSums.ДолгНаНачало ? itemSums.ДолгНаНачало.toFixed(2) : ''}
                </div>
                <div class="table-col">
                    ${itemSums.АвансНаНачало ? itemSums.АвансНаНачало.toFixed(2) : ''}
                </div>
                <div class="table-col">
                    ${itemSums.Продано ? itemSums.Продано.toFixed(2) : ''}
                </div>
                <div class="table-col">
                    ${itemSums.Оплачено ? itemSums.Оплачено.toFixed(2) : ''}
                </div>
                <div class="table-col">
                    ${itemSums.УвеличениеАванса ? itemSums.УвеличениеАванса.toFixed(2) : ''}
                </div>
                <div class="table-col">
                    ${itemSums.ПогашениеАванса ? itemSums.ПогашениеАванса.toFixed(2) : ''}
                </div>
                <div class="table-col">
                    ${itemSums.ДолгНаКонец ? itemSums.ДолгНаКонец.toFixed(2) : ''}
                </div>
                <div class="table-col">
                   ${itemSums.АвансНаКонец ? itemSums.АвансНаКонец.toFixed(2) : ''}
                </div>
            </div>
        `;

        template += subTemplate;

        template += `
            <div class="table-sub-header">
                <div class="table-col">
                    <span class="col-label"><strong>Итого</strong></span>
                </div>
                <div class="table-col">
                    <strong>${itemSums.ДолгНаНачало ? itemSums.ДолгНаНачало.toFixed(2) : ''}</strong>
                </div>
                <div class="table-col">
                    <strong>${itemSums.АвансНаНачало ? itemSums.АвансНаНачало.toFixed(2) : ''}</strong>
                </div>
                <div class="table-col">
                    <strong>${itemSums.Продано ? itemSums.Продано.toFixed(2) : ''}</strong>
                </div>
                <div class="table-col">
                    <strong>${itemSums.Оплачено ? itemSums.Оплачено.toFixed(2) : ''}</strong>
                </div>
                <div class="table-col">
                    <strong>${itemSums.УвеличениеАванса ? itemSums.УвеличениеАванса.toFixed(2) : ''}</strong>
                </div>
                <div class="table-col">
                    <strong>${itemSums.ПогашениеАванса ? itemSums.ПогашениеАванса.toFixed(2) : ''}</strong>
                </div>
                <div class="table-col">
                    <strong>${itemSums.ДолгНаКонец ? itemSums.ДолгНаКонец.toFixed(2) : ''}</strong>
                </div>
                <div class="table-col">
                   <strong>${itemSums.АвансНаКонец ? itemSums.АвансНаКонец.toFixed(2) : ''}</strong>
                </div>
            </div>
        `;

        template += '</div>';

        return template;
    }

})(jQuery, Drupal);
