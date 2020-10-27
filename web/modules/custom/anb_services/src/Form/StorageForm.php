<?php


namespace Drupal\anb_services\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;

/**
 * Storage form calculator.
 */
class StorageForm extends FormBase {

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'services_storage_form';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#attributes']['class'][] = 'calculator-form';

    $form['value'] = [
      '#type' => 'number',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Объём (тонн)'),
        'class' => ['value-form-field']
      ],
    ];

    $form['time'] = [
      '#type' => 'number',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Срок (дней)'),
        'class' => ['time-form-field'],
      ],
    ];

    $form['results'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['results-items', 'visually-hidden'],
        'id' => 'storage_form_results',
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
        'wrapper' => 'storage_form_results'
      ],
      '#states' => [
        'disabled' => [
          ':input[name="value"]' => ['value' => ''],
        ],
        'disabled' => [
          ':input[name="time"]' => ['value' => ''],
        ]
      ],
    ];

    return $form;
  }

  /**
   * Ajax callback for form submit.
   */
  public function ajaxSubmitForm(&$form, $form_state) {
    // Get service prices from configuration.
    $config = \Drupal::config('anb_services.settings_form');
    $storage_price = $config->get('storage_price_default');
    $storage_price_extra = $config->get('storage_price_extra');

    // Calculate price.
    $value = intval($form_state->getValue('value'));
    $time = intval($form_state->getValue('time'));

    $calculated_price = $value * $storage_price;

    $form['results']['#attributes']['class'][] = 'has-values';

    $form['results']['calculated_price'] = [
      '#type' => 'markup',
      '#markup' => '<div class="row"><p class="title">Приблизительная <br/>стоимость</p><p class="value">' . $calculated_price . '&nbsp;₽</p></div>'
    ];

    if ($time > 30) {
      $form['results']['extra_price'] = [
        '#type' => 'markup',
        '#markup' => '<div class="row"><p class="title">Стоимость при хранении <br/>более 30 дней (за тонну)</p><p class="value">' . $storage_price_extra . '&nbsp;₽</p></div>'
      ];
    }

    return $form['results'];
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    return $form;
  }

}
