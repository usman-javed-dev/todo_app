<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * User doctrine event listener
 */
class UserEventListener
{
  public function prePersist(LifecycleEventArgs $args): void
  {
    $entity = $args->getObject();

    if (!$entity instanceof User) {
      return;
    }

    $entityManager = $args->getObjectManager();

    $entity->setIdle(1);

    $entityManager->flush();
  }
}
