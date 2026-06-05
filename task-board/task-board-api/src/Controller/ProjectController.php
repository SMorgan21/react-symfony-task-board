<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/projects')]
final class ProjectController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository):
    JsonResponse
    {
        return $this->json($projectRepository->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $data = $request->toArray();
        $project = new Project();

        $project->setName($data['name']);
        $project->setDescription($data['description']);
        $project->setCreatedAt(new DateTimeImmutable());

        $errors = $validator->validate($project);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] =  $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        try {
            $entityManager->persist($project);
            $entityManager->flush();
            return $this->json($project, 201);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Project $project): JsonResponse
    {
        return $this->json($project);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Project $project, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->toArray();

        $project->setName($data['name']);
        $project->setDescription($data['description']);

        $errors = $validator->validate($project);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] =  $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }
        try {
            $entityManager->flush();
            return $this->json($project);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Project $project, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($project);
            $entityManager->flush();
            return $this->json('project ' . $project->getId() . ' removed', 204);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }


}
