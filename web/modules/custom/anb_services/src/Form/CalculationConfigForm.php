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
      '#title' => 'Стоимость хранения ГСМ (руб./тонна)',
      '#default_value' => $config->get('storage_price_default'),
    ];

    $form['storage_price_extra'] = [
      '#type' => 'textfield',
      '#title' => 'Доп. коэффициент за 31-60 дни',
      '#default_value' => 25,
    ];

    $form['storage_price_extra_90'] = [
      '#type' => 'textfield',
      '#title' => 'Доп. коэффициент за 61-90 дни',
      '#default_value' => 5,
    ];

    $form['storage_price_overlimits_message'] = [
      '#type' => 'textfield',
      '#title' => 'Сообщение, если срок > 90',
      '#default_value' => 'Оговаривается отдельно',
    ];

    $form['storage_price_transshipment'] = [
      '#type' => 'textfield',
      '#title' => 'Стоимость перевалки, за единицу объёма (руб./тонна)',
      '#default_value' => 450,
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
    ->set('storage_price_extra_90', $form_state->getValue('storage_price_extra_90'))
    ->set('storage_price_overlimits_message', $form_state->getValue('storage_price_overlimits_message'))
    ->set('storage_price_transshipment', $form_state->getValue('storage_price_transshipment'))
    ->save();

    parent::submitForm($form, $form_state);
  }

}
