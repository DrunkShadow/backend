<?php

namespace App\Controller;

use App\Entity\Keywords;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Keywords')]
class KeywordsController extends AbstractController
{

    #[Route('/keywords', name: 'getAllKeywords', methods: 'GET')]
    public function GetKeywords(EntityManagerInterface $entityManager): Response
    {
        $keywords = $entityManager->getRepository(Keywords::class)->findAll();

        if (!$keywords) {
            return $this->json(['error' => 'No keywords found'], 404);
        }

        return $this->json($keywords);
    }


    #[Route('/keywordsObjects', name: 'getAllKeywordsObjects', methods: 'GET')]

    public function GetObjects(EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery('SELECT DISTINCT k.keywordConcernedObject FROM App\Entity\Keywords k');
        $result = $query->getResult();

        if (!$result) {
            return $this->json(['error' => 'No keywords found'], 404);
        }

        return $this->json($result);
    }
}
