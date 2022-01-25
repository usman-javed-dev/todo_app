<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository extends AbstractRepository
{
  public function addUser(string $name, bool $idle, $andFlush = true)
  {
    $user = new User();

    $user->setName($name);
    $user->setIdle($idle);

    $this->persist($user, $andFlush);
  }
}
