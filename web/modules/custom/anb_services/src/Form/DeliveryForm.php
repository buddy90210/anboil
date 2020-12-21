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
    $form['#attributes']['class'][] = 'calculator-form';

    $form['point_from'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['point-form-field', 'calc-result-field'],
        'placeholder' => 'Откуда',
      ],
      '#required' => TRUE,
    ];

    $form['point_to'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['point-form-field', 'calc-result-field'],
        'placeholder' => 'Куда',
      ],
      '#required' => TRUE,
    ];

    $form['results'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['results-items', 'visually-hidden'],
        'id' => 'delivery_form_results',
      ],
      '#weight' => 300,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Рассчитать',
      '#attributes' => [
        'class' => ['button--flat'],
      ],
      '#ajax' => [
        'callback' => '::ajaxSubmitForm',
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
        ],
        'wrapper' => 'delivery_form_results'
      ],
      '#states' => [
        'disabled' => [
          ':input[name="point_form"]' => ['value' => ''],
        ],
        'disabled' => [
          ':input[name="point_to"]' => ['value' => ''],
        ],
      ],
    ];

    return $form;
  }

  /**
   * Ajax callback for form submit.
   */
  public function ajaxSubmitForm(&$form, $form_state) {
    // Calculate price.
    $point_from = $form_state->getValue('point_from');
    $point_to = $form_state->getValue('point_to');

    $form['results']['#attributes']['class'][] = 'has-values';

    $form['results']['calculated_price'] = [
      '#type' => 'markup',
      '#markup' => '<div class="row"><p class="title">Откуда</p><p class="value">' . $point_from . '</p></div>'
    ];

    $form['results']['extra_price'] = [
      '#type' => 'markup',
      '#markup' => '<div class="row"><p class="title">Куда</p><p class="value">' . $point_to . '</p></div>'
    ];

    return $form['results'];
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    return $form;
  }

}
