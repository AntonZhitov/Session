<?php
// src/Controller/TaskController.php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/tasks")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="task_index", methods={"GET"})
     */
    public function index(TaskRepository $taskRepository, SerializerInterface $serializer): JsonResponse
    {
        $tasks = $taskRepository->findAll();
        $data = $serializer->serialize($tasks, 'json', ['groups' => 'task:read']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     */
    public function show(Task $task, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($task, 'json', ['groups' => 'task:read']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/", name="task_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->getContent();
        $task = $serializer->deserialize($data, Task::class, 'json', ['groups' => 'task:write']);

        $em->persist($task);
        $em->flush();

        $responseData = $serializer->serialize($task, 'json', ['groups' => 'task:read']);
        return new JsonResponse($responseData, Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/{id}", name="task_update", methods={"PUT"})
     */
    public function update(Task $task, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->getContent();
        $serializer->deserialize($data, Task::class, 'json', [
            'groups' => 'task:write',
            'object_to_populate' => $task
        ]);

        $em->flush();

        $responseData = $serializer->serialize($task, 'json', ['groups' => 'task:read']);
        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/{id}", name="task_delete", methods={"DELETE"})
     */
    public function delete(Task $task, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($task);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}