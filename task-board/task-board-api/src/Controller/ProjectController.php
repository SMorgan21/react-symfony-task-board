<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/projects')]
final class ProjectController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository):
    JsonResponse
    {
        return $this->json($projectRepository->findAll());
    }
}
