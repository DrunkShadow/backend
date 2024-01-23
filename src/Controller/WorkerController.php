<?php

namespace App\Controller;

use App\DTO\WorkerDTO;
use App\Entity\Worker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Workers')]
#[Route('/workers', name: 'workers_')]

class WorkerController extends AbstractController
{
    #[Route('/', name: 'getAllWorkers', methods: 'GET')]
    public function GetWorkers(EntityManagerInterface $entityManager): Response
    {
        $workers = $entityManager->getRepository(Worker::class)->findAll();
        $workerDTOs = array_map(fn($worker) => new WorkerDTO($worker), $workers);
        return $this->json($workerDTOs);
    }
    #[Route('/{workerId}', name: 'getWorkerById', methods: ['GET'],)]
    public function GetProjectById(EntityManagerInterface $entityManager, string $workerId): Response
    {
        $worker = $entityManager->getRepository(Worker::class)->find($workerId);

        if (!$worker) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($worker);
    }
}


        
