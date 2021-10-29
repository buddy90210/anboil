<?php

namespace Drupal\anb_account\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Drupal\Core\Entity\EntityInterface;

/**
 * Event handling moderation operation.
 */
class ModerationEvent extends Event {

  /**
   * The user entity.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  private EntityInterface $entity;

  /**
   * The moderator ID.
   *
   * @var string
   */
  private string $moderatorID;

  /**
   * ModeratorCreatedAccount constructor.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The user account entity.
   * @param string $moderator_id
   *   The moderator ID.
   * @param string $operation
   */
  public function __construct(EntityInterface $entity, string $moderator_id) {
    $this->entity = $entity;
    $this->moderatorID = $moderator_id;
  }

  /**
   * Get the commerce product entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The user account entity.
   */
  public function getEntity() {
    return $this->entity;
  }

  /**
   * Get the moderator ID.
   *
   * @return string
   *   The moderator ID.
   */
  public function getModeratorId() {
    return $this->moderatorID;
  }

}
