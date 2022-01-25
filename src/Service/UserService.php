<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
  private $userRepo;
  private $utilSrv;

  function __construct(UserRepository $userRepo, UtilService $utilSrv)
  {
    $this->userRepo = $userRepo;
    $this->utilSrv = $utilSrv;
  }

  function createBulk(int $num)
  {
    $i = 1;

    while ($i <= $num) {
      $user = new User();

      $userName = $this->utilSrv->generateRandomString();

      $user->setName($userName);
      $user->setIdle(false);

      $this->userRepo->persist($user);

      $i++;
    }

    $this->userRepo->flush();
  }
}
