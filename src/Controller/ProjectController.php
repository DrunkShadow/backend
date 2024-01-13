<?php

namespace App\Controller;

// src\Controller\ProjectController.php

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

class ProjectController extends AbstractController
{   

    public function GetProjects(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();

        if (!$projects) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($projects);
    }
}