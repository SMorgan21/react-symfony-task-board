<?php

namespace App\Controller;

use App\Entity\Column;
use App\Entity\Project;
use App\Repository\ColumnRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/columns')]
final class ColumnController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ColumnRepository $columnRepository):
    JsonResponse
    {
        return $this->json($columnRepository->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $data = $request->toArray();
        $column = new Column();
        $project = $entityManager->getRepository(Project::class)->find($data['project']);
        $column->setName($data['name']);
        $column->setPosition($data['position']);
        $column->setProject($project);

        $errors = $validator->validate($column);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] =  $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }
        try {
            $entityManager->persist($column);
            $entityManager->flush();
            return $this->json($column, 201);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Column $column): JsonResponse
    {
        return $this->json($column);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Column $column, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $data = $request->toArray();

        $project = $entityManager->getRepository(Project::class)->find($data['project']);
        $column->setName($data['name']);
        $column->setPosition($data['position']);
        $column->setProject($project);

        $errors = $validator->validate($column);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] =  $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }
        try {
            $entityManager->flush();
            return $this->json($column);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Column $column, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($column);
            $entityManager->flush();
            return $this->json('column ' . $column->getId() . ' removed', 204);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

}
