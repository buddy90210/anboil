<?php

namespace Drupal\anb_account\Utility;

use Drupal\Core\Form\FormStateInterface;

/**
 * Helper class to alter user edit form.
 */
class UserFormHelper {

  /**
   * Alter user edit form.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   */
  public function alterForm(array &$form, FormStateInterface $form_state) {
    $current_user = \Drupal::currentUser();
    $current_user_roles = $current_user->getRoles();
    $skip_roles = [
      'administrator',
      'moderator',
    ];

    if (!array_intersect($skip_roles, $current_user_roles)) {
      // Make INN field non-editable.
      if (isset($form['field_inn'])) {
        $form['field_inn']['widget'][0]['value']['#attributes']['disabled'] = 'disabled';
      }
    }

    // Show moderation log for accounts with moderator role.
    $account = $form_state->getFormObject()->getEntity();
    if (in_array('moderator', $account->getRoles())) {
      if ($current_user->hasPermission('administer site configuration')) {
        $form['moderation'] = [
          '#type' => 'details',
          '#title' => 'Модерация',
          '#weight' => 99,
        ];
        $form['moderation']['moderation_log'] = [
          '#type' => 'markup',
          '#markup' => 'Записи не найдены',
        ];
        $moderation_log = \Drupal::service('user.data')->get('anb_account', $account->id(), 'moderation_log');
        if (!empty($moderation_log)) {
          $form['moderation']['moderation_log'] = [
            '#type' => 'table',
            '#header' => [
              'Время', 'Событие',
            ],
          ];
          $moderation_log = array_reverse($moderation_log);
          foreach ($moderation_log as $key => $record) {
            $form['moderation']['moderation_log'][$key]['created'] = [
              '#type' => '#markup',
              '#markup' => date('d.m.Y H:i', $record['created']),
            ];
            $form['moderation']['moderation_log'][$key]['message'] = [
              '#type' => '#markup',
              '#markup' => $record['message'],
            ];
          }
        }
      }
    }
  }

}
