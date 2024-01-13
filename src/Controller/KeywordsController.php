<?php

namespace App\Controller;

use App\Entity\Keywords;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class KeywordsController extends AbstractController
{
    public function GetKeywords(EntityManagerInterface $entityManager): Response
    {
        $keywords = $entityManager->getRepository(Keywords::class)->findAll();

        if (!$keywords) {
            return $this->json(['error' => 'No projects found'], 404);
        }

        return $this->json($keywords);
    }

    

    public function GetObjects(EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery('SELECT DISTINCT k.concernedObject FROM App\Entity\Keywords k');
        $result = $query->getResult();
    
        if (!$result) {
            return $this->json(['error' => 'No projects found'], 404);
        }
    
        return $this->json($result);
    }
}

        