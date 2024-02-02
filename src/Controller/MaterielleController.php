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
#[Route('/materielle', name: 'serving ', methods: 'GET')]
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

    #[Route('/{reference}', name: 'DeleteMaterielle', methods: ['DELETE'],)]
    public function DeleteMaterielle(EntityManagerInterface $entityManager, Request $request, string $reference): Response
    {
        $mat = $entityManager->getRepository(Materielle::class)->find($reference);
        if (!$mat) {
            return $this->json(['error' => 'Materielle not found'], 404);
        }
        $entityManager->remove($mat);
        $entityManager->flush();
        return $this->json(['message' => 'Materielle deleted successfully']);
    }

    #[Route('', name: 'createMaterielle', methods: ['POST'], format: 'json')]
    public function createMaterielle(EntityManagerInterface $entityManager, Request $request): Response
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
        return $this->json(['message' => 'Materielle updated successfully']);
    }

    #[Route('/update/{reference}', name: 'putMaterielleById', methods: ['PUT'],)]
    public function PutMaterielle(LoggerInterface $log,EntityManagerInterface $entityManager, Request $request, string $reference): Response
    {
        $materielle = $entityManager->getRepository(Materielle::class)->find($reference);

        if (!$materielle) {
            return $this->json(['error' => 'Materielle not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $log->info("*************************************************");
        $log->info($data['description']);
        $materielle->setTitre($data['titre']);
        $materielle->setDescription($data['description']);
        $materielle->setCategorie($data['categorie']);
        $materielle->setPrix($data['prix']);
        $materielle->setDate($data['date']);

        $entityManager->flush();

        return $this->json(['message' => 'Materielle updated successfully']);
    }

    #[Route('/Categories/distinct', name: 'getDistinctCategories', methods: 'GET')]
    public function GetDistinctCategories(EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery('SELECT DISTINCT m.categorie FROM App\Entity\Materielle m');
        $result = $query->getResult();
        if (!$result) {
            return $this->json(['error' => 'No categories found'], 404);
        }
        return $this->json($result);
    }








}

