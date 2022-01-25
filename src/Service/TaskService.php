<?php

namespace App\Service;

use App\Util\Enums;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Task Service
 */
class TaskService
{
  private $mediaService;
  private $taskRepo;
  private $formFactory;

  function __construct(FormFactoryInterface $formFactory, MediaService $mediaService, TaskRepository $taskRepo)
  {
    $this->mediaService = $mediaService;
    $this->taskRepo = $taskRepo;
    $this->formFactory = $formFactory;
  }

  public function findAll()
  {
    return $this->taskRepo->findAll();
  }

  public function new($form, Task $task)
  {
    $this->uploadAttachment($form, $task);

    $this->taskRepo->persist($task);
  }

  public function edit($form, Task $task)
  {
    $this->uploadAttachment($form, $task);

    $this->taskRepo->flush();
  }

  private function uploadAttachment($form, Task $task)
  {
    /** @var UploadedFile $attachmentFile */
    $attachmentFile = $form->get('attachment')->getData();
    if ($attachmentFile) {
      $attachmentFileName = $this->mediaService->upload($attachmentFile);
      $task->setAttachmentFileName($attachmentFileName);
    }
  }

  public function delete(Task $task)
  {
    // Remove file first
    $this->mediaService->remove($task->getAttachmentFileName());

    $this->taskRepo->remove($task);
  }

  public function deleteAttachment(Task $task)
  {
    // Remove file first
    $this->mediaService->remove($task->getAttachmentFileName());

    $task->setAttachmentFileName(null);

    $this->taskRepo->flush();
  }
}
