<?php

namespace App\Controller;

use App\Entity\Task;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Task CRUD Controller
 * 
 * @Route("/task")
 */
class TaskController extends AbstractController
{
    private $taskService;

    function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @Route("/", name="task_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $this->taskService->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->formFactory->create(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->new($form, $task);

            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->formFactory->create(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->edit($form, $task);

            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="task_delete", methods={"POST"})
     */
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $this->taskService->delete($task);
        }

        return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/attachment", name="task_attachment_delete", methods={"POST"})
     */
    public function deleteAttachment(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete-attachment' . $task->getId(), $request->request->get('_token'))) {
            $this->taskService->deleteAttachment($task);
        }

        return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    }
}
