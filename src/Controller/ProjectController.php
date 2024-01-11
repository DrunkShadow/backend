<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
class ProjectController extends AbstractController
{
//     

    public function Get(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();

        if (!$projects) {
            // Return a 404 response if no projects are found
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($projects);
    }
}


        