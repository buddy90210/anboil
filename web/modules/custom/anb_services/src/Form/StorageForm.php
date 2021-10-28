<?php


namespace Drupal\anb_services\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use function extract;
use function intval;

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
        'class' => ['value-form-field', 'calc-result-field']
      ],
    ];

    $form['time'] = [
      '#type' => 'number',
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Срок (дней)'),
        'class' => ['time-form-field', 'calc-result-field'],
      ],
    ];

    $form['calctype'] = [
      '#type' => 'textfield',
      '#required' => FALSE,
      '#attributes' => [
        'class' => ['calctype', 'hidden', 'calc-result-field'],
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
    $calcType = $form_state->getValue('calctype');

    // Check calculation type - the "perevalka" or the "khraneniye"
    if ($calcType === "pereval") {
      $calculation_result = $this->calculateTransshipment($form, $form_state);
    } else {
      $calculation_result = $this->calculateStorage($form, $form_state);
    }

    // Build form response
    $form['results']['#attributes']['class'][] = 'has-values';

    // Regular markup
    $form['results']['calculated_price'] = [
      '#type' => 'markup',
      '#markup' => ($calculation_result['#markup'] ? $calculation_result['#markup'] : '')
    ];

    // Extra markup
    if (@$calculation_result['#extra_markup']){
      $form['results']['extra_price'] = [
        '#type' => 'markup',
        '#markup' => $calculation_result['#extra_markup']
      ];
    }

    return $form['results'];
  }

  public function calculateStorage(&$form, &$form_state){
    // Get service prices from configuration.
    $config = \Drupal::config('anb_services.settings_form');
    $storage_price = $config->get('storage_price_default');
    $storage_price_extra = $config->get('storage_price_extra');
    $storage_price_extra_90 = $config->get('storage_price_extra_90');
    $storage_price_overlimits_message = $config->get('storage_price_overlimits_message');

    // Get form data
    $value = intval($form_state->getValue('value'));
    $time = intval($form_state->getValue('time'));

    // Calculate price.
    // days <= 30 : just volume
    // days > 30 <= 60 : above + coefficient * days over 30
    // days > 60 <= 90 : above + coefficient_2 * days over 60
    $calculated_price = $value * $storage_price;

    if ( ($time > 30) && ($time <= 60) ){
      $calculated_price = $calculated_price + (\intval($storage_price_extra) * $value * ($time - 30));
    } elseif ( ($time > 60) && ($time <= 90) ){
      $calculated_price = $calculated_price + (\intval($storage_price_extra) * $value * ($time - 30));
      $calculated_price = $calculated_price + (\intval($storage_price_extra_90) * $value * ($time - 60));
    } elseif ($time > 90){
      $calculated_price = false; // flag to send text instead of number-value
    }

    // Return price, markup and extra-markup
    $result_arr = array(
      'calculated_price' => $calculated_price,
      '#markup' => '',
      '#extra_markup' => '',
    );

    // Build response
    // Updated to delete rouble-icon. If !$calculated_price, will send annotation-text instead of price value
    if ($calculated_price){
      $result_arr['#markup'] .= '<div class="row"><p class="title">Приблизительная <br/>стоимость</p><p class="value">' . $calculated_price . '&nbsp;<i>₽</i></p></div>';
    } else {
      $result_arr['#markup'] .= '<div class="row"><p class="title">Приблизительная <br/>стоимость</p><p class="value">' . $storage_price_overlimits_message . '</p></div>';
    }

    // Additional price comment
    if ( ($time > 30) && ($time <= 90) ) {
      $result_arr['#extra_markup'] .= '<div class="row extra"><p class="title">Включая надбавку за срок хранения <br/>более 30 дней (в сутки)</p>';
      $result_arr['#extra_markup'] .= '<p class="value">С 31го дня: '.($storage_price_extra * $value).'&nbsp;<i>₽</i>/<span class="lower">сут</span></p>';

      if ($time > 60){
        $result_arr['#extra_markup'] .= '<p class="value">С 61го дня: '.($storage_price_extra * $value + $storage_price_extra_90 * $value).'&nbsp;<i>₽</i>/<span class="lower">сут</span></p>';
      }

      $result_arr['#extra_markup'] .= '</div>';
    }

    // Return result
    return $result_arr;
  }

  public function calculateTransshipment(&$form, &$form_state){
    // Get service prices from configuration.
    $config = \Drupal::config('anb_services.settings_form');
    $storage_price_transshipment = $config->get('storage_price_transshipment');

    // Get form data
    $value = intval($form_state->getValue('value'));

    // Return price, markup and extra-markup
    $result_arr = array(
      'calculated_price' => '',
      '#markup' => '',
      '#extra_markup' => '',
    );

    // Check if $value is empty
    // Due to custom submit js-handler on button, we must intercept empty value of time-field on backend
    if (!$value){
      $result_arr['#markup'] .= '<div class="row"><p class="title">Ошибка</p><p class="value">Вы не указали объём!</p></div>';
    } else {
      // Calculate price
      $calculated_price = $value * $storage_price_transshipment;
      $result_arr['calculated_price'] = $calculated_price;

      // Build markup
      $result_arr['#markup'] .= '<div class="row"><p class="title">Приблизительная <br/>стоимость</p><p class="value">' . $calculated_price . '&nbsp;<i>₽</i></p></div>';
    }

    // Returning
    return $result_arr;
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    return $form;
  }

}
