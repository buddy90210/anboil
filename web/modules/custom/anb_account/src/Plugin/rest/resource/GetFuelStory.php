<?php

namespace Drupal\anb_account\Plugin\rest\resource;

use Drupal\anb_account\AccountManager;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Client;
use function preg_replace;

/**
 * Provides a resource to get fuel story for current user.
 *
 * @RestResource(
 *   id = "get_fuel_story",
 *   label = @Translation("Get fuel story."),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-fuel-story"
 *   }
 * )
 */
class GetFuelStory extends ResourceBase {

  /**
   * The account manager.
   *
   * @var \Drupal\anb_account\AccountManager $accountManager
   */
  private AccountManager $accountManager;

  /**
   * The http client.
   */
  private Client $httpClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, array $serializer_formats, LoggerInterface $logger, $account_manager, $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->accountManager = $account_manager;
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('anb_account'),
      $container->get('anb_account.manager'),
      $container->get('http_client')
    );
  }

  /**
   * Responds to GET requests.
   */
  public function get() {
    $response = [];

    // Get account identifier (INN field).
    $account_identifier = $this->accountManager->getUserApiIdentifier();

    if (is_null($account_identifier)) {
      return new ModifiedResourceResponse('API identifier not found.', 400);
    }

    // Get API config.
    $api_config = $this->accountManager->getAccountApiConfig()->getRawData();

    if (is_null($api_config)) {
      return new ModifiedResourceResponse('API configuration not set.', 400);
    }
    if (!isset($api_config['api_url']) || !isset($api_config['api_token'])) {
      return new ModifiedResourceResponse('API configuration not set.', 400);
    }

    $api_url = $api_config['api_url'];
    $api_token = $api_config['api_token'];

    // Check if empty
    $dateFrom = preg_replace("/[^\d]+/", '', $_GET['fuel-from']) . '0000';
    $dateTo = preg_replace("/[^\d]+/", '', $_GET['fuel-to']) . '2359';

    // error handling
    $dateError = array();

    if (strlen($dateFrom) < 12){
      $dateError[] = 'начала';
    }
    if (strlen($dateTo) < 12){
      $dateError[] = 'окончания';
    }

    if (!empty($dateError)){
      $dateError = 'Вы не указали дату '.implode(' и ', $dateError) . '!';
      return new ModifiedResourceResponse($dateError, 400);
    }

    //get bazis form value
    if (isset($_GET['bazis']) && !empty($_GET['bazis']) && $_GET['bazis'] !== 'anb') {
      $request_url = $api_url . '/buh_anb/hs/exchange_/sale_history?inn=' . $account_identifier . '&token=' . $api_token.'&time_start='.$dateFrom.'&time_end='.$dateTo.'&storage='.$_GET['bazis'];
    } else {
      $request_url = $api_url . '/tank_farm_anb/hs/exchange_/sale_history?inn=' . $account_identifier . '&token=' . $api_token.'&time_start='.$dateFrom.'&time_end='.$dateTo;
    }
    // Send API request.
    $request = $this->httpClient->request('GET', $request_url);

    if ($request->getStatusCode() !== 200) {
      return new ModifiedResourceResponse('API response error.', 400);
    }

    $request_body = $request->getBody();
    $results = json_decode($request_body);

    if (empty($results)) {
      return new ModifiedResourceResponse('API response is empty.', 400);
    }

    foreach ($results as $result) {
      $response[] = json_decode(json_encode($result), true);
    }

    return new ModifiedResourceResponse($response, 200);
  }

}
