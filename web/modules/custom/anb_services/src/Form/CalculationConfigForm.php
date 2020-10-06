<?php

/**
 * @file
 * Contains Drupal\anb_services\Form\CalculationDeliveryForm.
 */

namespace Drupal\anb_services\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides calculation configuration form.
 */
class CalculationConfigForm extends ConfigFormBase {

  /**
   * @inheritDoc
   */
  protected function getEditableConfigNames() {
    return [
      'anb_base.settings_form',
    ];
  }

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'calculation_configuration_form';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => 'Title',
    ];

    return parent::buildForm($form, $form_state);
  }

}
