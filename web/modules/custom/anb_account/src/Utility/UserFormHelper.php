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
  public static function alterForm(&$form, FormStateInterface $form_state) {
    // Make INN field non-editable.
    if (isset($form['field_inn'])) {
      $form['field_inn']['widget'][0]['value']['#attributes']['disabled'] = 'disabled';
    }
  }

}
