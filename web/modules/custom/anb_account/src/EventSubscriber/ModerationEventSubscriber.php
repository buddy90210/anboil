<?php

namespace Drupal\anb_account\EventSubscriber;

use Drupal\anb_account\Event\ModerationEvent;
use Drupal\user\UserDataInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\anb_account\Event\ModerationEvents;

/**
 * Moderation event subscriber.
 */
class ModerationEventSubscriber implements EventSubscriberInterface {

  /**
   * The user.data storage.
   *
   * @var \Drupal\user\UserDataInterface
   */
  private UserDataInterface $userData;

  public function __construct(UserDataInterface $user_data) {
    $this->userData = $user_data;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ModerationEvents::MODERATOR_CREATED_ACCOUNT => ['accountCreated'],
      ModerationEvents::MODERATOR_ACTIVATED_ACCOUNT => ['accountActivated'],
      ModerationEvents::MODERATOR_DISABLED_ACCOUNT => ['accountDisabled'],
      ModerationEvents::MODERATOR_CANCELED_ACCOUNT => ['accountCanceled'],
    ];
  }

  /**
   * Fires moderator created account event.
   *
   * @param \Drupal\anb_account\Event\ModerationEvent $event
   *   The event object.
   */
  public function accountCreated(ModerationEvent $event) {
    $account = $event->getEntity();
    $moderator_id = $event->getModeratorId();

    $moderation_log = $this->userData->get('anb_account', $moderator_id, 'moderation_log') ?? [];
    $moderation_log[] = [
      'created' => date('U'),
      'message' => 'Модератор создал аккаунт ' . $account->label(),
    ];

    $this->userData->set('anb_account', $moderator_id, 'moderation_log', $moderation_log);
  }

  /**
   * Fires moderator activated account event.
   *
   * @param \Drupal\anb_account\Event\ModerationEvent $event
   *   The event object.
   */
  public function accountActivated(ModerationEvent $event) {
    $account = $event->getEntity();
    $moderator_id = $event->getModeratorId();

    $moderation_log = $this->userData->get('anb_account', $moderator_id, 'moderation_log') ?? [];
    $moderation_log[] = [
      'created' => date('U'),
      'message' => 'Модератор активировал аккаунт ' . $account->label(),
    ];

    $this->userData->set('anb_account', $moderator_id, 'moderation_log', $moderation_log);
  }

  /**
   * Fires moderator disabled account event.
   *
   * @param \Drupal\anb_account\Event\ModerationEvent $event
   *   The event object.
   */
  public function accountDisabled(ModerationEvent $event) {
    $account = $event->getEntity();
    $moderator_id = $event->getModeratorId();

    $moderation_log = $this->userData->get('anb_account', $moderator_id, 'moderation_log') ?? [];
    $moderation_log[] = [
      'created' => date('U'),
      'message' => 'Модератор отключил аккаунт ' . $account->label(),
    ];

    $this->userData->set('anb_account', $moderator_id, 'moderation_log', $moderation_log);
  }

  /**
   * Fires moderator canceled account event.
   *
   * @param \Drupal\anb_account\Event\ModerationEvent $event
   *   The event object.
   */
  public function accountCanceled(ModerationEvent $event) {
    $account = $event->getEntity();
    $moderator_id = $event->getModeratorId();

    $moderation_log = $this->userData->get('anb_account', $moderator_id, 'moderation_log') ?? [];
    $moderation_log[] = [
      'created' => date('U'),
      'message' => 'Модератор удалил аккаунт ' . $account->label(),
    ];

    $this->userData->set('anb_account', $moderator_id, 'moderation_log', $moderation_log);
  }

}
