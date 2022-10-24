<?php

namespace Drupal\anb_account\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides account menu block.
 *
 * @Block(
 *   id = "account_menu_block",
 *   admin_label = @Translation("Account menu block"),
 *   category = @Translation("Custom")
 * )
 */
class AccountMenuBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The request stack.
   *
   * @var \Drupal\Core\Routing\RouteMatch
   */
  protected $routeMatch;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->routeMatch = $container->get('current_route_match');

    return $instance;
  }

  public function build() {
    $build = [];
    $current_route = $this->routeMatch->getRouteName();

    $menu = [
      [
        'route' => 'anb_account.fuel_transit',
        'title' => 'Топливо в пути',
        'is_active' => FALSE,
      ],
      [
        'route' => 'anb_account.fuel_story',
        'title' => 'История отгрузок',
        'is_active' => FALSE,
      ],
      [
        'route' => 'anb_account.fuel_residues',
        'title' => 'Остаток топлива на хранении',
        'is_active' => FALSE,
      ],
      [
        'route' => 'anb_account.payment_history',
        'title' => 'Взаиморасчеты',
        'is_active' => FALSE,
      ],
      [
        'route' => 'anb_account.account_settings',
        'title' => 'Настройки',
        'is_active' => FALSE,
      ],
    ];

    $menu = array_map(function ($link) use ($current_route) {
      if ($link['route'] === $current_route) {
        $link['is_active'] = TRUE;
      }

      return $link;
    }, $menu);

    $build['content'] = [
      '#theme' => 'account_menu',
      '#links' => $menu,
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $cache_contexts = [
      'route.name',
    ];

    return Cache::mergeTags(
      parent::getCacheContexts(),
      $cache_contexts,
    );
  }

}
