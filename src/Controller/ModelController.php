<?php

namespace App\Controller;

use App\Entity\Models;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/models', name: 'Models')]
class ModelController extends AbstractController
{
    #[Route('', name: 'listModels', methods: ['GET'])]
    public function GetModels(EntityManagerInterface $entityManager): Response
    {
        $models = $entityManager->getRepository(Models::class)->findAll();

        if (!$models) {
            return $this->json(['error' => 'No projects found'], 404);
        }
        return $this->json($models);
    }

    #[Route('/{modelId}', name: 'getModelById', methods: ['GET'],)]
    public function GetProjectById(EntityManagerInterface $entityManager, string $modelId): Response
    {
        $model = $entityManager->getRepository(Models::class)->find($modelId);

        if (!$model) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($model);
    }

    #[Route('/{modelId}', name: 'putModelById', methods: ['PUT'],)]
    public function PutModel(EntityManagerInterface $entityManager, Request $request, string $modelId): Response
    {
        $model = $entityManager->getRepository(Models::class)->find($modelId);
    
        if (!$model) {
            return $this->json(['error' => 'Model not found'], 404);
        }
    
        $data = json_decode($request->getContent(), true);
        $model->setText($data['text']); 
    
        $entityManager->flush();
    
        return $this->json(['message' => 'Model updated successfully']);
    }

}
