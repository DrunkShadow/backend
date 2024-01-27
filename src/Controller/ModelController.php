<?php

namespace App\Controller;

use App\Entity\Models;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Models')]
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




    #[Route('/{modelId}', name: 'DeleteModel', methods: ['DELETE'],)]
    public function DeleteModel(EntityManagerInterface $entityManager, Request $request, string $modelId): Response
    {
        $model = $entityManager->getRepository(Models::class)->find($modelId);

        if (!$model) {
            return $this->json(['error' => 'Model not found'], 404);
        }

        $entityManager->remove($model);
        $entityManager->flush();

        return $this->json(['message' => 'Model deleted successfully']);
    }

    #[Route('', name: 'createModel', methods: ['POST'], format: 'json')]
    public function createModel(EntityManagerInterface $entityManager, Request $request): Response
    {
        $newModel = new Models();
        $data = json_decode($request->getContent(), true);
        $newModel->setId($data['id']);
        $newModel->setText($data['text']);
        $newModel->setConcernsProject($data['concernsProject']);
        $newModel->setConcernsWorker($data['concernsWorker']);
        $newModel->setConcernsEmail($data['concernsEmail']);

        $entityManager->persist($newModel);
        $entityManager->flush();

        return $this->json(['message' => 'Model updated successfully']);
    }



    // #[Route('/{id}/{text}/{isproj}/{isworker}', name: 'createModel', methods: ['POST'])]
    // public function createModel(EntityManagerInterface $entityManager, string $id, string $text,int $isproj,int $isworker): Response
    // {
    // $newModel = new Models();
    // $newModel->setId($id);
    // $newModel->setText($text);
    // $newModel->setConcernsProject($isproj);
    // $newModel->setConcernsWorker($isworker);

    // $entityManager->persist($newModel);
    // $entityManager->flush();

    // return $this->json(['message' => 'Model created successfully']);
    // }
    // ADDITION WITH A LINK CHECK MODEL-CREATOR

}
