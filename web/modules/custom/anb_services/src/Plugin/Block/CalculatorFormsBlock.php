<?php

namespace Drupal\anb_services\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides block for DeliveryForm.
 *
 * @Block(
 *   id = "services_calculators_block",
 *   admin_label = @Translation("Calcular forms block"),
 * )
 */
class CalculatorFormsBlock extends BlockBase {

  /**
   * @inheritDoc
   */
  public function build() {
    $forms = [];
    $form_builder = \Drupal::formBuilder();

    $forms['delivery'] = $form_builder->getForm('\Drupal\anb_services\Form\DeliveryForm');
    $forms['storage'] = $form_builder->getForm('\Drupal\anb_services\Form\StorageForm');

    return [
      '#theme' => 'calculator_forms',
      '#forms' => $forms,
    ];
  }

}
