<?php

namespace App\Service;

use App\Repository\UserRepository;

/**
 * User Service
 */
class UserService
{
  private $userRepo;
  private $utilService;

  function __construct(UserRepository $userRepo, UtilService $utilService)
  {
    $this->userRepo = $userRepo;
    $this->utilService = $utilService;
  }

  function createBulk(int $num)
  {
    $i = 1;

    while ($i <= $num) {
      $userName = $this->utilService->generateRandomString();
      $this->userRepo->addUser($userName, false, false);

      $i++;
    }

    $this->userRepo->flush();
  }
}
