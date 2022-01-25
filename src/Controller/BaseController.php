<?php

namespace App\Controller;

use App\Util\Enums;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
  /**
   * Base method to handle new/created/edit/updated actions from controller
   * 
   * @param string|array $resp
   */
  function handleSaveResource($resp, string $viewName, string $formName): Response
  {
    if (is_array($resp)) {
      $code = $resp[0];
      $params = $resp[1] ?? [];
    } else if (is_string($resp)) {
      $code = $resp;
      $params = [];
    }

    if ($code === Enums::RESOURCE_SAVED) {
      return $this->redirectToRoute($viewName, [], Response::HTTP_SEE_OTHER);
    } else if ($code === Enums::SAVE_RESOURCE) {
      return $this->renderForm($formName, $params);
    }
  }
}
