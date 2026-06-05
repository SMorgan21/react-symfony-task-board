<?php

namespace App\Controller;

use App\Entity\Column;
use App\Entity\Task;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/tasks')]
final class TaskController extends AbstractController
{

    #[Route('', methods: ['GET'])]
    public function index(TaskRepository $taskRepository):
    JsonResponse
    {
        return $this->json($taskRepository->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $data = $request->toArray();
        $task = new Task();

        $column = $entityManager->getRepository(Column::class)->find($data['taskColumn']);
        $task->setTitle($data['title']);
        $task->setPosition($data['position']);
        $task->setTaskColumn($column);
        $task->setCreatedAt(new DateTimeImmutable());

        $errors = $validator->validate($task);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] =  $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }
        try {
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->json($task, 201);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }

    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Task $task): JsonResponse
    {
        return $this->json($task);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Task $task, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $data = $request->toArray();

        $column = $entityManager->getRepository(Column::class)->find($data['taskColumn']);
        $task->setTitle($data['title']);
        $task->setPosition($data['position']);
        $task->setTaskColumn($column);

        $errors = $validator->validate($task);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] =  $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }
        try {
            $entityManager->flush();
            return $this->json($task);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Task $task, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($task);
            $entityManager->flush();
            return $this->json('task ' . $task->getId() . ' removed', 204);
        }catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

}
