<?php

namespace Drupal\anb_avtodispetcher;

use Drupal\Core\Site\Settings;

/**
 * Avtodispetcher Manager.
 */
class AvtodispetcherManager {

  /**
   * The Avtodispetcher API login.
   *
   * @var string
   */
  protected $apiLogin;

  /**
   * The Avtodispetcher API password.
   *
   * @var string
   */
  protected $apiPassword;

  /**
   * The http client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  public function __construct($http_client) {
    $avtodispetcher_settings = Settings::get('avtodispetcher');

    if (is_null($avtodispetcher_settings)) {
      return NULL;
    }

    $this->httpClient = $http_client;
    $this->apiLogin = $avtodispetcher_settings['login'];
    $this->apiPassword = $avtodispetcher_settings['password'];
  }

  /**
   * Get city tips for autocomplete form field.
   *
   * @param string $string
   * @return array
   */
  public function getCityTips($string) {
    $query_length = strlen($string);

    // Api accept only 3 or more characters string.
    if ($query_length < 3) {
      return NULL;
    }

    // Send API request.
    $api_query_url = 'https://api.avtodispetcher.ru/v1/cities?q=' . $string . '&limit=5&onlyCountries[]=RU';
    $request = $this->httpClient->request('GET', $api_query_url);

    if ($request->getStatusCode() !== 200) {
      return NULL;
    }

    $request_body = $request->getBody();
    $results = json_decode($request_body);

    return $results;
  }

  /**
   * Get route distance, time and price.
   *
   * @param string $from
   * @param string $to
   * @return array
   */
  public function getRoute($from, $to) {
    // Check parameters.
    if (is_null($from) || is_null($to)) {
      return NULL;
    }

    if ($from === $to) {
      return NULL;
    }

    if (empty($this->getCityTips($from)) || empty($this->getCityTips($to))) {
      return NULL;
    }

    // Format values for request.
    $from = urlencode($from);
    $to = urlencode($to);

    // Send API request.
    $api_query_url = 'https://api.avtodispetcher.ru/v1/route?from=' . $from . '&to=' . $to;
    $request = $this->httpClient->request('GET', $api_query_url, [
      'auth' => [$this->apiLogin, $this->apiPassword],
    ]);

    if ($request->getStatusCode() !== 200) {
      return NULL;
    }

    $request_body = $request->getBody();
    $results = json_decode($request_body);

    // Calculate route price.
    $route_distance = $results->kilometers;
    $route_time = $results->minutes / 60;

    $fuel_consumption = 100;
    $fuel_price = 90;

    $route_fuel_consumption = $fuel_consumption / 100 * $route_distance;
    $route_fuel_costs = $route_fuel_consumption * $fuel_price;

    return [
      'distance' => round($route_distance),
      'time' => round($route_time),
      'price' => round($route_fuel_costs),
    ];
  }
}
