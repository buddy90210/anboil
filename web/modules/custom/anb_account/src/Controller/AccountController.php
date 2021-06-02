<?php

namespace Drupal\anb_account\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for account pages.
 */
class AccountController extends ControllerBase {

  /**
   * Redirects users to fuel transit route.
   */
  public function userPage() {
    return $this->redirect('anb_account.fuel_transit');
  }

  /**
   * Provides fuel residues page.
   */
  public function fuelResidues() {
    return [
      '#theme' => 'fuel_residues',
      '#attached' => [
        'library' => [
          'anb_account/fuel-residues'
        ],
      ],
    ];
  }

  /**
   * Provides fuel transit page.
   */
  public function fuelTransit() {
    return [
      '#theme' => 'fuel_transit',
      '#attached' => [
        'library' => [
          'anb_account/fuel-transit'
        ],
      ],
    ];
  }

  /**
   * Provides account settings page.
   */
  public function accountSettings() {
    $entity_type_manager = $this->entityTypeManager();
    $account = $entity_type_manager->getStorage('user')
      ->load($this->currentUser()->id());

    $formObject = $entity_type_manager
      ->getFormObject('user', 'default')
      ->setEntity($account);

    $form = $this->formBuilder()->getForm($formObject);

    $form['actions']['logout'] = [
      '#type' => 'html_tag',
      '#tag' => 'a',
      '#value' => 'Выход',
      '#attributes' => [
        'href' => ['/user/logout'],
        'class' => ['button'],
      ],
      '#weight' => 9,
    ];

    return $form;
  }

}