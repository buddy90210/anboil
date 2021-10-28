<?php

namespace Drupal\anb_account\Form\Admin;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides account settings form.
 */
class AccountSettingsForm extends ConfigFormBase {

  /**
   * Provides module settings name.
   */
  const SETTINGS = 'anb_account.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'anb_account_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['api_url'] = [
      '#type' => 'textfield',
      '#title' => 'API url',
      '#default_value' => $config->get('api_url'),
    ];

    $form['api_token'] = [
      '#type' => 'textfield',
      '#title' => 'API токен',
      '#default_value' => $config->get('api_token'),
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable(static::SETTINGS);

    $config_fields = [
      'api_url',
      'api_token',
    ];

    foreach ($config_fields as $config_field) {
      $config->set($config_field, $form_state->getValue($config_field));
    };

    $config->save();

    $this->messenger()->addStatus('Настройки сохранены.');
  }

}
