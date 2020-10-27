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
      'anb_services.settings_form',
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
    $config = $this->config('anb_services.settings_form');

    $form['storage_price_default'] = [
      '#type' => 'textfield',
      '#title' => 'Стоимость хранения ГСМ (руб.)',
      '#default_value' => $config->get('storage_price_default'),
    ];

    $form['storage_price_extra'] = [
      '#type' => 'textfield',
      '#title' => 'Стоимость хранения ГСМ более месяца (руб.)',
      '#default_value' => $config->get('storage_price_extra'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('anb_services.settings_form');

    $config->set('storage_price_default', $form_state->getValue('storage_price_default'))
    ->set('storage_price_extra', $form_state->getValue('storage_price_extra'))
    ->save();

    parent::submitForm($form, $form_state);
  }

}
