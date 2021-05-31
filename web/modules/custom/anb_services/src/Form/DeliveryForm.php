<?php


namespace Drupal\anb_services\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\Core\Ajax\InsertCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Delivery form calculator.
 */
class DeliveryForm extends FormBase {

  /**
   * The current user.
   *
   * @var \Drupal\anb_avtodispetcher\AvtodispetcherManager
   */
  protected $manager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->manager = $container->get('anb_avtodispetcher.manager');

    return $instance;
  }

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
      '#suffix' => '<ul class="city-tips city-tips--from"></ul>',
      '#ajax' => [
        'callback' => '::getCityTips',
        'event' => 'input',
      ],
    ];

    $form['point_to'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => ['point-form-field', 'calc-result-field'],
        'placeholder' => 'Куда',
      ],
      '#required' => TRUE,
      '#suffix' => '<ul class="city-tips city-tips--to"></ul>',
      '#ajax' => [
        'callback' => '::getCityTips',
        'event' => 'input',
      ],
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
    
    $form['disclamer'] = [
      '#type' => 'markup',
      '#markup' => '<p class="delivery-disclamer">* Минимальное расстояние для расчета от 70 км. До 70 км. рассчитывается индивидуально.</p>'
    ];

    return $form;
  }

  /**
   * Get city tips for autocomplete form field.
   */
  public function getCityTips(&$form, $form_state) {
    $ajax_response = new AjaxResponse();
    $ajax_response->addCommand(new ReplaceCommand('.city-tips', ''));

    // Get triggering element value.
    $element = $form_state->getTriggeringElement()['#name'];
    $city = $form_state->getValue($element);

    // Get tips from API.
    $tips = $this->manager->getCityTips($city);
    $selector = 'input[data-drupal-selector="edit-' . Html::cleanCssIdentifier($element) . '"]';
    $parent_selector = '.form-item-' . Html::cleanCssIdentifier($element);

    $ajax_response->addCommand(new InvokeCommand($selector, 'addClass', ['has-value']));
    $ajax_response->addCommand(new AppendCommand($parent_selector, $this->renderCityTips($tips)));

    return $ajax_response;
  }

  /**
   * Returns city tips markup.
   * @param array $tips
   */
  protected function renderCityTips($tips) {
    $output = [
      '#type' => 'container',
      '#attributes' => ['class' => ['city-tips']],
    ];

    if (is_null($tips)) {
      $output['#attributes']['class'][] = 'is-empty';

      $output['empty'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => 'Не найдено',
      ];
    }

    foreach ($tips as $key => $tip) {
      $output[$key] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $tip,
        '#attributes' => ['class' => ['city-tip']],
      ];
    }

    return $output;
  }

  /**
   * Ajax callback for form submit.
   */
  public function ajaxSubmitForm(&$form, $form_state) {
    // Calculate price.
    $point_from = $form_state->getValue('point_from');
    $point_to = $form_state->getValue('point_to');

    $empty_message = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => 'Не найдена информация по указанным городам',
      '#attributes' => ['class' => ['not-found']],
    ];

    if (strlen($point_from) < 3 || strlen($point_to) < 3) {
      $form['results']['empty'] = $empty_message;
      return $form['results'];
    }

    // Get route info from api.
    $route_info = $this->manager->getRoute($point_from, $point_to);

    if (is_null($route_info)) {
      $form['results']['empty'] = $empty_message;
      return $form['results'];
    }

    $form['results']['#attributes']['class'][] = 'has-values';

    /*$form['results']['route_time'] = [
      '#type' => 'markup',
      '#markup' => '<div class="row"><p class="title">Время <br/>на доставку (в часах)</p><p class="value">' . $route_info['time'] . '</p></div>'
    ];*/

    $form['results']['extra_price'] = [
      '#type' => 'markup',
      '#markup' => '<div class="row"><p class="title">Приблизительная <br/>стоимость</p><p class="value">' . $route_info['price'] . '&nbsp;<i>₽</i></p></div>'
    ];
    
    $form['results']['result_disclamer'] = [
      '#type' => 'markup',
      '#markup' => '<div class="deliveryresult-disclamer"><p>Сроки и условия доставки нефтепродуктов рассчитываются индивидуально.</p><p>Номер телефона отдела логистики: <a href="tel:89282410202">+7 928 241-02-02</a></p></div>'
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
