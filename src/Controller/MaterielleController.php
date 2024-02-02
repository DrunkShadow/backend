<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Materielle;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;



#[OA\Tag(name: 'Materielle')]
#[Route('/materielle', name: 'serving', methods: 'GET')]
class MaterielleController extends AbstractController
{
    #[Route('', name: 'getAllMaterielle', methods: 'GET')]
    public function GetMaterielle(EntityManagerInterface $entityManager): Response
    {
        $mat = $entityManager->getRepository(Materielle::class)->findAll();

        if (!$mat) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($mat);
    }

    #[Route('/{reference}', name: 'getMaterielleBy', methods: ['GET'],)]
    public function GetProjectById(EntityManagerInterface $entityManager, string $reference): Response
    {
        $Materielle = $entityManager->getRepository(Materielle::class)->find($reference);

        if (!$Materielle) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($Materielle);
    }

    #[Route('/{reference}', name: 'DeleteModel', methods: ['DELETE'],)]
    public function DeleteModel(EntityManagerInterface $entityManager, Request $request, string $reference): Response
    {
        $mat = $entityManager->getRepository(Materielle::class)->find($reference);
        if (!$mat) {
            return $this->json(['error' => 'Model not found'], 404);
        }
        $entityManager->remove($mat);
        $entityManager->flush();
        return $this->json(['message' => 'Model deleted successfully']);
    }

    #[Route('', name: 'createModel', methods: ['POST'], format: 'json')]
    public function createModel(EntityManagerInterface $entityManager, Request $request,LoggerInterface $log): Response
    {
        $newMaterielle = new Materielle();
        $data = json_decode($request->getContent(), true);
        $newMaterielle->setReference($data['reference']);
        $newMaterielle->setTitre($data['titre']);
        $newMaterielle->setDescription($data['description']);
        $newMaterielle->setCategorie($data['categorie']);
        $newMaterielle->setPrix($data['prix']);
        $newMaterielle->setDate($data['date']);
        $entityManager->persist($newMaterielle);
        $entityManager->flush();
        return $this->json(['message' => 'Model updated successfully']);
    }

    #[Route('/{reference}', name: 'putModelById', methods: ['PUT'],)]
    public function PutModel(EntityManagerInterface $entityManager, Request $request, string $reference): Response
    {
        $materielle = $entityManager->getRepository(Materielle::class)->find($reference);

        if (!$materielle) {
            return $this->json(['error' => 'Model not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $materielle->setReference($data['reference']);
        $materielle->setTitre($data['titre']);
        $materielle->setDescription($data['description']);
        $materielle->setCategorie($data['categorie']);
        $materielle->setPrix($data['prix']);
        $materielle->setDate($data['date']);

        $entityManager->flush();

        return $this->json(['message' => 'Model updated successfully']);
    }








}

