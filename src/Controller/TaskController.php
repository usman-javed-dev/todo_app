<?php

namespace App\Controller;

use App\Entity\Task;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/task")
 */
class TaskController extends BaseController
{
    private $taskSrv;

    function __construct(TaskService $taskSrv)
    {
        $this->taskSrv = $taskSrv;
    }

    /**
     * @Route("/", name="task_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $this->taskSrv->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $resp = $this->taskSrv->new($request);
        return $this->handleSaveResource($resp, 'task_index', 'task/new.html.twig');
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
        $resp = $this->taskSrv->edit($request, $task);
        return $this->handleSaveResource($resp, 'task_index', 'task/edit.html.twig');
    }

    /**
     * @Route("/{id}", name="task_delete", methods={"POST"})
     */
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $this->taskSrv->delete($task);
        }

        return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/attachment", name="task_attachment_delete", methods={"POST"})
     */
    public function deleteAttachment(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete-attachment' . $task->getId(), $request->request->get('_token'))) {
            $this->taskSrv->deleteAttachment($task);
        }

        return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    }
}
