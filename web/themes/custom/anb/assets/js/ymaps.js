function init () {
  /**
   * Создаем мультимаршрут.
   * Первым аргументом передаем модель либо объект описания модели.
   * Вторым аргументом передаем опции отображения мультимаршрута.
   * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/multiRouter.MultiRoute.xml
   * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/multiRouter.MultiRouteModel.xml
   */
  var routeStartPoint = [45.058403, 39.006593]; // Краснодар
  var routeEndPoint = document.querySelector('#map').getAttribute('data-place');

  var multiRoute = new ymaps.multiRouter.MultiRoute({
    // Описание опорных точек мультимаршрута.
    referencePoints: [
      routeStartPoint,
      routeEndPoint
    ],
    // Параметры маршрутизации.
    params: {
      // Ограничение на максимальное количество маршрутов, возвращаемое маршрутизатором.
      results: 1
    }
  }, {
    // Автоматически устанавливать границы карты так, чтобы маршрут был виден целиком.
    boundsAutoApply: true
  });

  console.log(multiRoute)

  // Создаем кнопки для управления мультимаршрутом.
  var myMap = new ymaps.Map('map', {
    center: routeStartPoint,
    zoom: 7,
  }, {});

  // Добавляем мультимаршрут на карту.
  myMap.geoObjects.add(multiRoute);
}

ymaps.ready(init);
