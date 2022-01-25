<?php

namespace App\Service;

use App\Util\Enums;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TaskService
{
  private $mediaSrv;
  private $taskRepo;
  private $formFactory;

  function __construct(FormFactoryInterface $formFactory, MediaService $mediaSrv, TaskRepository $taskRepo)
  {
    $this->mediaSrv = $mediaSrv;
    $this->taskRepo = $taskRepo;
    $this->formFactory = $formFactory;
  }

  public function findAll()
  {
    return $this->taskRepo->findAll();
  }

  public function new(Request $request)
  {
    $task = new Task();
    $form = $this->formFactory->create(TaskType::class, $task);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->handleAttachment($form, $task);

      $this->taskRepo->persist($task);

      return [Enums::RESOURCE_SAVED, []];
    }

    return [Enums::SAVE_RESOURCE, [
      'task' => $task,
      'form' => $form,
    ]];
  }

  public function edit(Request $request, Task $task)
  {
    $form = $this->formFactory->create(TaskType::class, $task);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->handleAttachment($form, $task);

      $this->taskRepo->flush();

      return [Enums::RESOURCE_SAVED, []];
    }

    return [Enums::SAVE_RESOURCE, [
      'task' => $task,
      'form' => $form,
    ]];
  }

  private function handleAttachment($form, Task $task)
  {
    /** @var UploadedFile $attachmentFile */
    $attachmentFile = $form->get('attachment')->getData();
    if ($attachmentFile) {
      $attachmentFileName = $this->mediaSrv->upload($attachmentFile);
      $task->setAttachmentFileName($attachmentFileName);
    }
  }

  public function delete(Task $task)
  {
    // Remove file first
    $this->mediaSrv->remove($task->getAttachmentFileName());

    $this->taskRepo->remove($task);
  }
  public function deleteAttachment(Task $task)
  {
    // Remove file first
    $this->mediaSrv->remove($task->getAttachmentFileName());

    $task->setAttachmentFileName(null);

    $this->taskRepo->flush();
  }
}
