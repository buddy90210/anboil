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

/**
 * Provides a resource to get fuel transit for current user.
 *
 * @RestResource(
 *   id = "get_fuel_transit",
 *   label = @Translation("Get fuel transit."),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-fuel-transit"
 *   }
 * )
 */
class GetFuelTransit extends ResourceBase {

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

    // Send API request.
    $request_url = $api_url . '/tank_farm_anb/hs/exchange_/delivery?inn=' . $account_identifier . '&token=' . $api_token;
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
      $response[] = json_decode(json_encode($result), TRUE);
    }

    return new ModifiedResourceResponse($response, 200);
  }

}
