<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Projects')]
#[Route('/projects', name: 'project_')]
class ProjectController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function GetProjects(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();

        if (!$projects) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($projects);
    }

    #[Route('/{projectId}', name: 'getProjectById', methods: ['GET'],)]
    public function GetProjectById(EntityManagerInterface $entityManager, string $projectId): Response
    {
        $project = $entityManager->getRepository(Project::class)->find($projectId);

        if (!$project) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($project);
    }
}
