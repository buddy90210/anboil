<?php


namespace Drupal\anb_services\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Delivery form calculator.
 */
class DeliveryForm extends \Drupal\Core\Form\FormBase {

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'services_delivery_form';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user_mail'] = [
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage('Delivery form has been sent');
  }

}
