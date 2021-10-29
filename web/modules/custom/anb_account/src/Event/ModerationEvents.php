<?php

namespace Drupal\anb_account\Event;

/**
 * Defines events for the moderation events.
 */
final class ModerationEvents {

  /**
   * Name of the event firing when the moderator created account.
   *
   * @Event
   */
  const MODERATOR_CREATED_ACCOUNT = 'anb_account.moderator_created_account';

  /**
   * Name of the event firing when the moderator activated account.
   *
   * @Event
   */
  const MODERATOR_ACTIVATED_ACCOUNT = 'anb_account.moderator_activated_account';

  /**
   * Name of the event firing when the moderator disabled account.
   *
   * @Event
   */
  const MODERATOR_DISABLED_ACCOUNT = 'anb_account.moderator_disabled_account';

  /**
   * Name of the event firing when the moderator canceled account.
   *
   * @Event
   */
  const MODERATOR_CANCELED_ACCOUNT = 'anb_account.moderator_canceled_account';

}
