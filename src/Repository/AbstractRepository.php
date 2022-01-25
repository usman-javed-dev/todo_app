<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

abstract class AbstractRepository extends EntityRepository
{
  public function persist($entity, $andFlush = true)
  {
    $this->getEntityManager()->persist($entity);
    if ($andFlush) {
      $this->getEntityManager()->flush();
    }
  }

  public function flush()
  {
    $this->getEntityManager()->flush();
  }

  public function remove($entity, $andFlush = true)
  {
    $this->getEntityManager()->remove($entity);
    if ($andFlush) {
      $this->getEntityManager()->flush();
    }
  }
}
