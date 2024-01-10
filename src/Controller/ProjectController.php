<?php

namespace App\Controller;

use App\Entity\Project;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class ProjectController extends AbstractController
{
    #[Route('/project', name: 'app_project')]
    #public function createProject(EntityManagerInterface $entityManager): Response

    #{
        function show(EntityManagerInterface $entityManager): Response
        {
            $id =1;
            $Project = $entityManager->getRepository(Project::class)->find($id);
            if (!$Project) {
                throw $this->createNotFoundException(
                    'No Project found for id '.$id
                );
            }
    
            return new Response('Check out this great Project: '.$Project->getName());

        // $project = new Project();

        // $project->setId(1);
        // $project->setCategory('Economy');
        // $project->setName('Projecta1');
        // $project->setBudget(1000.020);
        // $date = new \DateTime('now', new \DateTimeZone('UTC'));

        // $project->setDate($date);

        // $entityManager->persist($project);

        // $entityManager->flush();

        // return new Response('Saved new project with id '.$project->getId());

    }
}
