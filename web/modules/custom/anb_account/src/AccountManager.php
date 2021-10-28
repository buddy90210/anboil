<?php

namespace Drupal\anb_account;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Session\AccountProxy;
use Drupal\user\UserStorage;

/**
 * Provides account manager service.
 */
class AccountManager {

  /**
   * The current user.
   */
  private AccountProxy $currentUser;

  /**
   * The user storage.
   */
  private UserStorage $userStorage;

  /**
   * The config factory.
   */
  private ConfigFactory $configFactory;

  /**
   * AccountManager constructor.
   *
   * @param \Drupal\Core\Session\AccountProxy $current_user
   *   The current user.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   The entity type manager.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   */
  public function __construct($current_user, $entity_type_manager, $config_factory) {
    $this->currentUser = $current_user;
    $this->userStorage = $entity_type_manager->getStorage('user');
    $this->configFactory = $config_factory;
  }

  /**
   * Get user api identifier (INN).
   *
   * @return string|null
   */
  public function getUserApiIdentifier() {
    $account = $this->userStorage->load($this->currentUser->id());

    if ($account->field_inn->isEmpty()) {
      return NULL;
    }

    return $account->field_inn->value;
  }

  /**
   * Get anb_account settings configuration.
   *
   * @return string
   */
  public function getAccountApiConfig() {
    return $this->configFactory->get('anb_account.settings');
  }

}
