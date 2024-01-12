<?php

namespace App\Controller;

use App\Entity\Worker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
class WorkerController extends AbstractController
{
    public function GetWorkers(EntityManagerInterface $entityManager): Response
    {
        $worker = $entityManager->getRepository(Worker::class)->findAll();

        if (!$worker) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($worker);
    }
}


        
