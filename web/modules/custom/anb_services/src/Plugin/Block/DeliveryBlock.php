<?php

namespace Drupal\anb_services\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides block for DeliveryForm.
 *
 * @Block(
 *   id = "services_delivery_block",
 *   admin_label = @Translation("Delivery form block"),
 * )
 */
class DeliveryBlock extends BlockBase {

  /**
   * @inheritDoc
   */
  public function build() {
    $form = \Drupal::formBuilder()
      ->getForm('Drupal\anb_services\Form\DeliveryForm');

    return $form;
  }

}
